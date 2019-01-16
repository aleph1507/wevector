<?php

namespace App\Http\Controllers;

use App\Mail\NewOrder;
use App\Traits\TrelloTrait;
use App\Traits\GoogleTrait;
use Illuminate\Http\Request;
use App\Order;
use App\Traits\FolderStructure;
use Image;
use File;
use Session;
use Auth;
use Storage;
use Mail;
use Carbon\Carbon;

class OrderController extends Controller
{
    use FolderStructure;
    use TrelloTrait;
    use GoogleTrait;

    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index()
    {
      return view('orders.index');
    }

    public function create()
    {
      return view('orders.create');
    }

    public function trelloOrder($user, $order, $driveLink)
    {
        $wv_board_id = $this->check_board();

        $name = $user->username . '_' . $order->created_at . '_' . $order->id;
        $board_res = $this->new_board($name);
        if($this->getResStatusCode($board_res) != 200)
            return redirect('/home')->withErrors(['There\'s been a problem placing the order']);

        $board = json_decode($this->getResBody($board_res));

        $lists = json_decode($this->getResBody($this->get_boards_lists($board->id)));

        $wv_board_lists = json_decode($this->getResBody($this->get_boards_lists($wv_board_id)));

        $new_card_desc = "The customer has named the order $order->name.%0AIt's orientation is $order->orientation.%0ALink to Google Drive: $driveLink%0A";

        if($order->color_scheme != null)
            $new_card_desc .= "It's color scheme is $order->color_scheme.%0A";

        $new_card_desc .= "Width: $order->width $order->units, Height: $order->height $order->units.%0A";

        $this->new_order_card($lists[0]->id, "New order from $user->username", $new_card_desc);

        $this->new_order_card($wv_board_lists[0]->id, "New order from $user->username", $new_card_desc);

        return $board;
    }

    public function googleOrder($order)
    {
        $driveService = $this->get_drive_service();
        $user_folder = array($this->folder_exist($driveService, Auth::user()->username));
        $folder_name = Carbon::now()->toDateString() . '_' . $order->name;
        $project_folder = array($this->new_folder($driveService, $folder_name, $user_folder));
        $projectImg = $this->drive_image($order->file, "images/$order->id/", $driveService, $project_folder);
        if($order->additional_files != null)
        {
            $additional_folder = array($this->new_folder($driveService, 'Additional_files', $project_folder));
            $add_files = explode(';', $order->additional_files);
            for($i = 0; $i<count($add_files); $i++)
            {
                $this->drive_image($add_files[$i], "images/$order->id/additional_files/",
                    $driveService, $additional_folder);
            }
        }

        $this->add_domain_permissions($driveService, $project_folder);

        return ['project_folder' => $driveService->files->get($project_folder, array("fields" => "webViewLink")),
                'primary_image' => $driveService->files->get($projectImg->id, array('fields' => 'webViewLink'))];
    }

    public function store(Request $request)
    {
      $this->validate($request, [
        'name' => 'required',
        'orientation' => 'required|in:portrait,landscape',
        'color_scheme' => 'nullable|in:rgb,cmyk,null',
        'file' => 'required|image',
        'width' => 'required|numeric',
        'height' => 'required|numeric',
        'art_units' => 'required|in:mm,cm,in',
        'additional_files.*' => 'nullable|image'
      ]);

      $this->clean_tmp();

      $tmp_additional_files = [];
      $additional_files_count = 0;
      $additional_files_field = null;

      $tmp = $this->find_or_make_path('tmp');
      $tmp_add_path = $this->find_or_make_path('tmp/additional');
      $tmp_thumb_path = $this->find_or_make_path('tmp/thumb');
      $tmp_sm_thumb_path = $this->find_or_make_path('tmp/thumb/sm');

      $new_file_name = $request->file('file')->getClientOriginalName();

      $tmp_img = $this->intervene_image($request->file('file'), $new_file_name, $tmp);
      $tmp_thumb_img =
        $this->intervene_thumb_image($request->file('file'), $tmp_img, $tmp_thumb_path);
      $tmp_sm_thumb_img =
        $this->intervene_image($request->file('file'), $new_file_name, $tmp_sm_thumb_path, 61, 60);

      if($request->file('additional_files'))
      {
        foreach($request->file('additional_files') as $add_file)
        {
          $tmp_additional_files[] =
            $this->intervene_image($add_file,
              $additional_files_count++ . time() . '.' . $add_file->getClientOriginalExtension(),
              $tmp_add_path);
        }
        $additional_files_field = implode(';', $tmp_additional_files);
      }

      $no = Order::create(['name' => $request->name,
            'orientation' => $request->orientation, 'width' => $request->width,
            'height' => $request->height, 'units' => $request->art_units,
            'color_scheme' => isset($request->color_scheme) ? $request->color_scheme : null,
            'file' => $tmp_img,
            'additional_files' => $additional_files_field,
            'user_id' => Auth::user()->id]);

      $this->mv_tmp_image($no->file, $no->id);

      if($this->mv_additional_images($no->additional_files, $no->id) != 0)
        return redirect('/home')->withErrors(['There\'s been a problem placing the order']);

      $drive = $this->googleOrder($no);

//      dd($drive['project_folder']["webViewLink"]);

      $trello = $this->trelloOrder(Auth::user(), $no, $drive['project_folder']['webViewLink']);

//      dd($trello->shortUrl);

      Mail::to('info@thinkerlab.io')->send(new
        NewOrder($drive['primary_image']['webViewLink'], $drive['project_folder']['webViewLink'], $trello->shortUrl, $no->id));

      Session::flash('success', 'Successfully ordered');
      return redirect('/home');
    }

    public function view($id)
    {
      return view('orders.view')->with('order', Auth::user()->orders->find($id));
    }

    public function activeOrders(Request $request)
    {
      $data = null;
      $sortBy = $request->query('sortBy', '');
      $direction = $request->query('direction', '');
      $queryString = $request->query('queryString', '');
//        return json_encode(['sortBy' => $sortBy, 'direction' => $direction, 'queryString' => $queryString]);
      if($sortBy != '' && $direction != '') {
          $direction = ($direction == 'asc' ? 'asc' : 'desc');
          switch($sortBy) {
              case 'Name':
                  $sortBy = 'name';
                  break;
              case 'ID':
                  $sortBy = 'id';
                  break;
              case 'Type':
                  $sortBy = 'type';
                  break;
              case 'Sent':
                  $sortBy = 'created_at';
                  break;
              case 'Status':
                  $sortBy = 'status';
                  break;
              case 'Comment':
                  $sortBy = 'comments';
                  break;
              default:
                  $sortBy = '';
          }
      }
//      return json_encode(['sortBy' => $sortBy, 'direction' => $direction]);

      if($queryString != '') {
          if($sortBy == '') {
//              return json_encode(['sortBy' => 'empty', 'queryString' => $queryString]);
              $data = json_encode(Order::active()->where('name', 'like', '%' . $queryString . '%')
                  ->orWhere('file', 'like', '%' . $queryString . '%')
                  ->orWhere('created_at', 'like', '%' . $queryString . '%')->paginate(5));
          } else {
              $data = json_encode(Order::active()->where('name', 'like', '%' . $queryString . '%')
                  ->orWhere('file', 'like', '%' . $queryString . '%')
                  ->orWhere('created_at', 'like', '%' . $queryString . '%')
                  ->orderBy($sortBy, $direction)
                  ->paginate(2));
          }
      } else {
          if($sortBy == '') {
              $data = json_encode(Order::active()->latest()->paginate(5));
          } else {
              $data = json_encode(Order::active()->orderBy($sortBy, $direction)->paginate(5));
          }

      }

      return $data;
//      return json_encode(Order::active()->latest()->paginate(2));
    }

    public function completedOrders(Request $request)
    {
        $data = null;
        $sortBy = $request->query('sortBy', '');
        $direction = $request->query('direction', '');
        $queryString = $request->query('queryString', '');
//        return json_encode(['sortBy' => $sortBy, 'direction' => $direction, 'queryString' => $queryString]);
        if($sortBy != '' && $direction != '') {
            $direction = ($direction == 'asc' ? 'asc' : 'desc');
            switch($sortBy) {
                case 'Name':
                    $sortBy = 'name';
                    break;
                case 'ID':
                    $sortBy = 'id';
                    break;
                case 'Type':
                    $sortBy = 'type';
                    break;
                case 'Sent':
                    $sortBy = 'created_at';
                    break;
                case 'Status':
                    $sortBy = 'status';
                    break;
                case 'Comment':
                    $sortBy = 'comments';
                    break;
                default:
                    $sortBy = '';
            }
        }
        if($queryString != '') {
            if($sortBy == '') {
                $data = json_encode(Order::completed()->where('name', 'like', '%' . $queryString . '%')
                    ->orWhere('file', 'like', '%' . $queryString . '%')
                    ->orWhere('created_at', 'like', '%' . $queryString . '%')->paginate(5));
            } else {
                $data = json_encode(Order::completed()->where('name', 'like', '%' . $queryString . '%')
                    ->orWhere('file', 'like', '%' . $queryString . '%')
                    ->orWhere('created_at', 'like', '%' . $queryString . '%')
                    ->orderBy($sortBy, $direction)
                    ->paginate(5));
            }
        } else {
            if($sortBy == '') {
                $data = json_encode(Order::completed()->latest()->paginate(5));
            } else {
                $data = json_encode(Order::completed()->orderBy($sortBy, $direction)->paginate(5));
            }

        }
      return $data;
    }

    public function changeStatus(Request $request) {
        // $table->enum('status', ['Recieved', 'In Process', 'On Hold', 'Completed'])->default('Recieved');
        if($request->status && $request->id) {
            switch($request->status) {
                case 'Received':
                case 'In Process':
                case 'On Hold':
                case 'Completed':
                    break;
                default:
//                    Session::flash('Status is not recognised.');
                    return redirect()->back()->withErrors('Status is not recognised.');
            }
            $o = Order::find($request->id);
            $o->status = $request->status;
            $o->save();
            Session::flash('success', 'Status changed.');
            return back();
        }

    }
}

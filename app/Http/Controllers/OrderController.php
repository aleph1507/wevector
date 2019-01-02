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

      Mail::to('xrristo@gmail.com')->send(new
        NewOrder($drive['primary_image']['webViewLink'], $drive['project_folder']['webViewLink'], $trello->shortUrl));

      Session::flash('success', 'Successfuly ordered');
      return redirect('/home');
    }

    public function view($id)
    {
      return view('orders.view')->with('order', Auth::user()->orders->find($id));
    }

    public function activeOrders()
    {
      return json_encode(Order::active()->latest()->paginate(2));
    }

    public function completedOrders()
    {
      return json_encode(Order::completed()->latest()->paginate(2));
    }
}

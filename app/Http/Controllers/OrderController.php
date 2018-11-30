<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Traits\FolderStructure;
use Image;
use File;
use Session;
use Auth;

class OrderController extends Controller
{
    use FolderStructure;

    public function create()
    {
      return view('orders.create');
    }

    public function store(Request $request)
    {
      $this->validate($request, [
        'name' => 'required',
        'orientation' => 'required|in:portrait,landscape',
        'color_scheme' => 'nullable|in:rgb,cmyk,null',
        'file' => 'required|image',
        'additional_files.*' => 'nullable|image'
      ]);


      $this->clean_tmp();

      $tmp_additional_files = [];
      $additional_files_count = 0;
      $additional_files_field = null;

      $tmp = $this->find_or_make_path('tmp');
      $tmp_add_path = $this->find_or_make_path('tmp/additional');
      $tmp_thumb_path = $this->find_or_make_path('tmp/thumb');

      $tmp_img = $this->intervene_image($request->file('file'), time(), $tmp);
      $tmp_thumb_img = $this->intervene_thumb_image($request->file('file'), $tmp_img, $tmp_thumb_path);

      if($request->file('additional_files'))
      {
        foreach($request->file('additional_files') as $add_file)
        {
          $tmp_additional_files[] =
            $this->intervene_image($add_file, $additional_files_count++ . time(), $tmp_add_path);
        }
        $additional_files_field = implode(';', $tmp_additional_files);
      }

      $no = Order::create(['name' => $request->name,
            'orientation' => $request->orientation,
            'color_scheme' => isset($request->color_scheme) ? $request->color_scheme : null,
            'file' => $tmp_img,
            'additional_files' => $additional_files_field,
            'user_id' => Auth::user()->id]);

      $this->mv_tmp_image($no->file, $no->id);

      if($this->mv_additional_images($no->additional_files, $no->id) != 0)
      {
        return redirect('/home')->withErrors(['There\'s been a problem placing the order']);
      }

      Session::flash('success', 'Successfuly ordered');
      return redirect('/home');

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Traits\FolderStructure;
use Image;
use File;

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
        'color_scheme' => 'sometimes|in:rgb,cmyk',
        'file' => 'required|image',
        'additional_files.*' => 'sometimes|image'
      ]);

      $this->clean_tmp();

      $tmp_additional_files = [];
      $additional_files_count = 0;
      $additional_files_field = null;

      $tmp = $this->find_or_make_path('tmp');
      $tmp_add_path = $this->find_or_make_path('tmp/additional');

      $tmp_img = $this->intervene_image($request->file('file'), time(), $tmp);

      // if(count($request->file('additional_files'))>0)
      if($request->file('additional_files'))
      {
        foreach($request->file('additional_files') as $add_file)
        {
          $tmp_additional_files[] =
          $this->intervene_image($add_file, $additional_files_count++ . time(), $tmp_add_path);
        }
        $additional_files_field = implode('.', $tmp_additional_files);
      }

      $no = Order::create(['name' => $request->name,
            'orientation' => $request->orientation,
            'color_scheme' => $request->color_scheme, 'file' => $tmp_img,
            'additional_files' => $additional_files_field]);

      var_dump($no);

    }
}

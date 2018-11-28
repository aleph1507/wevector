<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Order;
use App\Traits\FolderStructure;
use Image;

class OrderController extends Controller
{
    use FolderStructure;

    public function intervene_image($imgFile, $name)
    {
      $name .= $imgFile->getClientOriginalExtension();
      return Image::make($imgFile);
    }

    public function locate_image($img, $path)
    {
      $this->find_od_make_path($path);
    }

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

      intervene_image($request->file('file'), time());

      // $no = Order::create(request(['name', 'orientation', 'color_scheme']));
      //
      // echo '<br>' . $no->name . '<br>' . $no->orientation . '<br>' . $no->color_scheme;
    }
}

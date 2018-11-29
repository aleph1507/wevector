<?php

namespace App\Traits;
use File;
use Illuminate\Filesystem\Filesystem;
use Image;

trait FolderStructure
{
  public function find_or_make_path($path)
  {
    $path = public_path($path);
    File::isDirectory($path) or File::makeDirectory($path, 0777, true, false);
    return $path;
  }

  public function clean_tmp()
  {
    $file = new Filesystem;
    if(File::isDirectory(public_path('tmp/additional')))
      $file->cleanDirectory(public_path('tmp/additional'));
    if(File::isDirectory(public_path('tmp')))
      $file->cleanDirectory(public_path('tmp'));
  }

  public function intervene_image($imgFile, $name, $path)
  {
    $name .= '.' . $imgFile->getClientOriginalExtension();
    $location = $path . '/' . $name;
    Image::make($imgFile)->save($location);
    return $name;
  }

  public function mv_tmp_image($name, $id)
  {
    $new_path = $this->find_or_make_path(public_path('images/' . $id));
    return File::move(public_path('tmp/' . $name), $new_path);
  }
}

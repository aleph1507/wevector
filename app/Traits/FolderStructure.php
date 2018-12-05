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
    if(File::isDirectory(public_path('tmp/thumb')))
      $file->cleanDirectory(public_path('tmp/thumb'));
    if(File::isDirectory(public_path('tmp')))
      $file->cleanDirectory(public_path('tmp'));
  }

  public function intervene_image($imgFile, $name, $path, $fitX=null, $fitY=null)
  {
    $name .= '.' . $imgFile->getClientOriginalExtension();
    $location = $path . '/' . $name;
    if($fitX == null || $fitY == null)
      Image::make($imgFile)->save($location);
    else
      Image::make($imgFile)->fit($fitX,$fitY)->save($location);

    return $name;
  }

  public function intervene_thumb_image($imgFile, $name, $path)
  {
    $location_thumb = $path . '/' . $name;
    Image::make($imgFile)->resize(null, 200, function($constraint) {
      $constraint->aspectRatio();
      $constraint->upsize();
    })->save($location_thumb);

    return $name;
  }

  public function mv_tmp_image($name, $id)
  {
    $new_path = $this->find_or_make_path('images/' . $id);
    $new_thumb_path = $this->find_or_make_path('images/' . $id . '/thumb');
    $new_sm_thumb_path = $this->find_or_make_path('images/' . $id . '/thumb' . '/sm');
    File::move(public_path('tmp/thumb/sm/' . $name), $new_sm_thumb_path . '/' . $name);
    File::move(public_path('tmp/thumb/' . $name), $new_thumb_path . '/' . $name);
    return File::move(public_path('tmp/' . $name), $new_path . '/' . $name);
  }

  public function mv_additional_images($names, $id)
  {
    $new_path = $this->find_or_make_path('images/' . $id . '/additional_files');
    $additional_files = explode(';', $names);
    foreach($additional_files as $add_file)
    {
      if(!File::move(public_path('tmp/additional/' . $add_file),
          $new_path . '/' . $add_file))
      {
        return 1;
      }
    }
    return 0;
  }
}

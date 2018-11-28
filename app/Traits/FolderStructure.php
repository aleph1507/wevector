<?php

namespace App\Traits;
use File;

trait FolderStructure
{
  protected function find_od_make_path($path)
  {
    $path = public_path() . $path;
    File::isDirectory($path) or File::makeDirectory($path, 0777, true, false);
  }
}

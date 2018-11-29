<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['name', 'orientation',
      'color_scheme', 'file', 'additional_files'];
}

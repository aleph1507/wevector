<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['name', 'orientation', 'width', 'height',
    'units', 'color_scheme', 'file', 'additional_files', 'user_id'];

    public function user()
    {
      return $this->belongsTo('App\User');
    }

    public static function completed()
    {
      return static::orderBy('updated_at', 'desc')->where('completed', true)->get();
    }

    public static function active()
    {
      return static::orderBy('updated_at', 'desc')->where('completed', false)->get();
    }
}

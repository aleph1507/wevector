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

    public static function scopeCompleted($query, $val=null)
    {
      return $query->where('completed', true);
    }

    public static function scopeActive($query, $val=null)
    {
      return $query->where('completed', false);
    }
}

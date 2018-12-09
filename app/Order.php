<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

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
      // return $query->where('completed', true);
      return $query->where('status', 'completed');
    }

    public static function scopeActive($query, $val=null)
    {
      // return $query->where('completed', false);
      return $query->where('status', '!=', 'Completed');
    }

    public function getCreatedAtAttribute($date)
    {
      return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
    }

    public function getUpdatedAtAttribute($date)
    {
      return Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('d/m/Y');
    }
}

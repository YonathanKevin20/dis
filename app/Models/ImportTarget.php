<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportTarget extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'products_id', 'id');
    }
}

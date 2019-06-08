<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryOrder extends Model
{
    protected $guarded = ['id'];

    public function spv()
    {
        return $this->belongsTo('App\User', 'spvs_id', 'id');
    }

    public function sales()
    {
        return $this->belongsTo('App\User', 'sales_id', 'id');
    }
}

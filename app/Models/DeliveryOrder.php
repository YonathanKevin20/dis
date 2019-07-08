<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryOrder extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];

    public function spv()
    {
        return $this->belongsTo('App\User', 'spvs_id', 'id');
    }

    public function sales()
    {
        return $this->belongsTo('App\User', 'sales_id', 'id');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle', 'vehicles_id', 'id');
    }

    public function invoice()
    {
        return $this->hasMany('App\Models\Invoice', 'delivery_orders_id', 'id');
    }
}

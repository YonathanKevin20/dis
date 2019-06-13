<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function store()
    {
        return $this->belongsTo('App\Models\Store', 'stores_id', 'id');
    }

    public function sales()
    {
        return $this->belongsTo('App\User', 'sales_id', 'id');
    }

    public function deliveryOrder()
    {
        return $this->belongsTo('App\Models\DeliveryOrder', 'delivery_orders_id', 'id');
    }
}

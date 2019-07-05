<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceProduct extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = [
        'qty' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'products_id', 'id');
    }

    public function invoice()
    {
        return $this->belongsTo('App\Models\Invoice', 'invoices_id', 'id');
    }
}

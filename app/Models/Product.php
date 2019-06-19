<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	protected $guarded = ['id'];
    protected $casts = [
        'price' => 'integer',
    ];
	public $timestamps = false;

    public function invoiceProduct()
    {
        return $this->hasMany('App\Models\InvoiceProduct', 'products_id', 'id');
    }
}

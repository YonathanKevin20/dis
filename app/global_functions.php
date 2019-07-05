<?php
function generateNoDeliveryOrder($id, $kode = 'DO')
{
    $date = date('ymd');
    $kode .= $date;
    $prefix_id = 3-strlen($id);
    for($i = 0; $i < $prefix_id; $i++) {
        $kode .= "0";
    }
    $kode .= $id;
    return $kode;
}

function getNoDeliveryOrder()
{
    $model = App\Models\DeliveryOrder::latest()->whereDay('created_at', date('d'))->first();
    if($model) {
        $id = substr($model->no_delivery_order, -1)+1;
    }
    else {
        $id = 1;
    }
    return generateNoDeliveryOrder($id);
}
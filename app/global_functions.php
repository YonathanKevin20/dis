<?php
function generateNoDeliveryOrder($id, $kode = 'DF')
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
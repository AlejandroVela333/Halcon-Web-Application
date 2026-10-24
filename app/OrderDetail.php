<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['order_id', 'material_id', 'quantity'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}

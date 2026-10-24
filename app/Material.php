<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = ['name', 'stock'];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['customer_number', 'name', 'fiscal_data'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

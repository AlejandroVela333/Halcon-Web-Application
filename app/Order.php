<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    const STATUS_ORDERED = 'Ordered';
    const STATUS_IN_PROCESS = 'In Process';
    const STATUS_IN_ROUTE = 'In Route';
    const STATUS_DELIVERED = 'Delivered';

    const STATUSES = [
        self::STATUS_ORDERED,
        self::STATUS_IN_PROCESS,
        self::STATUS_IN_ROUTE,
        self::STATUS_DELIVERED,
    ];

    protected $fillable = [
        'invoice_number', 'customer_id', 'created_by', 'order_date',
        'delivery_address', 'notes', 'status',
    ];

    protected $dates = ['order_date', 'deleted_at'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function evidencePhotos()
    {
        return $this->hasMany(EvidencePhoto::class);
    }
}

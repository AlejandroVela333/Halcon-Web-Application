<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvidencePhoto extends Model
{
    const TYPE_LOADED = 'Loaded';
    const TYPE_DELIVERY = 'Delivery';

    protected $fillable = [
        'order_id', 'uploaded_by', 'type', 'file_path', 'uploaded_at',
    ];

    protected $dates = ['uploaded_at'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

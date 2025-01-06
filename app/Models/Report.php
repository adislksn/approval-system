<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'user_id',
        'uuid',
        'nopol',
        'tahun',
        'merk',
        'shipping_type',
        'kilometer',
        'description_service',
        'service_location',
        'images',
    ];

    protected $casts = [
        'description_service' => 'array',
        'images' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

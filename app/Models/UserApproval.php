<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApproval extends Model
{
    protected $table = 'user_approvals';
    protected $fillable = [
        'user_id',
        'ttd_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

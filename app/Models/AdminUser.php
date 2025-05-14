<?php

namespace App\Models;

use Database\Factories\AdminUserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    /** @use HasFactory<AdminUserFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'staff_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = "user_meta";
    use HasFactory;

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}

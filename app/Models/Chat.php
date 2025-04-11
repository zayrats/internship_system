<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $fillable = ['user_id', 'message', 'image'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

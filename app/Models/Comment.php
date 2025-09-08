<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public function replies()
    {
        return $this->hasMany(CommentReply::class, "comment_id", "id");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

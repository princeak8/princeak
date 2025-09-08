<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function coverPhoto()
    {
        return $this->belongsTo(File::class, "cover_photo_id", "id");
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, "post_tags", "post_id", "tag_id");
    }

    public function postTags()
    {
        return $this->hasMany(PostTag::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy("created_at", "DESC");
    }
}

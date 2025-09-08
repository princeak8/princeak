<?php

namespace App\Services;

use App\Models\Comment;

use App\Enums\Status;

class CommentService
{
    public $count = false;

    public function comments($postId)
    {
        $query = Comment::where("post_id", $postId);

        if($this->count) return $query->count();

        return $query->orderBy("created_at", "DESC")->get();
    }

    public function getCommentById($id)
    {
        return Comment::where("id", $id)->first();
    }

    public function save($data)
    {
        $comment = new Comment;

        $comment->message = $data['message'];
        $comment->post_id = $data['postId'];
        $comment->user_id = $data['userId'];

        $comment->save();

        return $comment;
    }
}
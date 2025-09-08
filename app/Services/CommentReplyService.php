<?php

namespace App\Services;

use App\Models\CommentReply;

class CommentReplyService
{
    public $count = false;

    public function replies($commentId)
    {
        $query = CommentReply::where("comment_id", $commentId);

        if($this->count) return $query->count();

        return $query->orderBy("created_at", "DESC")->get();
    }

    public function getReplyById($id)
    {
        return CommentReply::where("id", $id)->first();
    }

    public function save($data)
    {
        $reply = new CommentReply;

        $reply->message = $data['message'];
        $reply->comment_id = $data['commentId'];
        $reply->user_id = $data['userId'];

        $reply->save();

        return $reply;
    }
}
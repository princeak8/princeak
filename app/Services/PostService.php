<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostTag;

use App\Enums\Status;

class PostService
{
    public $categoryId = null;
    public $count = false;

    public function posts($limit=null)
    {
        $query = Post::query();

        if($this->categoryId) $query->where("category_id", $this->categoryId);

        if($this->count) return $query->count();

        if($limit) $query->limit($limit);
        return $query->orderBy("created_at", "DESC")->get();
    }

    public function recentPosts($currentPostId=null)
    {
        $limit = 5;
        $query = Post::query();

        if($currentPostId) $query->where("id", "!=", $currentPostId);

        if($limit) $query->limit($limit);
        return $query->orderBy("created_at", "DESC")->get();
    }

    public function getPostBySlug($slug)
    {
        return Post::where("slug", $slug)->first();
    }

    public function getPostById($id)
    {
        return Post::where("id", $id)->first();
    }

    public function getPostByTitle($title)
    {
        return Post::where("title", $title)->first();
    }

    public function save($data)
    {
        $post = new Post;

        $post->title = $data["title"];
        $post->slug = $this->generateSlug($data["title"]);
        $post->preview = $data['preview'];
        if(isset($data['coverPhotoId'])) $post->cover_photo_id = $data['coverPhotoId'];
        $post->content = $data['content'];
        if(isset($data['categoryId'])) $post->category_id = $data['categoryId'];
        $post->user_id = $data['userId'];

        $post->save();

        return $post;
    }

    public function addTags($post, $tagIds)
    {
        if(count($tagIds) > 0) {
            foreach($tagIds as $tagId) {
                $postTag = new PostTag;
                $postTag->post_id = $post->id;
                $postTag->tag_id = $tagId;
                $postTag->save();
            }
        }
    }

    public function update($data, $post)
    {
        if(isset($data["title"])) {
            $post->title = $data["title"];
            $post->slug = $this->generateSlug($data["title"]);
        }
        if(isset($data['preview'])) $post->preview = $data['preview'];
        if(isset($data['coverPhotoId'])) $post->cover_photo_id = $data['coverPhotoId'];
        if(isset($data['content'])) $post->content = $data['content'];
        if(isset($data['categoryId'])) $post->category_id = $data['categoryId'];

        $post->update();

        return $post;
    }

    public function publish($post)
    {
        $post->published = true;
        $post->published_at = now();
        $post->status = Status::PUBLISHED->value;

        $post->update();

        return $post;
    }

    public function unpublish($post)
    {
        $post->published = false;
        $post->status = Status::DRAFT->value;

        $post->update();

        return $post;
    }

    public function hide($post)
    {
        $post->visible = false;
        $post->status = Status::HIDDEN->value;

        $post->update();

        return $post;
    }

    public function archive($post)
    {
        $post->status = Status::ARCHIVED->value;

        $post->update();

        return $post;
    }

    private function generateSlug($title)
    {
        $titleArr = explode(' ', $title);
        return implode("-", $titleArr);
    }
}
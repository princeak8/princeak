<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\SaveComment;
use App\Http\Requests\SaveCommentReply;

use App\Http\Resources\PostResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\TagResource;

use App\Services\PostService;
use App\Services\TagService;
use App\Services\CategoryService;
use App\Services\FileService;
use App\Services\CommentService;
use App\Services\CommentReplyService;

use App\Enums\Status;

use App\Utilities;

class PostController extends Controller
{
    private $postService;
    private $commentService;
    private $commentReplyService;
    private $tagService;
    private $categoryService;
    private $fileService;

    public function __construct()
    {
        $this->postService = new PostService;
        $this->commentService = new CommentService;
        $this->commentReplyService = new CommentReplyService;
        $this->tagService = new TagService;
        $this->categoryService = new CategoryService;
        $this->fileService = new FileService;
    }

    public function index($slug)
    {
        $tags = $this->tagService->tags();
        $categories = $this->categoryService->categories();

        $post = $this->postService->getPostBySlug($slug);
        if(!$post) return back()->withErrors(["error" => "This Post does not exist"]);

        $recentPosts = $this->postService->recentPosts($post->id);

        return Inertia::render('Post', [
            'post' => new PostResource($post),
            'categories' => CategoryResource::collection($categories)->toArray(request()),
            'recentPosts' => PostResource::collection($recentPosts)->toArray(request()),
        ]);
    }

    public function category($name)
    {
        $categories = $this->categoryService->categories();
        $recentPosts = $this->postService->recentPosts();
        $category = $this->categoryService->getByName($name);
        if(!$category) return back()->withErrors(["error" => "This Category does not exist"]);

        $this->postService->categoryId = $category->id;

        $posts = $this->postService->posts();

        return Inertia::render('Category', [
            'category' => new CategoryResource($category),
            'posts' => PostResource::collection($posts)->toArray(request()),
            'categories' => CategoryResource::collection($categories)->toArray(request()),
            'recentPosts' => PostResource::collection($recentPosts)->toArray(request()),
        ]);
    }

    public function saveComment(SaveComment $request)
    {
        try{
            $data = $request->validated();
            $data["userId"] = Auth::user()->id;
            $comment = $this->commentService->save($data);

            return back();
        }catch (\Exception $e) {
            Utilities::error($e);
            return back()->withErrors([
                'error' => 'An error occurred while attempting to save a comment..',
            ]);
        }
    }

    public function saveCommentReply(SaveCommentReply $request)
    {
        try{
            $data = $request->validated();
            $data["userId"] = Auth::user()->id;
            $this->commentReplyService->save($data);

            return back();
        }catch (\Exception $e) {
            Utilities::error($e);
            return back()->withErrors([
                'error' => 'An error occurred while attempting to save a comment..',
            ]);
        }
    }
}

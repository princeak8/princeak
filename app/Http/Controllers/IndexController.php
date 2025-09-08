<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Http\Resources\PostResource;
use App\Http\Resources\CategoryResource;

use App\Services\PostService;
use App\Services\CategoryService;

class IndexController extends Controller
{
    protected $postService;
    protected $categoryService;

    public function __construct()
    {
        $this->postService = new PostService;
        $this->categoryService = new CategoryService;
    }

    public function index()
    {
        $recentPosts = $this->postService->posts(env("POST_LIMIT", 5));
        $categories = $this->categoryService->categories();
        $posts = $this->postService->posts();

        return Inertia::render('Index', [
            'posts' => PostResource::collection($posts)->toArray(request()),
            'categories' => CategoryResource::collection($categories)->toArray(request()),
            'recentPosts' => PostResource::collection($recentPosts)->toArray(request()),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Models\Post;
use App\Models\Category;

use App\Http\Resources\PostResource;

use App\Services\PostService;
use App\Services\CategoryService;
use App\Services\ContactMessageService;

class DashboardController extends Controller
{
    protected $postService;
    protected $categoryService;
    protected $messageService;

    public function __construct()
    {
        $this->postService = new PostService;
        $this->categoryService = new CategoryService;
        $this->messageService = new ContactMessageService;
    }

    public function index()
    {
        $limit = 5;
        $posts = $this->postService->posts($limit);
        $this->postService->count = true;
        $this->categoryService->count = true;
        $this->messageService->read = false;
        $this->messageService->count = true;
        // Get counts for dashboard stats
        $postsCount = $this->postService->posts();
        $categoriesCount = $this->categoryService->categories();
        $messagesCount = $this->messageService->messages();
        
        // Get recent posts (last 5 published posts)
        $recentPosts = PostResource::collection($posts)->toArray(request());
        // dd($recentPosts);

        return Inertia::render('Admin/Dashboard', [
            'postsCount' => $postsCount,
            'categoriesCount' => $categoriesCount,
            'recentPosts' => $recentPosts,
            'messagesCount' => $messagesCount
        ]);
    }
}

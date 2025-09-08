<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\SavePost;
use App\Http\Requests\PostToggle;
use App\Http\Requests\UpdatePost;

use App\Http\Resources\PostResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;

use App\Services\PostService;
use App\Services\TagService;
use App\Services\CategoryService;
use App\Services\FileService;
use App\Services\ContactMessageService;

use App\Enums\Status;

use App\Utilities;

class PostController extends Controller
{
    private $postService;
    private $tagService;
    private $categoryService;
    private $fileService;
    protected $messageService;

    protected $messagesCount;

    public function __construct()
    {
        $this->postService = new PostService;
        $this->tagService = new TagService;
        $this->categoryService = new CategoryService;
        $this->fileService = new FileService;
        $this->messageService = new ContactMessageService;

        $this->messageService->read = false;
        $this->messageService->count = true;
        $this->messagesCount = $this->messageService->messages();
        $this->messageService->count = null;
    }

    public function create()
    {
        $tags = $this->tagService->tags();
        $categories = $this->categoryService->categories();
        

        return Inertia::render('Admin/newPost', [
            'categories' => $categories,
            'tags' => $tags,
            'messagesCount' => $this->messagesCount
        ]);
    }

    public function posts()
    {
        $tags = $this->tagService->tags();
        $categories = $this->categoryService->categories();
        $posts = $this->postService->posts();
        

        return Inertia::render('Admin/posts', [
            'categories' => CategoryResource::collection($categories)->toArray(request()),
            'tags' => TagResource::collection($tags)->toArray(request()),
            'posts' => PostResource::collection($posts)->toArray(request()),
            'messagesCount' => $this->messagesCount
        ]);
    }

    public function post($postId)
    {
        $tags = $this->tagService->tags();
        $categories = $this->categoryService->categories();
        $posts = $this->postService->posts();

        
    }

    public function edit($postId)
    {
        $tags = $this->tagService->tags();
        $categories = $this->categoryService->categories();

        $post = $this->postService->getPostById($postId);
        if(!$post) return back()->withErrors(["error" => "This Post does not exist"]);

        return Inertia::render('Admin/EditPost', [
            'post' => new PostResource($post),
            'categories' => $categories,
            'tags' => $tags,
            'messagesCount' => $this->messagesCount
        ]);
    }

    public function save(SavePost $request)
    {
        try{
            $data = $request->validated();

            if ($request->hasFile('coverPhoto')) {
                $imageData["file"] = $request->file('coverPhoto');
                $imageData["fileType"] = "image";
                $coverPhoto = $this->fileService->save($imageData, 'coverPhotos');
                $data['coverPhotoId'] = $coverPhoto->id;
            }
            $data['userId'] = Auth::user()->id;
            $post = $this->postService->save($data);

            if($data['status'] == Status::PUBLISHED->value) {
                $post = $this->postService->publish($post);
            }

            if(isset($data['tagIds'])) $this->postService->addTags($post, $data['tagIds']);

            return redirect()->intended(route('admin.posts'));
            
        }catch (\Exception $e) {
            Utilities::error($e);
            return back()->withErrors([
                'error' => 'An error occurred while attempting to create a tag..',
            ]);
        }
    }

    public function update(UpdatePost $request, $postId)
    {
        try{
            $data = $request->validated();

            $post = $this->postService->getPostById($postId);
            if(!$post) return back()->withErrors(["error" => "This Post does not exist"]);

            $oldCoverPhoto = null;
            if ($request->hasFile('coverPhoto')) {
                $oldCoverPhoto = $post->coverPhoto;

                $imageData["file"] = $request->file('coverPhoto');
                $imageData["fileType"] = "image";
                $coverPhoto = $this->fileService->save($imageData, 'coverPhotos');
                $data['coverPhotoId'] = $coverPhoto->id;
            }
            $data['userId'] = Auth::user()->id;
            $post = $this->postService->update($data, $post);

            if($oldCoverPhoto) $oldCoverPhoto->delete();

            // $deletedTagIds = array_diff($data['tagIds'], $existingTagsArr);

            if(isset($data['tagIds'])) {
                $existingTagsArr = $post->tags->pluck("id")->toArray();

                $newTagIds = array_diff($data['tagIds'], $existingTagsArr);

                $this->postService->addTags($post, $newTagIds);

                if($post->postTags->count() > 0) {
                    foreach($post->postTags as $postTag) {
                        if(!in_array($postTag->tag_id, $data['tagIds'])) $postTag->delete(); 
                    }
                }
            }

            return redirect()->intended(route('admin.posts'));
            
        }catch (\Exception $e) {
            Utilities::error($e);
            return back()->withErrors([
                'error' => 'An error occurred while attempting to create a tag..',
            ]);
        }
    }

    public function togglePublish(PostToggle $request)
    {
        try{
            $post = $this->postService->getPostById($request->validated("id"));
            
            ($post->published == 0) ? $this->postService->publish($post) : $this->postService->unpublish($post);
            // dd($post->published);
            return back();
        }catch (\Exception $e) {
            Utilities::error($e);
            return back()->withErrors([
                'error' => 'An error occurred while attempting to perform this operation..',
            ]);
        }
    }
}

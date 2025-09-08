<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Http\Requests\SaveTag;
use App\Http\Requests\UpdateTag;

use App\Http\Resources\TagResource;

use App\Services\TagService;
use App\Services\ContactMessageService;

use App\Utilities;

class TagController extends Controller
{
    private $tagService;
    protected $messageService;

    protected $messagesCount;

    public function __construct()
    {
        $this->tagService = new TagService;
        $this->messageService = new ContactMessageService;

        $this->messageService->read = 0;
        $this->messageService->count = true;
        $this->messagesCount = $this->messageService->messages();
        $this->messageService->count = null;
    }

    public function tags()
    {
        $tags = $this->tagService->tags();

        return Inertia::render('Admin/Tags', [
            'tags' => TagResource::collection($tags)->toArray(request()),
            'messagesCount' => $this->messagesCount
        ]);
    }

    public function save(SaveTag $request)
    {
        try{
            $tag = $this->tagService->save($request->validated("name"));

            return Utilities::ok(new TagResource($tag));
        }catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'An error occurred while attempting to create a tag.',
            ]);
        }
    }

    public function add(SaveTag $request)
    {
        try{
            $tag = $this->tagService->save($request->validated("name"));

            return redirect()->intended(route('admin.tags'));
        }catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'An error occurred while attempting to create a tag.',
            ]);
        }
    }

    public function update(UpdateTag $request, $tagId)
    {
        try{
            $tag = $this->tagService->getTag($tagId);
            if(!$tag) return back()->withErrors(["error" => "This Tag does not exist"]);

            $tag = $this->tagService->update($request->validated("name"), $tag);

            return redirect()->intended(route('admin.tags'));
        }catch (\Exception $e) {
            return back()->withErrors([
                'error' => 'An error occurred while attempting to create a tag.',
            ]);
        }
    }

    public function delete($tagId)
    {
        try{
            $tag = $this->tagService->getTag($tagId);
            if(!$tag) return back()->withErrors(["error" => "This Tag does not exist"]);

            if($tag->posts->count() > 0) return back()->withErrors(["error" => "This Tag cannot be deleted"]);

            $this->tagService->delete($tag);

            return redirect()->intended(route('admin.tags'));
        }catch (\Exception $e) {
            Utilities::error($e);
            return back()->withErrors([
                'error' => 'An error occurred while attempting to delete Tag..',
            ]);
        }
    }
}

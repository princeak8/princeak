<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Http\Requests\AddCategory;
use App\Http\Requests\UpdateCategory;

use App\Http\Resources\CategoryResource;

use App\Services\CategoryService;
use App\Services\ContactMessageService;

use App\Utilities;

class CategoryController extends Controller
{
    protected $categoryService;
    protected $messageService;

    protected $messagesCount;

    public function __construct()
    {
        $this->categoryService = new CategoryService;
        $this->messageService = new ContactMessageService;
        $this->messageService->read = false;
        $this->messageService->count = true;
        $this->messagesCount = $this->messageService->messages();
        $this->messageService->count = null;
    }
    public function categories()
    {
        $categories = $this->categoryService->categories();

        return Inertia::render('Admin/Categories', [
            'categories' => CategoryResource::collection($categories)->toArray(request()),
            'messagesCount' => $this->messagesCount
        ]);
    }

    public function save(AddCategory $request)
    {
        try{
            $this->categoryService->save($request->validated());

            return redirect()->intended(route('admin.categories'));
        }catch (\Exception $e) {
            Utilities::error($e);
            return back()->withErrors([
                'error' => 'An error occurred while attempting to Add Category..',
            ]);
        }
    }

    public function update(AddCategory $request, $categoryId)
    {
        try{
            $category = $this->categoryService->category($categoryId);
            if(!$category) return back()->withErrors(["error" => "This Category does not exist"]);

            $this->categoryService->update($request->validated(), $category);

            return redirect()->intended(route('admin.categories'));
        }catch (\Exception $e) {
            Utilities::error($e);
            return back()->withErrors([
                'error' => 'An error occurred while attempting to update Category..',
            ]);
        }
    }

    public function delete($categoryId)
    {
        try{
            $category = $this->categoryService->category($categoryId);
            if(!$category) return back()->withErrors(["error" => "This Category does not exist"]);

            if($category->posts->count() > 0) return back()->withErrors(["error" => "This Category cannot be deleted"]);

            $this->categoryService->delete($category);

            return redirect()->intended(route('admin.categories'));
        }catch (\Exception $e) {
            Utilities::error($e);
            return back()->withErrors([
                'error' => 'An error occurred while attempting to delete Category..',
            ]);
        }
    }
}

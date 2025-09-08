<?php

namespace App\Services;

use App\Models\Category;

class CategoryService
{
    public $count = false;

    public function categories()
    {
        if($this->count) return Category::count();
        return Category::all();
    }

    public function category($id)
    {
        return Category::find($id);
    }

    public function getByName($name)
    {
        return Category::where("name", $name)->first();
    }

    public function save($data)
    {
        $category = new Category;
        $category->name = $data['name'];
        if(isset($data['description'])) $category->description = $data['description'];

        $category->save();

        return $category;
    }

    public function update($data, $category)
    {
        if(isset($data['name'])) $category->name = $data['name'];
        if(isset($data['description'])) $category->description = $data['description'];

        $category->update();

        return $category;
    }

    public function delete($category)
    {
        $category->delete();
    }
}
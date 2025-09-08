<?php

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function tags()
    {
        return Tag::all();
    }

    public function getTag($id)
    {
        return Tag::find($id);
    }

    public function save($name)
    {
        $tag = new Tag;
        $tag->name = $name;

        $tag->save();

        return $tag;
    }

    public function update($name, $tag)
    {
        $tag->name = $name;
        $tag->update();

        return $tag;
    }

    public function delete($tag)
    {
        $tag->delete();
    }
}
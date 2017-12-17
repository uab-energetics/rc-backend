<?php

namespace App\Services\Forms;


use App\Category;
use Illuminate\Support\Facades\DB;

class CategoryService {

    public function makeCategory($params) {
        return Category::create($params);
    }

    public function updateCategory(Category $category, $params) {
        $parent = Category::find($params['parent_id']);

        if ($category->getForm()->getKey() !== $parent->getForm()->getKey()) {
            return false;
        }
        return true;
    }

    public function deleteCategory(Category $category) {
        $parent_id = $category->parent_id;
        if ($parent_id === null) {
            return false;
        }
        DB::transaction(function() use (&$category, &$parent_id) {
            foreach ($category->children()->get() as $child) {
                $child->parent_id = $parent_id;
                $child->save();
            }
            $category->delete();
        });
    }


}
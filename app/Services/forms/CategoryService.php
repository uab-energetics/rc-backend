<?php

namespace App\Services\Forms;


use App\Category;

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


}
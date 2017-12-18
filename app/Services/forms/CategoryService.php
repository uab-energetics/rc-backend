<?php

namespace App\Services\Forms;


use App\Category;
use App\FormQuestion;
use Illuminate\Support\Facades\DB;

class CategoryService {

    public function makeCategory($params) {
        return Category::create($params);
    }

    public function updateCategory(Category $category, $params) {
        $parent = Category::find($params['parent_id']);

        if ($category->getForm()->getKey() !== $parent->getForm()->getKey()
            || $category->getKey() === $parent->getKey()) {
            return false;
        }

        $category->update($params);

        return true;
    }

    public function deleteCategory(Category $category) {
        $parent_id = $category->parent_id;
        if ($parent_id === null) {
            return false;
        }
        DB::beginTransaction();
            foreach ($category->children()->get() as $child) {
                $child->parent_id = $parent_id;
                $child->save();
            }
            $questionEdges = FormQuestion::query()
                ->where('category_id', '=', $category->getKey())
                ->get();
            foreach ($questionEdges as $questionEdge) {
                $questionEdge->category_id = $parent_id;
                $questionEdge->save();
            }
            $category->delete();
        DB::commit();
    }


}
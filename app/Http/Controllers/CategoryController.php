<?php

namespace App\Http\Controllers;

use App\Category;
use App\Form;
use App\Services\Forms\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {

    public function create(Request $request, CategoryService $categoryService) {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'string',
            'parent_id' => 'required|int|exists:categories,id',
        ]);

        $category = null;
        DB::transaction(function () use (&$category, &$request, &$categoryService) {
            $category = $categoryService->makeCategory($request->all());
        });

        return $category;
    }

    public function retrieve(Category $category) {
        return $category;
    }

    public function update(Category $category, Request $request, CategoryService $categoryService) {
        $request->validate([
            'name' => 'string|max:255',
            'description' => 'string',
            'parent_id' => 'exists:categories,id',
        ]);

        if ($category->parent_id === null) {
            return response()->json(static::INVALID_CATEGORY, 403);
        }

        $res = null;
        DB::transaction(function () use (&$category, &$request, &$categoryService, &$res) {
            $res = $categoryService->updateCategory($category, $request->all());
        });

        if ($res === false) {
            return response()->json(static::INVALID_PARENT, 400);
        }

        return $category->refresh();
    }

    public function updateOnForm(Form $form, Category $category, Request $request, CategoryService $categoryService) {
        return $this->update($category, $request, $categoryService);
    }

    public function delete(Form $form, Category $category, CategoryService $categoryService) {
        $res = $categoryService->deleteCategory($category);
        if ($res === false) {
            return response()->json([
                'status' => 'INVALID_CATEGORY',
                'msg' => "Root categories may not be deleted"
            ], 403);
        }

        $form->refresh();
        return $form;
    }


    const INVALID_PARENT = [
        'status' => 'INVALID_PARENT',
        'msg' => "The new parent is on a different form"
    ];

    const INVALID_CATEGORY = [
        'status' => 'INVALID_CATEGORY',
        'msg' => "Root categories may not have parents"
    ];
}

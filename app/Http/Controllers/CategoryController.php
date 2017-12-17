<?php

namespace App\Http\Controllers;

use App\Category;
use App\Services\Forms\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller {

    public function create(Request $request, CategoryService $categoryService) {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return invalidParamMessage($validator);
        }

        $category = null;
        DB::transaction(function () use (&$category, &$request, &$categoryService) {
            $category = $categoryService->makeCategory($request->all());
        });

        return $category->toArray();
    }

    public function update(Category $category, Request $request, CategoryService $categoryService) {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return invalidParamMessage($validator);
        }

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

        return okMessage("Successfully updated category");
    }


    /**
     * @param $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator($data) {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'parent_id' => 'required|int|exists:categories,id',
        ]);
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

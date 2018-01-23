<?php namespace App\Http\Controllers;

use app\Repositories\CategoryRepository;

class CategoriesController extends Controller
{
    /**
     * @param CategoryRepository $categoryRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCategories(CategoryRepository $categoryRepository)
    {
        if (!count($category = $categoryRepository->getModel()->orderBy('title')->get())) {
            return response()->json([], 204);
        }
        return response()->json($category);
    }
}

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
        $category = $categoryRepository->findAllOrderBy('title');
        if ($category->isEmpty()) {
            return response()->json([], 204);
        }
        return response()->json($category);
    }
}

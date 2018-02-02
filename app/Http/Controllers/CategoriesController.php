<?php namespace App\Http\Controllers;

use app\Repositories\CategoryInterface;

class CategoriesController extends Controller
{
    /**
     * @param CategoryInterface $categoryRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCategories(CategoryInterface $categoryRepository)
    {
        $categories = $categoryRepository->findAllOrderBy("title = 'Другое'", 'title');

        if ($categories->isEmpty()) {
            return response()->json([], 204);
        }
        return response()->json($categories);
    }
}

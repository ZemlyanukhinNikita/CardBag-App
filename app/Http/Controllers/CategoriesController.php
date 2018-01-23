<?php namespace App\Http\Controllers;

use app\Repositories\CategoryInterface;

class CategoriesController extends Controller
{
    /**
     * @param CategoryInterface $categoryInterface
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllCategories(CategoryInterface $categoryInterface)
    {
        $category = $categoryInterface->findAllOrderBy('title');
        if ($category->isEmpty()) {
            return response()->json([], 204);
        }
        return response()->json($category);
    }
}

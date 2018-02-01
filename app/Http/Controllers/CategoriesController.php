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
        $otherCategory = [
            'id' => 16,
            'title' => 'Другое'
        ];
        $categories = $categoryRepository->findAllOrderBy('title')->except($otherCategory)->push($otherCategory);

        if ($categories->isEmpty()) {
            return response()->json([], 204);
        }
        return response()->json($categories);
    }
}

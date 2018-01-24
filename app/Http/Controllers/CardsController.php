<?php namespace App\Http\Controllers;

use app\Repositories\CardInterface;
use app\Repositories\CategoryInterface;
use app\Repositories\UserInterface;
use App\Service;
use App\Service\CardService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CardsController extends Controller
{
    /**
     * @param Request $request
     * @param CardService $cardService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUserCards(Request $request, CardService $cardService)
    {
        /** @var Collection $cards */
        $cards = $cardService->getUserCards($request->header('uuid'));
        if ($cards->isEmpty()) {
            return response()->json([], 204);
        }
        return response()->json($cards->load('category')->makeHidden([
            'user_id',
            'category_id',
            'created_at',
            'updated_at'
        ]));
    }

    /**
     * Метод валидации полей
     * @param Request $request
     * @return mixed
     */
    public function validator(Request $request)
    {
        $messages = [
            'title.required' => "Не заполнено поле 'Название карты'",
            'discount.integer' => 'Введите числовое значение',
            'discount.min' => 'Значение скидки должно быть от :min до 100',
            'discount.max' => 'Значение скидки должно быть от 0 до :max',
            'front_photo.required' => 'Загрузите лицевое фото карты',
            'back_photo.required' => 'Загрузите фото обратной стороны карты',
        ];

        return Validator::make($request->all(), [
            'title' => 'required|max:40',
            'category_id' => 'required',
            'front_photo' => 'required',
            'back_photo' => 'required',
            'discount' => 'required|integer:discount|min:0|max:100',
        ], $messages);
    }

    /**
     * Метод добавления карты
     * @param Request $request
     * @param UserInterface $userRepository
     * @param CardInterface $cardRepository
     * @param CategoryInterface $categoryRepository
     */
    public function addCard(
        Request $request,
        UserInterface $userRepository,
        CardInterface $cardRepository,
        CategoryInterface $categoryRepository
    ) {
        $validator = $this->validator($request);
        if ($validator->fails()) {
            abort(400, $validator->errors()->first());
        }

        $user = $userRepository->findOneBy('uuid', $request->header('uuid'));
        if ($user === null) {
            abort(401, 'Unauthorized');
        }

        $category = $categoryRepository->findAllBy('id', $request->input('category_id'));
        if ($category->isEmpty()) {
            abort(404);
        }

        $cardRepository->create(array(
            'user_id' => $user->id,
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'front_photo' => $request->input('front_photo'),
            'back_photo' => $request->input('back_photo'),
            'discount' => $request->input('discount'),
        ));
    }
}

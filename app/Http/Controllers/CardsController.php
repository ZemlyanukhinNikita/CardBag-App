<?php namespace App\Http\Controllers;

use app\Repositories\CardInterface;
use app\Repositories\UserInterface;
use App\Service;
use App\Service\CardService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

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
            'category_id.exists' => 'Такой категории в базе данных нет'
        ];

        $this->validate($request, [
            'title' => 'required|max:40',
            'category_id' => 'exists:categories,id',
            'front_photo' => 'required|url',
            'back_photo' => 'required|url',
            'discount' => 'required|integer:discount|min:0|max:100',
        ], $messages);
    }

    /**
     * Метод добавления карты
     * @param Request $request
     * @param UserInterface $userRepository
     * @param CardInterface $cardRepository
     */
    public function addCard(
        Request $request,
        UserInterface $userRepository,
        CardInterface $cardRepository
    ) {
        $this->validator($request);

        $user = $userRepository->findOneBy('uuid', $request->header('uuid'));
        if ($user === null) {
            abort(401, 'Unauthorized');
        }

        $cardRepository->create([
            'user_id' => $user->id,
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'front_photo' => $request->input('front_photo'),
            'back_photo' => $request->input('back_photo'),
            'discount' => $request->input('discount'),
        ]);
    }
}

<?php namespace App\Http\Controllers;

use app\Repositories\CardInterface;
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
            'category_id'
        ]));
    }

    /**
     * Метод валидации полей
     * @param Request $request
     */
    private function validateCardFields(Request $request)
    {
        $messages = [
            'title.required' => 'Введите название карты',
            'discount.integer' => 'Введите числовое значение',
            'discount.min' => 'Введите размер скидки от :min до 100',
            'discount.max' => 'Введите размер скидки от 0 до :max',
            'front_photo.required' => 'Загрузите лицевое фото карты',
            'back_photo.required' => 'Загрузите фото обратной стороны карты',
            'category_id.exists' => 'Такой категории в базе данных нет'
        ];

        $this->validate($request, [
            'title' => 'required|max:40',
            'category_id' => 'exists:categories,id',
            'front_photo' => 'required|url',
            'back_photo' => 'required|url',
            'discount' => 'integer:discount|min:0|max:100',
            'uuid' => 'unique:cards'
        ], $messages);
    }

    /**
     * Метод добавления карты
     * @param Request $request
     * @param CardInterface $cardRepository
     */
    public function addCard(
        Request $request,
        CardInterface $cardRepository
    )
    {
        $this->validateCardFields($request);

        if (!(file_exists('storage/' . $request->input($request->input('front_photo'))) &&
            file_exists('storage/' . $request->input($request->input('back_photo'))))
        ) {
            abort(400, 'photo not found on server');
        }

        $cardRepository->create([
            'user_id' => $request->user()->id,
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'front_photo' => $request->input('front_photo'),
            'back_photo' => $request->input('back_photo'),
            'discount' => $request->input('discount'),
            'uuid' => $request->input('uuid')
        ]);
    }

    /**
     * Метод удаления карты
     * @param $uuid
     * @param Request $request
     * @param CardInterface $cardRepository
     */
    public function deleteCard(Request $request, $uuid, CardInterface $cardRepository)
    {
        $this->checkingValidityUuidCard($uuid);

        if ($cardRepository->findAllBy('uuid', (string)$uuid)->isEmpty()) {
            abort(400, 'uuid not found in database');
        }

        $this->checkPermissionUser($request, $cardRepository, $uuid);

        $this->removingPhotosFromServer(basename($cardRepository->findOneBy('uuid', $uuid)->front_photo),
            basename($cardRepository->findOneBy('uuid', $uuid)->back_photo));

        $cardRepository->delete('uuid', $uuid);
    }

    /**
     * Удаление фото с сервера
     * @param $frontPhoto
     * @param $backPhoto
     */
    public function removingPhotosFromServer($frontPhoto, $backPhoto)
    {
        if (!(file_exists('storage/' . $frontPhoto) && file_exists('storage/' . $backPhoto))) {
            abort(400, 'photo not found');
        }
        unlink('storage/' . $frontPhoto);
        unlink('storage/' . $backPhoto);
    }

    /**
     * Проверка валидности uuid карты
     * @param $uuid
     */
    public function checkingValidityUuidCard($uuid)
    {
        if (!preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $uuid)
        ) {
            abort(422, 'Invalid uuid');
        }
    }

    /**
     * Проверка того, что карточка принадлежит пользователю
     * @param Request $request
     * @param CardInterface $cardRepository
     * @param $uuid
     */
    public function checkPermissionUser(Request $request, CardInterface $cardRepository, $uuid)
    {
        if ($cardRepository->findOneBy('uuid', $uuid)->user_id !== $request->user()->id) {
            abort(403, 'Permission denied');
        }
    }
}

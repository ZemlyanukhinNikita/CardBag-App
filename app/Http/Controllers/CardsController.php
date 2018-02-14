<?php namespace App\Http\Controllers;

use app\Repositories\CardInterface;
use app\Repositories\PhotoInterface;
use App\Service\CardService;
use App\Service\PhotoService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    /**
     * @param Request $request
     * @param CardService $cardService
     * @param CardInterface $cardRepository
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
            'category_id.exists' => 'Такой категории в базе данных нет',
        ];

        $this->validate($request, [
            'title' => 'required|max:40',
            'category_id' => 'nullable|exists:categories,id',
            'front_photo' => 'required|url',
            'back_photo' => 'required|url',
            'discount' => 'integer:discount|min:0|max:100',
            'uuid' => 'required',
            'updated_at' => 'date'
        ], $messages);
    }

    /**
     * Метод добавления карты
     * @param Request $request
     * @param CardInterface $cardRepository
     * @param PhotoService $photoService
     */
    public function addCard(
        Request $request,
        CardInterface $cardRepository,
        PhotoService $photoService)
    {
        $this->validateCardFields($request);

        $this->checkingValidityUuidCard($request->input('uuid'));

        $this->isExistUuidInDataBase($request->input('uuid'), $cardRepository);

        if ($request->input('front_photo') === $request->input('back_photo')) {
            abort(400, 'Url photos can not be the same');
        }

        $frontPhoto = $photoService->checkingSendPhotoOnServer($request->input('front_photo'));
        $backPhoto = $photoService->checkingSendPhotoOnServer($request->input('back_photo'));

        $photoService->checkingUserPermission($request->input('front_photo'));
        $photoService->checkingUserPermission($request->input('back_photo'));

        $cardRepository->create([
            'user_id' => $request->user()->id,
            'title' => $request->input('title'),
            'category_id' => $this->replacingEmptyStringWithNull($request->input('category_id')),
            'front_photo' => $frontPhoto->id,
            'back_photo' => $backPhoto->id,
            'discount' => $this->replacingEmptyStringWithNull($request->input('discount')),
            'uuid' => $request->input('uuid')
        ]);
    }

    /**
     * Метод удаления карты
     * @param $uuid
     * @param CardInterface $cardRepository
     * @param PhotoService $photoService
     * @param PhotoInterface $photoRepository
     */
    public function deleteCard($uuid, CardInterface $cardRepository, PhotoService $photoService, PhotoInterface $photoRepository)
    {
        $this->checkingValidityUuidCard($uuid);

        $card = $cardRepository->findOneBy('uuid', (string)$uuid);

        if (!$card) {
            abort(400, 'card`s UUID not found in database');
        }

        $photoService->checkingUserPermission($photoRepository->findOneBy('id', $card->front_photo)->filename);
        $photoService->checkingUserPermission($photoRepository->findOneBy('id', $card->back_photo)->filename);

        $cardRepository->delete('uuid', $uuid);
    }

    /**
     * Метод редактирования карты
     * @param Request $request
     * @param $uuid
     * @param CardInterface $cardRepository
     * @param PhotoService $photoService
     */
    public function updateCard(Request $request, $uuid, CardInterface $cardRepository, PhotoService $photoService)
    {
        $this->checkingValidityUuidCard($uuid);

        $this->validateCardFields($request);

        $card = $cardRepository->findOneBy('uuid', (string)$uuid);
        if (!$card) {
            abort(400, 'card`s UUID not found in database');
        }

        if ($request->input('front_photo') === $request->input('back_photo')) {
            abort(400, 'Url photos can not be the same');
        }

        $frontPhoto = $photoService->checkingSendPhotoOnServer($request->input('front_photo'));
        $backPhoto = $photoService->checkingSendPhotoOnServer($request->input('back_photo'));

        $photoService->checkingUserPermission($request->input('front_photo'));
        $photoService->checkingUserPermission($request->input('back_photo'));

        $cardRepository->update('uuid', $uuid,
            [
                'title' => $request->input('title'),
                'front_photo' => $frontPhoto->id,
                'back_photo' => $backPhoto->id,
                'category_id' => $request->input('category_id'),
                'discount' => $this->replacingEmptyStringWithNull($request->input('discount')),
                'updated_at' => $request->input('updated_at'),
            ]);

        if ($frontPhoto->filename !== basename($request->input('front_photo'))) {
            $backPhoto->removingPhotoFromServer($frontPhoto->filename);
        }

        if ($backPhoto->filename !== basename($request->input('back_photo'))) {
            $photoService->removingPhotoFromServer($backPhoto->filename);
        }
    }

    /**
     * Проверка валидности uuid карты
     * @param $uuid
     */
    private
    function checkingValidityUuidCard($uuid)
    {
        if (!preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $uuid)
        ) {
            abort(422, 'Invalid card`s UUID supplied');
        }
    }

    /**
     * @param $uuid
     * @param CardInterface $cardRepository
     */
    private
    function isExistUuidInDataBase($uuid, CardInterface $cardRepository)
    {
        if ($cardRepository->findOneBy('uuid', $uuid)) {
            abort(400, 'Uuid must be unique');
        }
    }

    private function replacingEmptyStringWithNull($value)
    {
        if ($value === '') {
            return null;
        }
        return $value;
    }
}
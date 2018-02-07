<?php namespace App\Http\Controllers;

use app\Repositories\CardInterface;
use App\Service;
use App\Service\CardService;
use App\Service\PhotoService;
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
     * @param PhotoService $photoService
     */
    public function addCard(
        Request $request,
        CardInterface $cardRepository,
        PhotoService $photoService
    )
    {
        $this->validateCardFields($request);

        $photoService->checkingSendPhotos($request->input('front_photo'), $request->input('back_photo'));

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
     * @param CardInterface $cardRepository
     * @param PhotoService $photoService
     */
    public function deleteCard($uuid, CardInterface $cardRepository, PhotoService $photoService)
    {
        $this->checkingValidityUuidCard($uuid);

        if ($cardRepository->findAllBy('uuid', (string)$uuid)->isEmpty()) {
            abort(400, 'uuid not found in database');
        }

        $photoService->removingPhotosFromServer(basename($cardRepository->findOneBy('uuid', $uuid)->front_photo));

        $photoService->removingPhotosFromServer(basename($cardRepository->findOneBy('uuid', $uuid)->back_photo));

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

        if ($cardRepository->findAllBy('uuid', (string)$uuid)->isEmpty()) {
            abort(400, 'uuid not found in database');
        }
        $photoService->checkingSendPhotos($request->input('front_photo'), $request->input('back_photo'));

        $frontPhoto = $cardRepository->findOneBy('uuid', $uuid)->front_photo;
        $backPhoto = $cardRepository->findOneBy('uuid', $uuid)->back_photo;

        if ($frontPhoto !== $request->input('front_photo')) {
            $photoService->removingPhotosFromServer(basename($frontPhoto));
        }

        if ($backPhoto !== $request->input('back_photo')) {
            $photoService->removingPhotosFromServer(basename($backPhoto));
        }

        $cardRepository->update('uuid', $uuid,
            [
                'title' => $request->input('title'),
                'front_photo' => $request->input('front_photo'),
                'back_photo' => $request->input('back_photo'),
                'category_id' => $request->input('category_id'),
                'discount' => $request->input('discount'),
                'updated_at' => $request->input('updated_at'),
            ]);
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
}


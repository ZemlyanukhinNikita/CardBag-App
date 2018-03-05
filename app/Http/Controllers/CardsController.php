<?php namespace App\Http\Controllers;

use app\Repositories\CardInterface;
use app\Repositories\PhotoInterface;
use app\Service\BarcodeService;
use App\Service\CardService;
use App\Service\PhotoService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    /**
     * @param CardService $cardService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUserCards(CardService $cardService)
    {
        /** @var Collection $cards */
        $cards = $cardService->getUserCards();
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
     * @param BarcodeService $barcodeService
     */
    public function addCard(
        Request $request,
        CardInterface $cardRepository,
        PhotoService $photoService,
        BarcodeService $barcodeService
    )
    {
        $this->validateCardFields($request);

        $this->checkingValidityUuidCard($request->input('uuid'));

        $this->isExistValueInDataBase('uuid', $request->input('uuid'), $cardRepository, 'Uuid must be unique');

        if ($request->input('front_photo') === $request->input('back_photo')) {
            abort(400, 'Url photos can not be the same');
        }

        $frontPhoto = $photoService->checkingSendPhotoOnServer($request->input('front_photo'));
        $backPhoto = $photoService->checkingSendPhotoOnServer($request->input('back_photo'));
        $this->isExistValueInDataBase('front_photo', $frontPhoto->id, $cardRepository, 'Photo must be unique');
        $this->isExistValueInDataBase('back_photo', $backPhoto->id, $cardRepository, 'Photo must be unique');

        $photoService->checkingUserPermission($request->input('front_photo'));
        $photoService->checkingUserPermission($request->input('back_photo'));

        $barcodeImageName = $barcodeService->getImageUrlOrNull($backPhoto->filename);
        if ($barcodeImageName === null) {
            $barcodeImageName = $barcodeService->getImageUrlOrNull($frontPhoto->filename);
        }

        $barcodePhoto = $photoService->checkingSendPhotoOnServer($barcodeImageName);

        $barcode = $barcodeService->scanBarCode($backPhoto->filename);
        if ($barcode === null) {
            $barcode = $barcodeService->scanBarCode($frontPhoto->filename);
        }


        $cardRepository->create([
            'user_id' => $request->user()->id,
            'title' => $request->input('title'),
            'category_id' => $this->replacingEmptyStringWithNull($request->input('category_id')),
            'front_photo' => $frontPhoto->id,
            'back_photo' => $backPhoto->id,
            'discount' => $this->replacingEmptyStringWithNull($request->input('discount')),
            'uuid' => $request->input('uuid'),
            'updated_at' => $request->input('updated_at'),
            'barcode_photo' => $barcodePhoto->id,
            'barcode' => $barcode
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

        $frontPhoto = $photoRepository->findOneBy('id', $card->front_photo);
        $backPhoto = $photoRepository->findOneBy('id', $card->back_photo);

        $photoService->checkingUserPermission($frontPhoto->filename);
        $photoService->checkingUserPermission($backPhoto->filename);

        $cardRepository->delete('uuid', $uuid);

        $photoRepository->update('filename', $frontPhoto->filename, ['deleted_at' => date(DATE_ATOM)]);
        $photoRepository->update('filename', $backPhoto->filename, ['deleted_at' => date(DATE_ATOM)]);

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
    private function checkingValidityUuidCard($uuid)
    {
        if (!preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i',
            $uuid)
        ) {
            abort(422, 'Invalid card`s UUID supplied');
        }
    }

    /**
     * @param $field
     * @param $value
     * @param CardInterface $cardRepository
     * @param $message
     * @internal param $uuid
     */
    private function isExistValueInDataBase($field, $value, CardInterface $cardRepository, $message)
    {
        if ($cardRepository->findOneByWithTrashedBy($field, $value)) {
            abort(400, $message);
        }
        return $value;
    }

    private function replacingEmptyStringWithNull($value)
    {
        if ($value === '') {
            return null;
        }
        return $value;
    }
}
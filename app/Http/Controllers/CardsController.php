<?php namespace App\Http\Controllers;

use app\Repositories\CardInterface;
use App\Service;
use app\Service\BarCodeService;
use App\Service\CardService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
     * @param BarCodeService $barCodeService
     * @throws \Exception
     */
    public function addCard(
        Request $request,
        CardInterface $cardRepository,
        BarCodeService $barCodeService
    ) {
        $this->validateCardFields($request);

        if (!preg_match(('/(https?:\/\/.*\.(?:png|jpg|gif|bmp|svg|jpeg))/i'), $request->input('front_photo'))) {
            abort(422, 'Not valid url image');
        }

        $format = explode('/', $request->input('back_photo'));
        $fileName = end($format);

        $barCode = $barCodeService->scanBarCode($fileName);

        if ($barCode->code === 400) {
            abort(422, 'No barcode detected');
        }

        $png = $barCodeService->generateBarCodeImage($barCode->text, 'C128');
        $cardRepository->create([
            'user_id' => $request->user()->id,
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'front_photo' => $request->input('front_photo'),
            'back_photo' => $request->input('back_photo'),
            'discount' => $request->input('discount'),
            'uuid' => $request->input('uuid'),
            'bar_code' => Storage::url($png)
        ]);
    }

    /**
     * Метод удаления карты
     * @param $id
     * @param CardInterface $cardRepository
     */
    public function deleteCard($id, CardInterface $cardRepository)
    {
        if (!preg_match('/^\d+$/', $id)) {
            abort(422, 'Invalid ID supplied');
        }

        if ($cardRepository->findAllBy('id', (int)$id)->isEmpty()) {
            abort(400, 'ID not found in database');
        }

        $cardRepository->delete('id', $id);
    }
}

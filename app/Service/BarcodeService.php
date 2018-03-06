<?php


namespace app\Service;

use app\Repositories\PhotoInterface;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Milon\Barcode\DNS1D;
use RobbieP\ZbarQrdecoder\ZbarDecoder;


class BarcodeService
{
    private $decoder;
    private $barCode;
    private $request;
    private $photoRepository;


    /**
     * BarCodeService constructor.
     * @param Request $request
     * @param ZbarDecoder $decoder
     * @param DNS1D $barCode
     * @param PhotoInterface $photoRepository
     */
    public function __construct(Request $request, ZbarDecoder $decoder, DNS1D $barCode, PhotoInterface $photoRepository)
    {
        $this->decoder = $decoder;
        $this->barCode = $barCode;
        $this->photoRepository = $photoRepository;
        $this->request = $request;
    }

    /**
     * Метод сохраняет штрихкод фото в базу данных
     * @param string $photo url фотографии
     */
    private function saveBarcodeImageInDB(string $filename)
    {
        $this->photoRepository->create([
            'user_id' => $this->request->user()->id,
            'filename' => $filename
        ]);
    }

    /**
     * Метод сканирования фотографии
     * @param $fileName
     * @return \RobbieP\ZbarQrdecoder\Result\Result
     */
    private function scanBarCode(string $fileName)
    {
        try {
            return $this->decoder->make('storage/' . $fileName);
        } catch (Exception $e) {
            Log::error('Scanning image error: ' . $e->getMessage() . ' ' . $e->getCode());
        }
    }

    /**
     * Метод генерации изображения штрихкода
     * @param $code
     * @param $type
     * @return mixed
     */
    private function generateBarCodeImage($code, $type)
    {
        try {
            return $this->barCode->getBarcodePNGPath($code, $this->setBarcode($type));
        } catch (Exception $e) {
            Log::error('Generating image error: ' . $e->getMessage() . ' ' . $e->getCode());
        }
    }

    /**
     * Метод возвращает url картинки если она распозанана,
     * иначе null
     * @param $photo
     * @return $filename|null
     */
    public function getImageUrl($photo)
    {
        $scanImage = $this->scanBarCode($photo);
        if ($scanImage->code !== 400) {
            $filename = $this->generateBarCodeImage($scanImage->text, $scanImage->format);
            $this->saveBarcodeImageInDB($filename);
            return $filename;
        }
        return null;
    }

    /**
     * Для создания картинки штрихкода нужно передать тип штрихкода, считанный с фотографии карточки,
     * считанный тип и тип штрихкода нужный для создания картинки отличаются, т.к используется два разных сервиса.
     * Метод устанавливает нужный тип штрихкода для генерации картинок
     * @param $format
     * @return false|string
     */
    private function setBarcode($format)
    {
        switch ($format) {
            case 'CODE_39': {
                return 'C39';
            }
            case 'INTERLEAVED_2_5': {
                return 'I25';
            }
            case 'CODE_128': {
                return 'C128';
            }
            case 'EAN_13': {
                return 'EAN13';
            }
            case 'QR_CODE': {
                return 'QRCODE';
            }
            default: {
                return false;
            }
        }
    }

}
<?php

namespace app\Service;

use Milon\Barcode\DNS1D;
use RobbieP\ZbarQrdecoder\ZbarDecoder;

class BarCodeService
{
    private $decoder;
    private $barCode;
    private $format;

    /**
     * BarCodeService constructor.
     * @param ZbarDecoder $decoder
     * @param DNS1D $barCode
     */
    public function __construct(ZbarDecoder $decoder, DNS1D $barCode)
    {
        $this->decoder = $decoder;
        $this->barCode = $barCode;
    }

    /**
     * @param $fileName
     * @return mixed
     * @throws \Exception
     */
    public function scanBarCode($fileName)
    {
        return $this->decoder->make('storage/' . $fileName);
    }

    /**
     * @param $code
     * @param $type
     * @return \Milon\Barcode\path
     */
    public function generateBarCodeImage($code, $type)
    {
        return $this->barCode->getBarcodePNGPath($code, $this->setBarcode($type));
    }

    /**
     * Для создания картинки штрихкода нужно передать тип штрихкода, считанный с фотографии карточки,
     * считанный тип и тип штрихкода нужный для создания картинки отличаются, т.к используется два разных сервиса.
     * Метод устанавливает нужный тип штрихкода для генерации картинок
     * @param $format
     * @return bool|string
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
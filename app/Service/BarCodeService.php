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
        return $this->barCode->getBarcodePNGPath($code, $type);
    }

    public function setBarcode($format)
    {
        switch ($format) {
            case 'CODE_39': {
                return $this->format = 'C39';
            }
            case 'INTERLEAVED_2_5': {
                return $this->format = 'I25';
            }
            case 'CODE_128': {
                return $this->format = 'C128';
            }
            case 'EAN_13': {
                return $this->format = 'EAN13';
            }
            case 'QR_CODE': {
                return $this->format = 'QRCODE';
            }
            default: {
                return $this->format = false;
            }
        }
    }
}
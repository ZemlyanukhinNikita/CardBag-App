<?php

namespace app\Service;


use Milon\Barcode\DNS1D;
use RobbieP\ZbarQrdecoder\ZbarDecoder;

class BarCodeService
{
    private $decoder;
    private $barCode;


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
}
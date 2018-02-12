<?php


namespace tests;


use app\Service\BarCodeService;
use Mockery;
use TestCase;

class BarCodeServiceTest extends TestCase
{

    public function testScanBarCode()
    {
        $fileName = 'test.png';
        $m = Mockery::mock(BarCodeService::class);
        $m->shouldReceive('scanBarCode')->with(Mockery::type('string'))->once();
        $m->scanBarCode($fileName);
    }

    public function testGenerateBarCodeImage()
    {
        $code = '111111';
        $type = 'C128';
        $m = Mockery::mock(BarCodeService::class);
        $m->shouldReceive('generateBarCodeImage')->with(Mockery::type('string'), Mockery::type('string'))->once();
        $m->generateBarCodeImage($code, $type);
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }
}

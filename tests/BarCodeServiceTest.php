<?php


namespace tests;


use app\Service\BarCodeService;
use Mockery;
use TestCase;

class BarCodeServiceTest extends TestCase
{
    private $mock;

    public function setUp()
    {
        parent::setUp();
        $this->mock = Mockery::mock(BarCodeService::class);
    }

    public function testScanBarCode()
    {
        $fileName = 'test.png';
        $this->mock->shouldReceive('scanBarCode')->with(Mockery::type('string'))->once();
        $this->mock->scanBarCode($fileName);
    }

    public function testGenerateBarCodeImage()
    {
        $code = '111111';
        $type = 'C128';
        $this->mock->shouldReceive('generateBarCodeImage')->with(Mockery::type('string'),
            Mockery::type('string'))->once();
        $this->mock->generateBarCodeImage($code, $type);
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }
}

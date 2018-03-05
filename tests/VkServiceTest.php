<?php

use App\Service\VkService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class VkServiceTest extends TestCase
{
    public function testSuccessfulResponse()
    {
        $token = "_7dzHuJq4UzsUFTOUOojiJwtoIKKa8umnseYuFXU2ODMCyi8kf3TZWVrxy9dQAzltKQZW6Lv";

        $mock = new MockHandler([
            new Response(200, ["content-type" => 'application/json'], json_encode(["response" => [['uid' => '210700286', 'first_name' => 'Роман', 'last_name' => 'Максимов']]])),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $service = new VkService($client);

        $userProfile = $service->checkUserTokenInSocialNetwork($token, '210700286');

        $this->assertEquals('Роман Максимов', $userProfile->getFullName());
        $this->assertEquals($token, $userProfile->getToken());
        $this->assertEquals('210700286', $userProfile->getUid());
    }

    public function testInvalidData()
    {
        $token = "token";

        $mock = new MockHandler([
            new Response(200, ["content-type" => 'application/json'], json_encode(["response" => [['uid' => '210700286', 'first_name' => 'Роман', 'last_name' => 'Максимов']]])),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $service = new VkService($client);

        $userProfile = $service->checkUserTokenInSocialNetwork($token, '1234');

        $this->assertEquals($userProfile, false);
    }
}

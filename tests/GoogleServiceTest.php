<?php

use App\Service\GoogleService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class GoogleServiceTest extends TestCase
{
    public function testGoogleService()
    {
        $token = "ya29.GltxBXq1wUjk8_sxLPQ6s20Ruks7xs1nBpWKjIx84BxDeiSXmJ84_7dzHuJq4UzsUFTOUOojiJwtoIKKa8umnseYuFXU2ODMCyi8kf3TZWVrxy9dQAzltKQZW6Lv";

        $mock = new MockHandler([
            new Response(200, ["content-type" => 'application/json'], json_encode(['name' => 'Роман Максимов', 'sub' => '407408718192'])),
        ]);

        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $service = new GoogleService($client);

        $userProfile = $service->checkUserTokenInSocialNetwork($token, '407408718192');

        $this->assertEquals('Роман Максимов', $userProfile->getFullName());
        $this->assertEquals($token, $userProfile->getToken());
        $this->assertEquals('407408718192', $userProfile->getUid());
    }
}

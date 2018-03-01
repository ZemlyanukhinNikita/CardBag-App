<?php

namespace tests;


use app\Repositories\TokenRepository;
use app\Repositories\UserRepository;
use App\Service\AuthorizeService;
use App\Service\SocialNetworkFactory;
use App\Token;
use App\User;
use Illuminate\Http\Request;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Mockery;
use TestCase;
use UserProfile;

class AuthorizeServiceTest extends TestCase
{
    use DatabaseTransactions;
    private $request;
    private $userRepository;
    private $tokenRepository;
    private $factory;
    private $authorizeService;
    private $userProfile;

    public function setUp()
    {
        $this->request = new Request();
        $this->userRepository = new UserRepository();
        $this->tokenRepository = new TokenRepository();
        $this->factory = Mockery::mock(SocialNetworkFactory::class);
        $this->authorizeService = new AuthorizeService($this->request, $this->userRepository, $this->tokenRepository,
            $this->factory);
        parent::setUp();
    }

    public function testRefreshUserToken()
    {
        $tokenModel = factory(Token::class)->create();
        $tokenModel->token = 'testToken';
        $fullName = 'Test Name';
        $this->userProfile = new UserProfile($fullName, $tokenModel->token, $tokenModel->uid);
        $this->authorizeService->refreshUserToken($this->userProfile);
        $this->seeInDatabase('tokens', ['token' => $tokenModel->token]);
    }

    public function testRegisterNewUser()
    {
        $user = factory(User::class)->create();
        $tokenModel = factory(Token::class)->create();
        $this->userProfile = new UserProfile($user->full_name, $tokenModel->token, $tokenModel->uid);
        $this->authorizeService->registerNewUser($this->userProfile);
        $this->seeInDatabase('users', ['full_name' => $user->full_name]);
        $this->seeInDatabase('tokens', ['token' => $tokenModel->token]);
    }

    public function tearDown()
    {
        parent::tearDown();
        Mockery::close();
    }

}

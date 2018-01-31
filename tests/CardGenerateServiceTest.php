<?php

use app\Repositories\CardRepository;
use App\Service\CardGenerateService;
use App\User;
use Laravel\Lumen\Testing\DatabaseTransactions;

/**
 * Class CardGenerateServiceTest
 * Тест генерации карточек
 */
class CardGenerateServiceTest extends TestCase
{
    use DatabaseTransactions;

    private $cardService;
    private $cardRepository;

    public function setUp()
    {
        parent::setUp();

        $this->cardService = new CardGenerateService();
        $this->cardRepository = new CardRepository();
    }


    public function test()
    {
        $user = factory(User::class)->create();

        $this->cardService->generateUserCards($user);

        $cards = $this->cardRepository->findAllBy('user_id', $user->id);

        if (count($cards) > 0 && count($cards) <= 10) {
            return $this->assertTrue(true);
        }
        $this->assertTrue(false);
    }
}

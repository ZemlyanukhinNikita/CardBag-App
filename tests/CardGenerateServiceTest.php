<?php

use App\Card;
use App\Service\CardGenerateService;
use Illuminate\Support\Facades\DB;

class CardGenerateServiceTest extends TestCase
{
    public function test()
    {
        $card = ['title' => 'title',
            'user_id' => 1,
            'category_id' => 1,
            'front_photo' => 'http://yandex',
            'back_photo' => 'http://yandex',
            'discount' => 55];

        $stub = $this->createMock(CardGenerateService::class);

        $stub->method('generateUserCards')
            ->willReturn(factory(Card::class, 1)->create($card));

        $this->seeInDatabase('cards', $card);

        DB::table('cards')->where($card)->delete();
    }
}

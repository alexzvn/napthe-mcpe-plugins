<?php

namespace Alexzvn\MoneyBooster\Drivers\Cardvip;

use Alexzvn\MoneyBooster\Contracts\BoosterCallbackContract;
use Alexzvn\MoneyBooster\Drivers\Driver;
use Alexzvn\MoneyBooster\Contracts\CardContract;
use Alexzvn\MoneyBooster\Contracts\BoosterResponseContract;
use pocketmine\Player;
use sekjun9878\RequestParser\Request;

class CardvipDriver extends Driver
{
    protected static array $cards = [
        \Alexzvn\MoneyBooster\Card\Viettel::class,
        \Alexzvn\MoneyBooster\Card\Mobifone::class,
        \Alexzvn\MoneyBooster\Card\Vinaphone::class,
        \Alexzvn\MoneyBooster\Card\Garena::class,
        \Alexzvn\MoneyBooster\Card\Zing::class,
        \Alexzvn\MoneyBooster\Card\Gate::class,
        \Alexzvn\MoneyBooster\Card\Vcoin::class,
    ];

    protected function api(): string
    {
        return 'https://partner.cardvip.vn/';
    }

    public function request(CardContract $card, Player $player): BoosterResponseContract
    {
        $response = $this->client->post('api/createExchange', [
            'APIKey'         => $this->secret,
            'NetworkCode'    => $card->telecom(),
            'PricesExchange' => $card->amount(),
            'NumberCard'     => $card->code(),
            'SeriCard'       => $card->seri(),
            'RequestId'      => $player->getLowerCaseName()
        ]);

        return new CardvipResponse($response);
    }

    public static function callback(Request $request): BoosterCallbackContract
    {
        return new CardvipCallback($request);
    }
}

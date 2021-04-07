<?php

namespace Alexzvn\MoneyBooster\Drivers;

use Alexzvn\MoneyBooster\Contracts\BoosterCallbackContract;
use GuzzleHttp\Client;
use Alexzvn\MoneyBooster\Contracts\BoosterDriverContract;
use Alexzvn\MoneyBooster\Contracts\CardContract;
use Alexzvn\MoneyBooster\Exception\MoreThanOneCardFoundException;
use Alexzvn\MoneyBooster\Exception\NoCardFoundException;
use sekjun9878\RequestParser\Request;

abstract class Driver implements BoosterDriverContract
{
    protected Client $client;

    /**
     * API key
     */
    protected string $secret = '';

    /**
     * @var string[]
     */
    protected static array $cards = [

    ];

    public function __construct(string $apiKey) {
        $this->client = new Client([
            'base_uri' => $this->api()
        ]);

        $this->secret = $apiKey;
    }

    public static function makeCard(string $code, string $seri, int $amount): CardContract
    {
        $card = [];

        foreach (static::$cards as $class) {
            $card[] = new $class($code, $seri, $amount);
        }

        if (count($card) > 1) {
            throw new MoreThanOneCardFoundException();
        }

        if (count($card) ===0) {
            throw new NoCardFoundException();
        }

        return $card[0];
    }

    abstract public static function callback(Request $request): BoosterCallbackContract;

    /**
     * return endpoint API
     *
     * @return string base uri
     */
    abstract protected function api(): string;
}

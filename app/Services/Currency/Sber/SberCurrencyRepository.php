<?php

declare(strict_types=1);


namespace App\Services\Currency\Sber;

use App\Services\Currency\Interface\CurrencyRepositoryInterface;
use Predis\ClientInterface;


class SberCurrencyRepository implements CurrencyRepositoryInterface
{

    private string $redisJsonKey = 'Json';

    public function __construct(
        private readonly ClientInterface $client
    ) {
        $this->redisJsonKey .= config('currency.sber.cache.key');
    }

    public function getJson(): string
    {
        return $this->client->get($this->redisJsonKey);
    }

    public function setJson(string $json): void
    {
        $this->client->set($this->redisJsonKey, $json);
    }

    public function existJson(): bool
    {
        return (bool) $this->client->exists($this->redisJsonKey);
    }

    public function delete(): bool
    {
        return (bool) $this->client->del($this->redisJsonKey);
    }


}
<?php

declare(strict_types=1);


namespace App\Services\Currency\Interface;

interface CurrencyRepositoryInterface
{
    public function getJson(): string;

    public function setJson(string $json): void;

    public function existJson(): bool;

}
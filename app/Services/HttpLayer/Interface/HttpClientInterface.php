<?php

declare(strict_types=1);


namespace App\Services\HttpLayer\Interface;

interface HttpClientInterface
{
    /**
     * @param  string  $url
     *
     * @return string
     */
    public function get(string $url): string;
}
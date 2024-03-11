<?php

declare(strict_types=1);


namespace App\Services\HttpLayer;

use App\Services\HttpLayer\Interface\HttpClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpClientFactory implements HttpClientInterface
{

    private Client $client;

    public function __construct(
        array $config = [],
        int $maxRetries = 5
    ) {
        $this->client = $this->init($config, $maxRetries);
    }


    private function init(array $config, $maxRetries): Client
    {
        $decider = static function (
            int $retries,
            RequestInterface $request,
            ResponseInterface $response = null
        )
        use ($maxRetries): bool {
            return
                $retries < $maxRetries
                && null !== $response
                && match ($response->getStatusCode()) {
                    429, 503 => true,
                    default => false
                };
        };

        $stack = HandlerStack::create();
        $stack->push(Middleware::retry($decider));
        $config['handler'] = $stack;

        return new Client($config);
    }


    /**
     * @throws GuzzleException
     */
    public function get(string $url): string
    {
        return $this->client->get($url)->getBody()->getContents();
    }
}
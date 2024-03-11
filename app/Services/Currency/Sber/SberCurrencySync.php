<?php

declare(strict_types=1);


namespace App\Services\Currency\Sber;

use App\Services\Currency\Exceptions\CurrencySyncException;
use App\Services\Currency\Interface\CurrencyRepositoryInterface;
use App\Services\HttpLayer\HttpClientFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Encoder\XmlEncoder;

class SberCurrencySync
{
    /**
     * @throws CurrencySyncException
     */
    public static function run(
        LoggerInterface $logger,
        CurrencyRepositoryInterface $repository
    ): void {
        $logger->info(__METHOD__, ['action' => 'start']);

        $url = config('currency.sber.host');
        $maxRetries = (int) config('currency.sber.maxRetries');

        try {
            $xml = (new HttpClientFactory(maxRetries: $maxRetries))->get($url);

            $result = (new XmlEncoder())->decode($xml, 'xml');

            $result['lastSync'] = date("Y-m-d H:i:s");

            $json = json_encode($result,
                JSON_THROW_ON_ERROR
                | JSON_NUMERIC_CHECK
                | JSON_PRESERVE_ZERO_FRACTION
                | JSON_FORCE_OBJECT);

            if ( ! json_validate($json)) {
                throw new \RuntimeException('Json data not validated');
            }

            $repository->setJson($json);
        } catch (\Throwable $e) {
            throw new CurrencySyncException($e->getMessage());
        }

        $logger->info(__METHOD__, ['action' => 'end']);
    }


}
<?php

declare(strict_types=1);


namespace App\Console\Commands;

use App\Services\Currency\Exceptions\CurrencySyncException;
use App\Services\Currency\Sber\SberCurrencyRepository;
use App\Services\Currency\Sber\SberCurrencySync;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;


class SyncCurrency extends Command
{
    protected $signature = 'sync:currency';
    protected $description = 'Sync currency';

    /**
     * @throws CurrencySyncException
     */
    public function handle(LoggerInterface $logger, SberCurrencyRepository $repository): void
    {
        SberCurrencySync::run($logger, $repository);

        $this->info('Sync Done!');
    }
}
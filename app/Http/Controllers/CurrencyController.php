<?php

namespace App\Http\Controllers;


use App\Services\Currency\Exceptions\CurrencySyncException;
use App\Services\Currency\Sber\SberCurrencyRepository;
use App\Services\Currency\Sber\SberCurrencySync;
use Illuminate\Http\Response;
use Laravel\Lumen\Routing\Controller as BaseController;
use Psr\Log\LoggerInterface;

class CurrencyController extends BaseController
{

    /**
     * @throws CurrencySyncException
     */
    public function all(SberCurrencyRepository $repository, LoggerInterface $logger): Response
    {
        $logger->info(__METHOD__, ['action' => 'start']);

        //на всякий, если вдруг пропадут данные, запускаем синк
        if ( ! $repository->existJson()) {
            SberCurrencySync::run($logger, $repository);
        }

        $val = $repository->getJson();

        $logger->info(__METHOD__, ['action' => 'end']);

        return response($val)->header('Content-Type', 'application/json');
    }


}

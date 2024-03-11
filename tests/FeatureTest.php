<?php

namespace Tests;


use App\Services\Currency\Sber\SberCurrencyRepository;

class FeatureTest extends TestCase
{
    /**
     *
     *
     * @return void
     */
    public function test_that_currency_endpoint_returns_a_successful_response()
    {
        $this->get('/currency/all');

        $this->assertResponseStatus(200);
    }

    /**
     *
     *
     * @return void
     */
    public function test_fail_safe_if_storage_empty()
    {
        $app = $this->createApplication();
        /** @var SberCurrencyRepository $repo */
        $repo = $app->make(SberCurrencyRepository::class);

        $repo->delete();

        $this->get('/currency/all');

        $this->assertResponseStatus(200);
    }

  
}

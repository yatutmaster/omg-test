<?php


use App\Services\Currency\Sber\SberCurrencyRepository;
use App\Services\Currency\Sber\SberCurrencySync;
use Psr\Log\LoggerInterface;

class UnitTest extends \Tests\TestCase
{

    /**
     *
     *
     * @return void
     */
    public function test_sber_currency_repository()
    {
        $app = $this->createApplication();
        /** @var SberCurrencyRepository $repo */
        $repo = $app->make(SberCurrencyRepository::class);

        $repo->delete();

        $this->assertFalse($repo->existJson());

        $repo->setJson('{"test":2}');

        $this->assertTrue($repo->existJson());
    }

    /**
     *
     *
     * @return void
     */
    public function test_sber_currency_sync()
    {
        $app = $this->createApplication();
        /** @var SberCurrencyRepository $repo */
        $repo = $app->make(SberCurrencyRepository::class);

        $oldJson = $repo->getJson();

        sleep(2); //minimum run after 2 sec

        SberCurrencySync::run($app->make(LoggerInterface::class), $repo);

        $newJson = $repo->getJson();

        $this->assertNotSame(md5($oldJson), md5($newJson));
    }

}

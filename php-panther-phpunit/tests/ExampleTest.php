<?php
namespace Tests;

use Carbonate\SDK;
use Carbonate\Browser\PantherBrowser;
use Facebook\WebDriver\WebDriverSelect;
use Symfony\Component\Panther\PantherTestCase;
use Symfony\Component\Panther\Client as PantherClient;
use Throwable;

class ExampleTest extends PantherTestCase
{
    private static $sdk;
    private static $browser;

    public static function setUpBeforeClass(): void
    {
        self::$browser = new PantherBrowser(PantherClient::createChromeClient());

        self::$sdk = new SDK(
            self::$browser,
            __DIR__ .'/'. pathinfo(__FILE__, PATHINFO_FILENAME),
            //"<your user ID>", // TODO: Change me
            //"<your api key>" // TODO: Change me
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::$sdk->startTest(__CLASS__, $this->getName());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        self::$sdk->endTest();
    }

    protected function onNotSuccessfulTest(Throwable $t): void
    {
        self::$sdk->handleFailedTest($t);
        parent::onNotSuccessfulTest($t);
    }

    public function testSelectBirthdayFromTheDropdown()
    {
        self::$sdk->load(
            'https://carbonate.dev/demo-form'
        );

        self::$sdk->action('select Birthday from the event type dropdown');

        $this->assertTrue(
            self::$sdk->assertion('the event type dropdown should be set to Birthday')
        );
    }

    public function testSelectBirthdayFromTheDropdownAdvanced()
    {
        $select = new WebDriverSelect(self::$sdk->lookup('the event type dropdown'));

        $select->selectByVisibleText('Birthday');

        $this->assertSame('Birthday', $select->getFirstSelectedOption()->getAttribute('value'));
    }
}
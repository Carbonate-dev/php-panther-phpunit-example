<?php
namespace Tests;

use Carbonate\SDK;
use Carbonate\Browser\PantherBrowser;
use Facebook\WebDriver\WebDriverSelect;
use Symfony\Component\Panther\Client as PantherClient;
use Symfony\Component\Panther\PantherTestCase;
use Throwable;

class ExampleTest extends PantherTestCase
{
    private static $carbonate;
    private static $browser;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$browser = new PantherBrowser(PantherClient::createChromeClient());

        self::$carbonate = new SDK(
            self::$browser,
            __DIR__ .'/'. pathinfo(__FILE__, PATHINFO_FILENAME),
            //"<your user ID>", // TODO: Change me
            //"<your api key>" // TODO: Change me
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        self::$carbonate->startTest(__CLASS__, $this->getName());
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        self::$carbonate->endTest();
    }

    protected function onNotSuccessfulTest(Throwable $t): void
    {
        self::$carbonate->handleFailedTest($t);
        parent::onNotSuccessfulTest($t);
    }

    public function testSelectBirthdayFromTheDropdown()
    {
        self::$carbonate->load(
            'https://carbonate.dev/demo-form'
        );

        self::$carbonate->action('select Birthday from the event type dropdown');

        $this->assertTrue(
            self::$carbonate->assertion('the event type dropdown should be set to Birthday')
        );
    }

    public function testSelectBirthdayFromTheDropdownAdvanced()
    {
        $select = new WebDriverSelect(self::$carbonate->lookup('the event type dropdown'));

        $select->selectByVisibleText('Birthday');

        $this->assertSame(
            'Birthday',
            $select->getFirstSelectedOption()->getAttribute('value')
        );
    }
}
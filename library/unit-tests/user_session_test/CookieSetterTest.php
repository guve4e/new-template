<?php
/**
 * Tests CookieSetter Class.
 */
require_once ("../../../relative-paths.php");
require_once ("../UtilityTest.php");
require_once (USER_SESSION_PATH . "/CookieSetter.php");

use PHPUnit\Framework\TestCase;

class CookieSetterTest extends TestCase
{
    use UtilityTest;

    /**
     * Create test subject before test
     */
    protected function setUp()
    {
        $_SERVER['REMOTE_ADDR'] = "REMOTE_ADDR";
        $_SERVER['HTTP_USER_AGENT'] = "HTTP_USER_AGENT";
        $_SERVER['HTTP_ACCEPT'] = "HTTP_ACCEPT";
        $_COOKIE['somewebsite-user'] = "mcsiljcincklsdncvklsdvisdn";
    }

    public function testProperSettingOfHash() {
        // Arrange
        $cookie = new CookieSetter("SomeCookie", 2);

        // Act
        // We cant call $cookie->setCookie, because of a header problem
        // Then invoke makeHash, to make sure we are generating same
        // hash every time
        $this->invokeMethod($cookie, 'makeHash');

        // Assert
        $actualHash = $cookie->getHash();
        $this->assertSame("cdf26213a150dc3ecb610f18f6b38b46", $actualHash);
    }

    protected function tearDown()
    {
        $_SERVER = array();
        $_COOKIE = array();
    }
}

<?php declare(strict_types=1);

/*
 * This file is part of the Speedy package.
 *
 * (c) Tomáš Kliner <kliner.tomas@gmail.com>
 *
 */

namespace Speedy\FTP\Tests;

use PHPUnit\Framework\TestCase;
use Speedy\FTP\Connection\Connection;

/**
 * Class ConnectionTestCase
 *
 * @package     Speedy\FTP\Tests
 * @author      Tomáš Kliner <kliner.tomas@gmail.com>
 * @since       1.0.0
 */
class ConnectionTestCase extends TestCase
{
    public const TIMEOUT = 90;

    public static $connection;
    public static $connected = false;

    /**
     * Test the creation of connections at the beginning of all the tests that inherit from this class
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$connection = new Connection(getenv('FTP_HOST'), getenv('FTP_USERNAME'), getenv('FTP_PASSWORD'), (int)getenv('FTP_PORT'), self::TIMEOUT, true);

        try {
            self::$connection->open();
            self::$connected = true;
        } catch (\Exception $e) {
            self::$connected = false;
        }

    }

    /**
     * Check if connection is ok before each test
     */
    public function setUp()
    {
        if (false === self::$connected) {
            $this->markTestSkipped('Fails to connect to the ftp server, make sure the server is running and the parameters are defined in the phpunit configuration file');
        }
    }

}
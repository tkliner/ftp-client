<?php

declare(strict_types=1);

namespace Speedy\FTP\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Speedy\FTP\Connection\Connection;

/**
 * @author Tomáš Kliner <kliner.tomas@gmail.com>
 */
class ConnectionTestCase extends TestCase
{
    public const TIMEOUT = 90;

    public static Connection $connection;
    public static bool $connected = false;

    /**
     * Test the creation of connections at the beginning of all the tests that inherit from this class
     */
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        self::$connection = new Connection(
            getenv('FTP_HOST'),
            getenv('FTP_USERNAME'),
            getenv('FTP_PASSWORD'),
            (int)getenv('FTP_PORT'),
            self::TIMEOUT,
            true,
        );

        try {
            self::$connection->open();
            self::$connected = true;
        } catch (Exception $exception) {
            self::$connected = false;
        }
    }

    /**
     * Check if connection is ok before each test
     */
    protected function setUp(): void
    {
        if (false === self::$connected) {
            static::markTestSkipped('Fails to connect to the ftp server, make sure the server is running and the parameters are defined in the phpunit configuration file');
        }
    }
}

<?php

declare(strict_types=1);

namespace Speedy\FTP\Tests;

use PHPUnit\Framework\TestCase;
use Speedy\FTP\Connection\Connection;
use Speedy\FTP\ConnectionInterface;
use Speedy\FTP\Exception\ConnectionAlreadyEstablishedException;
use Speedy\FTP\Exception\ConnectionBadCredentialsException;
use Speedy\FTP\Exception\ConnectionException;

/**
 * @author Tomáš Kliner <kliner.tomas@gmail.com>
 */
class ConnectionTest extends TestCase
{
    private string $host;
    private int $port;
    private string $username;
    private string $password;

    protected function setUp(): void
    {
        $this->__construct();

        $this->host = (string) getenv('FTP_HOST');
        $this->port = (int) getenv('FTP_PORT');
        $this->username = (string) getenv('FTP_USERNAME');
        $this->password = (string) getenv('FTP_PASSWORD');
    }

    /**
     * Basic test to create a class connection instance
     */
    public function testCreateInstanceConnectionClass(): void
    {
        $connection = new Connection($this->host, $this->username, $this->password, $this->port);
        static::assertInstanceOf(
            ConnectionInterface::class,
            $connection,
            'Connection class does not implement connection interface',
        );
    }

    /**
     * Test to establish a connection to the server
     */
    public function testEstablishConnectionSuccess(): void
    {
        $connection = new Connection($this->host, $this->username, $this->password, $this->port);

        try {
            static::assertTrue($connection->open(), 'Failed to create connection');
            static::assertTrue($connection->isConnected(), 'Method isConnected return bad state');
        } catch (Exception $exception) {
            static::markTestSkipped($exception->getMessage());
        }
    }

    /**
     * Test to establish multiple connections during one instance of the class connection
     */
    public function testMultipleEstablishConnection(): void
    {
        $this->expectException(ConnectionAlreadyEstablishedException::class);

        $connection = new Connection($this->host, $this->username, $this->password, $this->port);
        $connection->open();
        $connection->open();
    }

    /**
     * Test to establish a connection with a non-existing host parameter
     */
    public function testEstablishConnectionWithBadHost(): void
    {
        $this->expectException(ConnectionException::class);

        $connection = new Connection('foobar.google.com', $this->username, $this->password, $this->port);
        $connection->open();
    }

    /**
     * Test to establish connection with bad login data
     */
    public function testEstablishConnectionWithBadCredentials(): void
    {
        $this->expectException(ConnectionBadCredentialsException::class);

        $connection = new Connection($this->host, 'abc', '123456', $this->port);
        $connection->open();
    }

    /**
     * Test to establish connection with passive mode
     */
    public function testEstablishConnectionWithPassiveMode(): void
    {
        try {
            $connection = new Connection($this->host, $this->username, $this->password, $this->port, 90, true);

            static::assertTrue($connection->open(), 'Failed to create connection');
            static::assertTrue($connection->isConnected(), 'Method isConnected return bad state');
        } catch (Exception $exception) {
            static::markTestSkipped($exception->getMessage());
        }
    }

    /**
     * Termination test
     */
    public function testCloseConnection(): void
    {
        try {
            $connection = new Connection($this->host, $this->username, $this->password, $this->port);
            $connection->open();
        } catch (Exception $exception) {
            static::markTestSkipped($exception->getMessage());
        }

        static::assertTrue($connection->close(), 'Connection was not closed');
        static::assertFalse($connection->isConnected(), 'Method isConnected return bad state');
    }
}

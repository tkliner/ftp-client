<?php declare(strict_types=1);

/*
 * This file is part of the Speedy package.
 *
 * (c) Tomáš Kliner <kliner.tomas@gmail.com>
 *
 */

use PHPUnit\Framework\TestCase;
use Speedy\FTP\Connection\Connection;
use Speedy\FTP\ConnectionInterface;

/**
 * Class ConnectionTest
 * @author      Tomáš Kliner <kliner.tomas@gmail.com>
 * @version     1.0.0
 */
class ConnectionTest extends TestCase
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var integer
     */
    private $port;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    public function setUp()
    {
        parent::__construct();

        $this->host = getenv('FTP_HOST');
        $this->port = (int)getenv('FTP_PORT');
        $this->username = getenv('FTP_USERNAME');
        $this->password = getenv('FTP_PASSWORD');
    }

    /**
     * Basic test to create a class connection instance
     */
    public function testCreateInstanceConnectionClass()
    {
        $connection = new Connection($this->host, $this->username, $this->password, $this->port);
        $this->assertInstanceOf(ConnectionInterface::class, $connection, 'Connection class does not implement connection interface');
    }

    /**
     * Test to establish a connection to the server
     */
    public function testEstablishConnectionSuccess()
    {
        $connection = new Connection($this->host, $this->username, $this->password, $this->port);

        try {
            $this->assertTrue($connection->open(), 'Failed to create connection');
            $this->assertTrue($connection->isConnected(), 'Method isConnected return bad state');
        } catch (\Exception $e) {
            $this->markTestSkipped();
        }
    }

    /**
     * Test to establish multiple connections during one instance of the class connection
     *
     * @expectedException \Speedy\FTP\Exception\ConnectionAlreadyEstablishedException
     */
    public function testMultipleEstablishConnection()
    {
        $connection = new Connection($this->host, $this->username, $this->password, $this->port);
        $connection->open();
        $connection->open();
    }

    /**
     * Test to establish a connection with a non-existing host parameter
     *
     * @expectedException \Speedy\FTP\Exception\ConnectionException
     */
    public function testEstablishConnectionWithBadHost()
    {
        $connection = new Connection('foobar.google.com', $this->username, $this->password, $this->port);
        $connection->open();
    }

    /**
     * Test to establish connection with bad login data
     *
     * @expectedException \Speedy\FTP\Exception\ConnectionBadCredentialsException
     */
    public function testEstablishConnectionWithBadCredentials()
    {
        $connection = new Connection($this->host, 'abc', '123456', $this->port);
        $connection->open();
    }

    /**
     * Test to establish connection with passive mode
     */
    public function testEstablishConnectionWithPassiveMode()
    {
        try {
            $connection = new Connection($this->host, $this->username, $this->password, $this->port, 90, true);

            $this->assertTrue($connection->open(), 'Failed to create connection');
            $this->assertTrue($connection->isConnected(), 'Method isConnected return bad state');
        } catch (\Exception $e) {
            $this->markTestSkipped();
        }
    }

    /**
     * Termination test
     */
    public function testCloseConnection()
    {
        try {
            $connection = new Connection($this->host, $this->username, $this->password, $this->port);
            $connection->open();

            $this->assertTrue($connection->close(), 'Connection was not closed');
            $this->assertFalse($connection->isConnected(), 'Method isConnected return bad state');
        } catch (\Exception $e) {
            $this->markTestSkipped();
        }
    }

}
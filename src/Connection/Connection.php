<?php declare(strict_types=1);

/*
 * This file is part of the Speedy package.
 *
 * (c) Tomáš Kliner <kliner.tomas@gmail.com>
 *
 */

namespace Speedy\FTP\Connection;

use Speedy\FTP\ConnectionInterface;
use Speedy\FTP\Exception\ConnectionAlreadyEstablishedException;
use Speedy\FTP\Exception\ConnectionBadCredentialsException;
use Speedy\FTP\Exception\ConnectionException;

/**
 * Class FTP Connection
 *
 * @package     Speedy\FTP\Connection
 * @author      Tomáš Kliner <kliner.tomas@gmail.com>
 * @version     1.0.0
 */
class Connection implements ConnectionInterface
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var int
     */
    private $port;

    /**
     * @var int
     */
    private $timeout;

    /**
     * @var bool
     */
    private $passive;

    /**
     * @var bool
     */
    private $connected;

    /**
     * @var resource
     */
    private $resource;

    /**
     * Connection constructor.
     *
     * @param string $host
     * @param string $username
     * @param string $password
     * @param int    $port
     * @param int    $timeout
     * @param bool   $passive
     *
     * @throws \Exception
     */
    public function __construct(
        string $host,
        string $username,
        string $password,
        int $port = 21,
        int $timeout = 90,
        bool $passive = false
    )
    {
        if (!\extension_loaded('ftp')) {
            throw new \LogicException('PHP extension FTP is not loaded.');
        }

        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->passive = $passive;
    }

    /**
     * Open connection to server and login user
     *
     * @return bool
     * @throws ConnectionAlreadyEstablishedException
     * @throws ConnectionException
     * @throws ConnectionBadCredentialsException
     */
    public function open(): bool
    {
        // checking whether the connection is already established
        if ($this->isConnected()) {
            throw new ConnectionAlreadyEstablishedException(
                'Connection is already established, multiple connections can not be established within one process'
            );
        }

        // try establish a connection with the ftp server
        $this->resource = $this->connect();

        if (false === $this->resource || !\is_resource($this->resource)) {
            throw new ConnectionException(
                sprintf('Can\'t connect to the server %s on port %s', $this->getHost(), $this->getPort())
            );
        }

        // user attempt and login
        if (!@ftp_login($this->getResource(), $this->getUserName(), $this->getPassword())) {
            throw new ConnectionBadCredentialsException(
                sprintf(
                    'It is not possible to login with this username %s and this %s password',
                    $this->getUserName(),
                    $this->getPassword()
                )
            );
        }

        if (true === $this->passive) {
            if (!@ftp_pasv($this->getResource(), $this->passive)) {
                throw new ConnectionException('Passive mode can not be turned on');
            }
        }

        $this->setConnected(true);

        return true;
    }

    /**
     * Connects to the server
     *
     * @return resource
     */
    protected function connect()
    {
        return @\ftp_connect($this->getHost(), $this->getPort(), $this->getTimeout());
    }

    /**
     * Return the status of the connection has been closed or not
     *
     * @return bool
     */
    public function close(): bool
    {
        $state = \ftp_close($this->getResource());
        $this->setConnected(!$state);

        return $state;
    }

    /**
     * Return connection state
     *
     * @return bool
     */
    public function isConnected(): ?bool
    {
        return $this->connected;
    }

    /**
     * Close the connection when the object is destroyed
     */
    public function __destruct()
    {
        if ($this->isConnected()) {
            $this->close();
        }
    }

    /************ GETTERS AND SETTERS ************/

    /**
     * Return FTP protocol resource
     *
     * @return resource
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Return host
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * Return port
     *
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * Return timeout
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Return username
     *
     * @return string
     */
    public function getUserName(): string
    {
        return $this->username;
    }

    /**
     * Return password
     *
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * This method implements fluent interface
     *
     * @param $param null|bool
     *
     * @return ConnectionInterface
     */
    public function setConnected(?bool $param): ConnectionInterface
    {
        $this->connected = $param;

        return $this;
    }

}
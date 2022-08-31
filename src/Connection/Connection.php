<?php

declare(strict_types=1);

namespace BPM\FTP\Connection;

use BPM\FTP\ConnectionInterface;
use BPM\FTP\Exception\ConnectionAlreadyEstablishedException;
use BPM\FTP\Exception\ConnectionBadCredentialsException;
use BPM\FTP\Exception\ConnectionException;
use LogicException;

use function extension_loaded;

/**
 * @author Tomáš Kliner <kliner.tomas@gmail.com>
 */
class Connection implements ConnectionInterface
{
    private string $host;
    private string $username;
    private string $password;
    private int $port;
    private int $timeout;
    private bool $passive;
    private ?bool $connected = null;

    /** @var false|resource|\FTP\Connection */
    private $resource;

    /**
     * @throws LogicException
     */
    public function __construct(
        string $host,
        string $username,
        string $password,
        int $port = 21,
        int $timeout = 90,
        bool $passive = false
    ) {
        if (!extension_loaded('ftp')) {
            throw new LogicException('PHP extension FTP is not loaded.');
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

        // try to establish a connection with the ftp server
        $this->resource = $this->connect();

        if (false === $this->resource) {
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

        if (true === $this->passive && !@ftp_pasv($this->getResource(), $this->passive)) {
            throw new ConnectionException('Passive mode can not be turned on');
        }

        $this->setConnected(true);

        return true;
    }

    /**
     * Connects to the server
     *
     * @return resource|false|\FTP\Connection
     */
    protected function connect()
    {
        return @ftp_connect($this->getHost(), $this->getPort(), $this->getTimeout());
    }

    /**
     * Return the status of the connection has been closed or not
     */
    public function close(): bool
    {
        $state = ftp_close($this->getResource());
        $this->setConnected(!$state);

        return $state;
    }

    /**
     * Return connection state
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

    /**
     * Return FTP protocol resource
     *
     * @return false|resource|\FTP\Connection
     */
    public function getResource()
    {
        return $this->resource;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getTimeout(): int
    {
        return $this->timeout;
    }

    public function getUserName(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setConnected(?bool $param): ConnectionInterface
    {
        $this->connected = $param;

        return $this;
    }
}

<?php

declare(strict_types=1);

namespace Speedy\FTP;

interface ConnectionInterface
{
    /**
     * Return PHP FTP resource connection
     *
     * @return resource
     */
    public function getResource();

    /**
     * Return connection state
     */
    public function isConnected(): ?bool;

    /**
     * Open connection to server and login user
     */
    public function open(): bool;
}

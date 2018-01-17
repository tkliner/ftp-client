<?php declare(strict_types=1);

/*
 * This file is part of the Speedy package.
 *
 * (c) Tomáš Kliner <kliner.tomas@gmail.com>
 *
 */

namespace Speedy\FTP;

/**
 * Interface ConnectionInterface
 *
 * @package Speedy\FTP
 */
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
     *
     * @return bool|null
     */
    public function isConnected(): ?bool;

    /**
     * Open connection to server and login user
     *
     * @return bool
     */
    public function open(): bool;

}
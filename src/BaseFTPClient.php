<?php declare(strict_types=1);

/*
 * This file is part of the Speedy package.
 *
 * (c) Tomáš Kliner <kliner.tomas@gmail.com>
 *
 */

namespace Speedy\FTP;

use Speedy\FTP\Exception\CommandException;

/**
 * Class BaseFTPClient
 * @package     Speedy\FTP
 * @author      Tomáš Kliner <kliner.tomas@gmail.com>
 * @version     1.0.0
 */
abstract class BaseFTPClient
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * BaseFTPClient constructor.
     *
     * @param ConnectionInterface $connection
     */
    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Allows any existing php ftp function to be called
     *
     * @param string $command
     *
     * @return mixed
     * @throws CommandException
     */
    public function ftp(string $command)
    {
        if (!$this->connection->isConnected()) {
            $this->connection->open();
        }

        $args = \func_get_args();
        $args[0] = $this->connection->getResource();

        return $this->executeCommand('ftp_' . $command, $args);
    }

    /**
     * Ensures the execution of a defined function with defined arguments
     *
     * @param callable $function
     * @param array    $args
     *
     * @return mixed
     * @throws CommandException
     */
    private function executeCommand(callable $function, array $args = [])
    {
        try {
            $result = \call_user_func_array($function, $args);
        } catch (\Exception $e) {
            throw new CommandException($e->getMessage(), $e->getCode(), $e);
        }

        return $result;
    }

}
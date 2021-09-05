<?php

declare(strict_types=1);

namespace Speedy\FTP;

use Exception;
use Speedy\FTP\Exception\CommandException;
use function call_user_func_array;
use function func_get_args;

/**
 * @author Tomáš Kliner <kliner.tomas@gmail.com>
 */
abstract class BaseFTPClient
{
    /** @var ConnectionInterface */
    protected $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Allows any existing php ftp function to be called
     *
     * @throws CommandException
     */
    public function ftp(string $command)
    {
        if (!$this->connection->isConnected()) {
            $this->connection->open();
        }

        $args = func_get_args();
        $args[0] = $this->connection->getResource();

        return $this->executeCommand('ftp_' . $command, $args);
    }

    /**
     * Ensures the execution of a defined function with defined arguments
     *
     * @throws CommandException
     */
    private function executeCommand(callable $function, array $args = [])
    {
        try {
            $result = call_user_func_array($function, $args);
        } catch (Exception $e) {
            throw new CommandException($e->getMessage(), $e->getCode(), $e);
        }

        return $result;
    }
}

<?php

declare(strict_types=1);

namespace Speedy\FTP;

use Speedy\FTP\Exception\CommandException;

/**
 * @author Tomáš Kliner <kliner.tomas@gmail.com>
 */
class SimpleFTPClient extends BaseFTPClient
{
    /**
     * Download a file from ftp
     *
     * @see http://php.net/manual/en/function.ftp-get.php
     * @throws CommandException
     */
    public function get(string $localFile, string $remoteFile, int $mode = FTP_BINARY, int $resumepos = 0): bool
    {
        return $this->ftp('get', $localFile, $remoteFile, $mode, $resumepos);
    }

    /**
     * Download a file from ftp with non-blocking mode
     *
     * @see http://php.net/manual/en/function.ftp-nb-get.php
     * @throws CommandException
     */
    public function getNb(string $localFile, string $remoteFile, int $mode = FTP_BINARY, int $resumepos = 0): int
    {
        return $this->ftp('nb_get', $localFile, $remoteFile, $mode, $resumepos);
    }

    /**
     * Downloads a file from the FTP server and saves to an open file
     *
     * @see http://php.net/ftp_fget
     * @throws CommandException
     */
    public function fget($handle, string $remoteFile, int $mode = FTP_BINARY, int $resumepos = 0): bool
    {
        return $this->ftp('fget', $handle, $remoteFile, $mode, $resumepos);
    }

    /**
     * Retrieves a file from the FTP server and writes it to an open file (non-blocking)
     *
     * @see http://php.net/ftp_nb_fget
     * @throws CommandException
     */
    public function fgetNb($handle, string $remoteFile, int $mode = FTP_BINARY, int $resumepos = 0): int
    {
        return $this->ftp('nb_fget', $handle, $remoteFile, $mode, $resumepos);
    }

    /**
     * Continues retrieving/sending a file (non-blocking)
     *
     * @see http://php.net/ftp_nb_continue
     * @throws CommandException
     */
    public function nbContinue(): int
    {
        return $this->ftp('nb_continue');
    }

    /**
     * Uploads a file to the FTP server
     *
     * @see http://php.net/manual/en/function.ftp-put.php
     * @throws CommandException
     */
    public function put(string $remoteFile, string $localFile, int $mode = FTP_BINARY, int $startpos = 0): bool
    {
        return $this->ftp('put', $remoteFile, $localFile, $mode, $startpos);
    }

    /**
     * Stores a file on the FTP server (non-blocking)
     *
     * @see http://php.net/manual/en/function.ftp-nb-put.php
     * @throws CommandException
     */
    public function putNb(string $remoteFile, string $localFile, int $mode = FTP_BINARY, int $startpos = 0): int
    {
        return $this->ftp('nb_put', $remoteFile, $localFile, $mode, $startpos);
    }

    /**
     * Uploads from an open file to the FTP server
     *
     * @see http://php.net/manual/en/function.ftp-fput.php
     * @throws CommandException
     */
    public function fput(string $remoteFile, $handle, int $mode = FTP_BINARY, int $startpos = 0): bool
    {
        return $this->ftp('fput', $remoteFile, $handle, $mode, $startpos);
    }

    /**
     * Stores a file from an open file to the FTP server (non-blocking)
     *
     * @see http://php.net/manual/en/function.ftp-nb-fput.php
     * @throws CommandException
     */
    public function fputNb($remoteFile, $handle, $mode, $startpos = 0): int
    {
        return $this->ftp('nb_fput', $remoteFile, $handle, $mode, $startpos);
    }

    /**
     * Retrieves various runtime behaviours of the current FTP stream
     *
     * @see http://php.net/manual/en/function.ftp-get-option.php
     * @throws CommandException
     */
    public function getOption(int $option)
    {
        return $this->ftp('get_option', $option);
    }

    /**
     * Set miscellaneous runtime FTP options
     *
     * @see http://php.net/manual/en/function.ftp-set-option.php
     * @throws CommandException
     */
    public function setOption(int $option, $value): bool
    {
        return $this->ftp('set_option', $option, $value);
    }

    /**
     * Changes to the parent directory
     *
     * @see http://php.net/manual/en/function.ftp-cdup.php
     * @throws CommandException
     */
    public function cdup(): bool
    {
        return $this->ftp('cdup');
    }

    /**
     * Changes the current directory on a FTP server
     *
     * @see http://php.net/manual/en/function.ftp-chdir.php
     * @throws CommandException
     */
    public function chdir(string $directory): bool
    {
        return $this->ftp('chdir', $directory);
    }

    /**
     * Creates a directory
     *
     * @see http://php.net/manual/en/function.ftp-mkdir.php
     * @throws CommandException
     */
    public function mkdir(string $directory)
    {
        return $this->ftp('mkdir', $directory);
    }

    /**
     * Removes a directory
     *
     * @see http://php.net/manual/en/function.ftp-rmdir.php
     * @throws CommandException
     */
    public function rmdir(string $directory): bool
    {
        return $this->ftp('rmdir', $directory);
    }

    /**
     * Returns a list of files in the folder on ftp server
     *
     * @see http://php.net/manual/en/function.ftp-nlist.php
     * @throws CommandException
     */
    public function nlist(string $directory)
    {
        return $this->ftp('nlist', $directory);
    }

    /**
     * Returns a detailed list of files in the given directory
     *
     * @see http://php.net/manual/en/function.ftp-rawlist.php
     * @throws CommandException
     */
    public function rawlist(string $directory, bool $recursive = false)
    {
        return $this->ftp('rawlist', $directory, $recursive);
    }

    /**
     * Returns the current directory name
     *
     * @throws CommandException
     */
    public function pwd()
    {
        return $this->ftp('pwd');
    }

    /**
     * Sends an arbitrary command to an FTP server
     *
     * @throws CommandException
     */
    public function raw(string $command)
    {
        return $this->ftp('raw', $command);
    }

    /**
     * Requests execution of a command on the FTP server
     *
     * @see http://php.net/manual/en/function.ftp-exec.php
     * @throws CommandException
     */
    public function exec(string $command): bool
    {
        return $this->ftp('exec', $command);
    }

    /**
     * Allocates space for a file to be uploaded
     *
     * @see http://php.net/manual/en/function.ftp-alloc.php
     * @throws CommandException
     */
    public function alloc(int $filesize, string $result = null): bool
    {
        return $this->ftp('alloc', $filesize, $result);
    }

    /**
     * Set permissions on a file via FTP
     *
     * @see http://php.net/manual/en/function.ftp-chmod.php
     * @throws CommandException
     */
    public function chmod(int $mode, string $filename)
    {
        return $this->ftp('chmod', $mode, $filename);
    }

    /**
     * Renames a file or a directory on the FTP server
     *
     * @see http://php.net/manual/en/function.ftp-rename.php
     * @throws CommandException
     */
    public function rename(string $oldname, string $newname): bool
    {
        return $this->ftp('rename', $oldname, $newname);
    }

    /**
     * Deletes a file on the FTP server
     *
     * @see http://php.net/manual/en/function.ftp-delete.php
     * @throws CommandException
     */
    public function delete(string $path): bool
    {
        return $this->ftp('delete', $path);
    }

    /**
     * Returns the last modified time of the given file
     *
     * @see http://php.net/manual/en/function.ftp-mdtm.php
     * @throws CommandException
     */
    public function mdtm(string $remoteFile)
    {
        return $this->ftp('mdtm', $remoteFile);
    }

    /**
     * Turns passive mode on or off
     *
     * @see http://php.net/manual/en/function.ftp-pasv.php
     * @throws CommandException
     */
    public function pasv(bool $pasv): bool
    {
        return $this->ftp('pasv', $pasv);
    }

    /**
     * Returns the size of the given file
     *
     * @see http://php.net/manual/en/function.ftp-size.php
     * @throws CommandException
     */
    public function size(string $remoteFile)
    {
        return $this->ftp('size', $remoteFile);
    }

    /**
     * Returns the system type identifier of the remote FTP server
     *
     * @throws CommandException
     */
    public function systype()
    {
        return $this->ftp('systype');
    }
}

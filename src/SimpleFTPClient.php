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
 * Class SimpleFTPClient
 *
 * @package     Speedy\FTP
 * @author      Tomáš Kliner <kliner.tomas@gmail.com>
 * @version     1.0.0
 */
class SimpleFTPClient extends BaseFTPClient
{
    /**
     * Download a file from ftp
     * @see http://php.net/manual/en/function.ftp-get.php
     *
     * @param string $localFile
     * @param string $remoteFile
     * @param int    $mode
     * @param int    $resumepos
     *
     * @return mixed
     * @throws CommandException
     */
    public function get(string $localFile, string $remoteFile, int $mode = FTP_BINARY, int $resumepos = 0): bool
    {
        return $this->ftp('get', $localFile, $remoteFile, $mode, $resumepos);
    }

    /**
     * Download a file from ftp with non-blocking mode
     * @see http://php.net/manual/en/function.ftp-nb-get.php
     *
     * @param string $localFile
     * @param string $remoteFile
     * @param int    $mode
     * @param int    $resumepos
     *
     * @return mixed
     * @throws CommandException
     */
    public function getNb(string $localFile, string $remoteFile, int $mode = FTP_BINARY, int $resumepos = 0): int
    {
        return $this->ftp('nb_get', $localFile, $remoteFile, $mode, $resumepos);
    }

    /**
     * Downloads a file from the FTP server and saves to an open file
     * @see http://php.net/ftp_fget
     *
     * @param        $handle
     * @param string $remoteFile
     * @param int    $mode
     * @param int    $resumepos
     *
     * @return mixed
     * @throws CommandException
     */
    public function fget($handle, string $remoteFile, int $mode = FTP_BINARY, int $resumepos = 0): bool
    {
        return $this->ftp('fget', $handle, $remoteFile, $mode, $resumepos);
    }

    /**
     * Retrieves a file from the FTP server and writes it to an open file (non-blocking)
     * @see http://php.net/ftp_nb_fget
     *
     * @param     $handle
     * @param     $remoteFile
     * @param     $mode
     * @param int $resumepos
     *
     * @return mixed
     * @throws CommandException
     */
    public function fgetNb($handle, string $remoteFile, int $mode = FTP_BINARY, int $resumepos = 0): int
    {
        return $this->ftp('nb_fget', $handle, $remoteFile, $mode, $resumepos);
    }

    /**
     * Continues retrieving/sending a file (non-blocking)
     * @see http://php.net/ftp_nb_continue
     *
     * @return mixed
     * @throws CommandException
     */
    public function nbContinue(): int
    {
        return $this->ftp('nb_continue');
    }

    /**
     * Uploads a file to the FTP server
     * @see http://php.net/manual/en/function.ftp-put.php
     *
     * @param string $remoteFile
     * @param string $localFile
     * @param int    $mode
     * @param int    $startpos
     *
     * @return bool
     * @throws CommandException
     */
    public function put(string $remoteFile, string $localFile, int $mode = FTP_BINARY, int $startpos = 0): bool
    {
        return $this->ftp('put', $remoteFile, $localFile, $mode, $startpos);
    }

    /**
     * Stores a file on the FTP server (non-blocking)
     * @see http://php.net/manual/en/function.ftp-nb-put.php
     *
     * @param string $remoteFile
     * @param string $localFile
     * @param int    $mode
     * @param int    $startpos
     *
     * @return int
     * @throws CommandException
     */
    public function putNb(string $remoteFile, string $localFile, int $mode = FTP_BINARY, int $startpos = 0): int
    {
        return $this->ftp('nb_put', $remoteFile, $localFile, $mode, $startpos);
    }

    /**
     * Uploads from an open file to the FTP server
     * @see http://php.net/manual/en/function.ftp-fput.php
     *
     * @param string $remoteFile
     * @param        $handle
     * @param int    $mode
     * @param int    $startpos
     *
     * @return bool
     * @throws CommandException
     */
    public function fput(string $remoteFile, $handle, int $mode = FTP_BINARY, int $startpos = 0): bool
    {
        return $this->ftp('fput', $remoteFile, $handle, $mode, $startpos);
    }

    /**
     * Stores a file from an open file to the FTP server (non-blocking)
     * @see http://php.net/manual/en/function.ftp-nb-fput.php
     *
     * @param     $remoteFile
     * @param     $handle
     * @param     $mode
     * @param int $startpos
     *
     * @return int
     * @throws CommandException
     */
    public function fputNb($remoteFile, $handle, $mode, $startpos = 0): int
    {
        return $this->ftp('nb_fput', $remoteFile, $handle, $mode, $startpos);
    }

    /**
     * Retrieves various runtime behaviours of the current FTP stream
     * @see http://php.net/manual/en/function.ftp-get-option.php
     *
     * @param int $option
     *
     * @return mixed
     * @throws CommandException
     */
    public function getOption(int $option)
    {
        return $this->ftp('get_option', $option);
    }

    /**
     * Set miscellaneous runtime FTP options
     * @see http://php.net/manual/en/function.ftp-set-option.php
     *
     * @param int   $option
     * @param mixed $value
     *
     * @return bool
     * @throws CommandException
     */
    public function setOption(int $option, $value): bool
    {
        return $this->ftp('set_option', $option, $value);
    }

    /**
     * Changes to the parent directory
     * @see http://php.net/manual/en/function.ftp-cdup.php
     *
     * @return bool
     * @throws CommandException
     */
    public function cdup(): bool
    {
        return $this->ftp('cdup');
    }

    /**
     * Changes the current directory on a FTP server
     * @see http://php.net/manual/en/function.ftp-chdir.php
     *
     * @param string $directory
     *
     * @return bool
     * @throws CommandException
     */
    public function chdir(string $directory): bool
    {
        return $this->ftp('chdir', $directory);
    }

    /**
     * Creates a directory
     * @see http://php.net/manual/en/function.ftp-mkdir.php
     *
     * @param string $directory
     *
     * @return mixed
     * @throws CommandException
     */
    public function mkdir(string $directory)
    {
        return $this->ftp('mkdir', $directory);
    }

    /**
     * Removes a directory
     * @see http://php.net/manual/en/function.ftp-rmdir.php
     *
     * @param string $directory
     *
     * @return bool
     * @throws CommandException
     */
    public function rmdir(string $directory): bool
    {
        return $this->ftp('rmdir', $directory);
    }

    /**
     * Returns a list of files in the folder on ftp server
     * @see http://php.net/manual/en/function.ftp-nlist.php
     *
     * @param $directory
     *
     * @return mixed
     * @throws CommandException
     */
    public function nlist(string $directory)
    {
        return $this->ftp('nlist', $directory);
    }

    /**
     * Returns a detailed list of files in the given directory
     * @see http://php.net/manual/en/function.ftp-rawlist.php
     *
     * @param string $directory
     * @param bool   $recursive
     *
     * @return mixed
     * @throws CommandException
     */
    public function rawlist(string $directory, bool $recursive = false)
    {
        return $this->ftp('rawlist', $directory, $recursive);
    }

    /**
     * Returns the current directory name
     *
     * @return mixed
     * @throws CommandException
     */
    public function pwd()
    {
        return $this->ftp('pwd');
    }

    /**
     * Sends an arbitrary command to an FTP server
     *
     * @param string $command
     *
     * @return mixed
     * @throws CommandException
     */
    public function raw(string $command)
    {
        return $this->ftp('raw', $command);
    }

    /**
     * Requests execution of a command on the FTP server
     * @see http://php.net/manual/en/function.ftp-exec.php
     *
     * @param string $command
     *
     * @return bool
     * @throws CommandException
     */
    public function exec(string $command): bool
    {
        return $this->ftp('exec', $command);
    }

    /**
     * Allocates space for a file to be uploaded
     * @see http://php.net/manual/en/function.ftp-alloc.php
     *
     * @param int         $filesize
     * @param string|null $result
     *
     * @return bool
     * @throws CommandException
     */
    public function alloc(int $filesize, string &$result = null): bool
    {
        return $this->ftp('alloc', $filesize, $result);
    }

    /**
     * Set permissions on a file via FTP
     * @see http://php.net/manual/en/function.ftp-chmod.php
     *
     * @param int    $mode
     * @param string $filename
     *
     * @return mixed
     * @throws CommandException
     */
    public function chmod(int $mode, string $filename)
    {
        return $this->ftp('chmod', $mode, $filename);
    }

    /**
     * Renames a file or a directory on the FTP server
     * @see http://php.net/manual/en/function.ftp-rename.php
     *
     * @param string $oldname
     * @param string $newname
     *
     * @return bool
     * @throws CommandException
     */
    public function rename(string $oldname, string $newname): bool
    {
        return $this->ftp('rename', $oldname, $newname);
    }

    /**
     * Deletes a file on the FTP server
     * @see http://php.net/manual/en/function.ftp-delete.php
     *
     * @param string $path
     *
     * @return bool
     * @throws CommandException
     */
    public function delete(string $path): bool
    {
        return $this->ftp('delete', $path);
    }

    /**
     * Returns the last modified time of the given file
     * @see http://php.net/manual/en/function.ftp-mdtm.php
     *
     * @param string $remoteFile
     *
     * @return mixed
     * @throws CommandException
     */
    public function mdtm(string $remoteFile)
    {
        return $this->ftp('mdtm', $remoteFile);
    }

    /**
     * Turns passive mode on or off
     * @see http://php.net/manual/en/function.ftp-pasv.php
     *
     * @param bool $pasv
     *
     * @return bool
     * @throws CommandException
     */
    public function pasv(bool $pasv): bool
    {
        return $this->ftp('pasv', $pasv);
    }

    /**
     * Returns the size of the given file
     * @see http://php.net/manual/en/function.ftp-size.php
     *
     * @param string $remoteFile
     *
     * @return mixed
     * @throws CommandException
     */
    public function size(string $remoteFile)
    {
        return $this->ftp('size', $remoteFile);
    }

    /**
     * Returns the system type identifier of the remote FTP server
     *
     * @return mixed
     * @throws CommandException
     */
    public function systype()
    {
        return $this->ftp('systype');
    }

}
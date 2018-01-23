<?php declare(strict_types=1);

/*
 * This file is part of the Speedy package.
 *
 * (c) Tomáš Kliner <kliner.tomas@gmail.com>
 *
 */

use Speedy\FTP\SimpleFTPClient;
use Speedy\FTP\Tests\ConnectionTestCase;

/**
 * Class SimpleFTPClientTest
 *
 * @author      Tomáš Kliner <kliner.tomas@gmail.com>
 * @since       1.0.0
 */
class SimpleFTPClientTest extends ConnectionTestCase
{
    /**
     * @var SimpleFTPClient
     */
    public $simpleFtpClient;

    /**
     * Create SimpleFTPClient before each test
     */
    public function setUp()
    {
        parent::setUp();

        $this->simpleFtpClient = new SimpleFTPClient(parent::$connection);
    }

    /**
     * Check if fpt server is empty
     *
     * @throws \Speedy\FTP\Exception\CommandException
     */
    public function testListFilesSuccess()
    {
        $this->assertEmpty($this->simpleFtpClient->nlist('/'), 'FTP server isn\'t empty');
    }

    /**
     * basic test for upload and download file
     *
     * @throws \Speedy\FTP\Exception\CommandException
     */
    public function testUploadAndDownloadFileSuccess()
    {
        $uploadState = $this->simpleFtpClient->put('/test1', __DIR__ . '/content/file1.txt');
        $this->assertTrue($uploadState, 'File upload failed');

        $downloadState = $this->simpleFtpClient->get(__DIR__ . '/content/file1_download.txt', '/test1');
        $this->assertTrue($downloadState, 'File download failed');

        $originalFileContent = file_get_contents(__DIR__ . '/content/file1.txt');
        $downloadFileContent = file_get_contents(__DIR__ . '/content/file1_download.txt');

        $this->assertSame($originalFileContent, $downloadFileContent, 'The content of the downloaded file does not match the original file');

        unlink(__DIR__ . '/content/file1_download.txt');
    }

    /**
     * Basic test for non-blocking file upload and download
     *
     * @throws \Speedy\FTP\Exception\CommandException
     */
    public function testNonBlockingUploadAndDownloadFileSuccess()
    {
        $uploadState = $this->simpleFtpClient->putNb('/test2', __DIR__ . '/content/file2.txt');
        $this->assertSame(1, $uploadState, 'Non blocking file upload failed');

        $downloadState = $this->simpleFtpClient->getNb(__DIR__ . '/content/file2_download.txt', '/test2');

        while ($downloadState === FTP_MOREDATA) {
            $downloadState = $this->simpleFtpClient->nbContinue();
        }

        $this->assertSame(1, $downloadState, 'File download failed');

        $originalFileContent = file_get_contents(__DIR__ . '/content/file2.txt');
        $downloadFileContent = file_get_contents(__DIR__ . '/content/file2_download.txt');

        $this->assertSame($originalFileContent, $downloadFileContent, 'The content of the downloaded file does not match the original file');

        unlink(__DIR__ . '/content/file2_download.txt');
    }

    /**
     * Basic test for get server options
     *
     * @throws \Speedy\FTP\Exception\CommandException
     */
    public function testGetOptionExist()
    {
        $value = $this->simpleFtpClient->getOption(FTP_TIMEOUT_SEC);
        $this->assertSame(parent::TIMEOUT, $value, 'The return value does not match the defined value in the test');
    }

    /**
     * Basic test for get non-exist server option
     *
     * @expectedException \Speedy\FTP\Exception\CommandException
     */
    public function testGetOptionNonExist()
    {
        $this->simpleFtpClient->getOption(100);
    }

    /**
     * Basic test for set server option and validate this option
     *
     * @throws \Speedy\FTP\Exception\CommandException
     */
    public function testSetOptionAndValidate()
    {
        $this->simpleFtpClient->setOption(FTP_TIMEOUT_SEC, 10);
        $value = $this->simpleFtpClient->getOption(FTP_TIMEOUT_SEC);

        $this->assertSame(10, $value, 'Returned value does not match the expected value');
    }

    /**
     * Basic test for create and remove directory
     *
     * @throws \Speedy\FTP\Exception\CommandException
     */
    public function testCreateAndRemoveDirectory()
    {
        $state = $this->simpleFtpClient->mkdir('/test');
        $this->assertNotFalse($state, 'Folder was not created');

        $deleteState = $this->simpleFtpClient->rmdir('/test');
        $this->assertNotFalse($deleteState, 'Folder was not deleted');
    }

    /**
     * Clear FTP server after each test
     *
     * @throws \Speedy\FTP\Exception\CommandException
     */
    public function tearDown()
    {
        $files = $this->simpleFtpClient->nlist('/', true);

        foreach ($files as $file) {
            $this->simpleFtpClient->delete('/' . $file);
        }
    }

}
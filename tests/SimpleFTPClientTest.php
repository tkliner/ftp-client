<?php

declare(strict_types=1);

use Speedy\FTP\Exception\CommandException;
use Speedy\FTP\SimpleFTPClient;
use Speedy\FTP\Tests\ConnectionTestCase;

/**
 * @author Tomáš Kliner <kliner.tomas@gmail.com>
 */
class SimpleFTPClientTest extends ConnectionTestCase
{
    public SimpleFTPClient $simpleFtpClient;

    /**
     * Create SimpleFTPClient before each test
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->simpleFtpClient = new SimpleFTPClient(parent::$connection);
    }

    /**
     * Check if fpt server is empty
     */
    public function testListFilesSuccess(): void
    {
        self::markTestSkipped('Free FTP server is not empty everytime.');
        #static::assertEmpty($this->simpleFtpClient->nlist('/'), 'FTP server isn\'t empty');
    }

    /**
     * basic test for upload and download file
     */
    public function testUploadAndDownloadFileSuccess(): void
    {
        $uploadState = $this->simpleFtpClient->put('/test1', __DIR__ . '/content/file1.txt');
        static::assertTrue($uploadState, 'File upload failed');

        $downloadState = $this->simpleFtpClient->get(__DIR__ . '/content/file1_download.txt', '/test1');
        static::assertTrue($downloadState, 'File download failed');

        $originalFileContent = file_get_contents(__DIR__ . '/content/file1.txt');
        $downloadFileContent = file_get_contents(__DIR__ . '/content/file1_download.txt');

        static::assertSame(
            $originalFileContent,
            $downloadFileContent,
            'The content of the downloaded file does not match the original file',
        );

        unlink(__DIR__ . '/content/file1_download.txt');
    }

    /**
     * Basic test for non-blocking file upload and download
     */
    public function testNonBlockingUploadAndDownloadFileSuccess(): void
    {
        $uploadState = $this->simpleFtpClient->putNb('/test2', __DIR__ . '/content/file2.txt');
        static::assertSame(1, $uploadState, 'Non blocking file upload failed');

        $downloadState = $this->simpleFtpClient->getNb(__DIR__ . '/content/file2_download.txt', '/test2');

        while ($downloadState === FTP_MOREDATA) {
            $downloadState = $this->simpleFtpClient->nbContinue();
        }

        static::assertSame(1, $downloadState, 'File download failed');

        $originalFileContent = file_get_contents(__DIR__ . '/content/file2.txt');
        $downloadFileContent = file_get_contents(__DIR__ . '/content/file2_download.txt');

        static::assertSame(
            $originalFileContent,
            $downloadFileContent,
            'The content of the downloaded file does not match the original file',
        );

        unlink(__DIR__ . '/content/file2_download.txt');
    }

    /**
     * Basic test for get server options
     */
    public function testGetOptionExist(): void
    {
        $value = $this->simpleFtpClient->getOption(FTP_TIMEOUT_SEC);
        static::assertSame(parent::TIMEOUT, $value, 'The return value does not match the defined value in the test');
    }

    /**
     * Basic test for get non-exist server option
     */
    public function testGetOptionNonExist(): void
    {
        $this->expectException(CommandException::class);

        $this->simpleFtpClient->getOption(100);
    }

    /**
     * Basic test for set server option and validate this option
     */
    public function testSetOptionAndValidate(): void
    {
        $this->simpleFtpClient->setOption(FTP_TIMEOUT_SEC, 10);
        $value = $this->simpleFtpClient->getOption(FTP_TIMEOUT_SEC);

        static::assertSame(10, $value, 'Returned value does not match the expected value');
    }

    /**
     * Basic test for create and remove directory
     */
    public function testCreateAndRemoveDirectory(): void
    {
        $state = $this->simpleFtpClient->mkdir('/test');
        static::assertNotFalse($state, 'Folder was not created');

        $deleteState = $this->simpleFtpClient->rmdir('/test');
        static::assertNotFalse($deleteState, 'Folder was not deleted');
    }

    /**
     * Clear FTP server after each test
     */
    protected function tearDown(): void
    {
        $files = $this->simpleFtpClient->nlist('/');

        foreach ($files as $file) {
            $this->simpleFtpClient->delete('/' . $file);
        }
    }
}

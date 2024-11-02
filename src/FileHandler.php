<?php

declare(strict_types=1);

namespace Nigo\Doc;

use Exception;

final class FileHandler
{
    /**
     * @throws Exception
     *
     * @return resource
     */
    public function openFileForReading(string $path)
    {
        $file = fopen($path, 'r');
        if ($file === false) {
            throw new Exception("Cannot open file for reading: {$path}");
        }
        return $file;
    }

    /**
     * @throws Exception
     *
     * @return resource
     */
    public function openFileForWriting(string $path)
    {
        $file = fopen($path, 'a');
        if ($file === false) {
            throw new Exception("Cannot open file for writing: {$path}");
        }
        return $file;
    }

    /**
     * @param resource $file
     */
    public function closeFile($file): void
    {
        fclose($file);
    }

    /**
     * @param resource $writer
     *
     * @throws Exception
     */
    public function writeString($writer, string $str): void
    {
        if (fwrite($writer, $str) === false) {
            throw new Exception('Cannot write to file');
        }
    }

    /**
     * @param resource $reader
     */
    public function readLine($reader): string|false
    {
        return fgets($reader, 4096);
    }

    /**
     * @throws Exception
     */
    public function ensureFileExists(string $path): void
    {
        if (! file_exists($path)) {
            throw new Exception("File {$path} not exist");
        }
    }

    public function createNameFromExistsFile(
        string $path,
        string $format
    ): string {
        $folders = explode('/', $path);
        $file = $folders[count($folders) - 1];
        $fileData = explode('.', $file);

        $fileData[0] .= '_Translated';
        $fileData[1] = $format;

        $folders[count($folders) - 1] = implode('.', $fileData);
        return implode('/', $folders);
    }
}

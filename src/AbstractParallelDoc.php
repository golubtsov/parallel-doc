<?php

namespace Nigo\Doc;

use Exception;
use Nigo\Translator\LibreTranslator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

abstract class AbstractParallelDoc implements ParallelDocInterface
{
    protected string $target;

    protected string $format;

    protected LibreTranslator $translator;

    public function __construct(string $target)
    {
        $this->setFormat();
        $this->target = $target;
        $this->translator = LibreTranslator::create($this->target);
    }

    abstract protected function markup(
        string $line,
        string $translatedLine
    ): string;

    abstract protected function setFormat(): void;

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function generate(
        string  $pathRead,
    ): string|false
    {
        try {
            $pathWrite = $this->createNameFromExistsFile($pathRead);

            $this->isExists($pathRead);

            $reader = fopen($pathRead, 'r');

            $this->canReadFile($reader);

            $writer = fopen($pathWrite, 'a');

            $this->canReadFile($writer);

            file_put_contents($pathWrite, '');

            $this->saveContent($reader, $writer);

            return $pathWrite;
        } catch (Exception|TransportExceptionInterface $exception) {
            return false;
        }
    }

    protected function createNameFromExistsFile(string $path): string
    {
        $folders = explode('/', $path);

        $file = $folders[count($folders) - 1];

        $fileData = explode('.', $file);

        // Create name for new file
        $fileData[0] = $fileData[0] . '_Translated';

        // File format
        $fileData[1] = $this->format;

        $folders[count($folders) - 1] = implode('.', $fileData);

        return implode('/', $folders);

    }

    /**
     * @param resource|false $file
     * @throws Exception
     */
    protected function canReadFile($file): void
    {
        if (FALSE === $file) {
            throw new Exception("Cannot open file ($file)");
        }
    }

    /**
     * @throws Exception
     */
    protected function isExists(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception("File $path not exist");
        }
    }

    /**
     * @param resource $reader
     * @param resource $writer
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface|TransportExceptionInterface
     */
    protected function saveContent($reader, $writer): void
    {
        $this->writeContent($reader, $writer);

        fclose($reader);

        fclose($writer);
    }

    /**
     * @param resource $reader
     * @param resource $writer
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    protected function writeContent($reader, $writer): void
    {
        while (($buffer = fgets($reader, 4096)) !== false) {
            if ($buffer !== PHP_EOL) {
                $line = str_replace(PHP_EOL, '', $buffer);

                $translatedLine = $this->translator->translate($line);

                $str = $this->markup($line, $translatedLine);

                $this->writeString($writer, $str);
            }
        }
    }

    /**
     * @param resource $writer
     * @throws Exception
     */
    protected function writeString($writer, string $str): void
    {
        if (fwrite($writer, $str) === FALSE) {
            throw new Exception("Cannot write to file $writer");
        }
    }
}
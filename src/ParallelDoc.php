<?php

declare(strict_types=1);

namespace Nigo\Doc;

use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class ParallelDoc implements ParallelDocI
{
    private string $target;
    private string $format;
    private FileHandler $fileHandler;
    private Translator $translator;
    private MarkupFormatter $markupFormatter;

    public function __construct(
        string $target,
        string $format,
        MarkupFormatter $markupFormatter
    ) {
        $this->target = $target;
        $this->format = $format;
        $this->fileHandler = new FileHandler();
        $this->translator = new Translator($target);
        $this->markupFormatter = $markupFormatter;
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function generate(string $pathRead): string|false
    {
        $pathWrite = $this->fileHandler
            ->createNameFromExistsFile($pathRead, $this->format);

        $this->fileHandler->ensureFileExists($pathRead);

        $reader = $this->fileHandler->openFileForReading($pathRead);
        $writer = $this->fileHandler->openFileForWriting($pathWrite);

        file_put_contents($pathWrite, '');
        $this->saveContent($reader, $writer);

        return $pathWrite;
    }

    /**
     * @param resource $reader
     * @param resource $writer
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    private function saveContent($reader, $writer): void
    {
        while (($buffer = $this->fileHandler->readLine($reader)) !== false) {
            if ($buffer !== PHP_EOL) {
                $line = str_replace(PHP_EOL, '', $buffer);
                $translatedLine = $this->translator->translate($line);
                $str = $this->markupFormatter->markup($line, $translatedLine);
                $this->fileHandler->writeString($writer, $str);
            }
        }

        $this->fileHandler->closeFile($reader);
        $this->fileHandler->closeFile($writer);
    }
}

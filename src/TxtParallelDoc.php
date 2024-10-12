<?php

namespace Nigo\Doc;

use Exception;
use Nigo\Translator\LibreTranslator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class TxtParallelDoc
{
    private LibreTranslator $translator;

    public function __construct(string $target)
    {
        $this->translator = LibreTranslator::create($target);
    }

    /**
     * @throws Exception|TransportExceptionInterface
     */
    public function generate(string $pathRead, string|null $pathWrite = null): true
    {
        if (is_null($pathWrite)) {
            $pathWrite = $this->createNameFromExistsFile($pathRead);
        }

        $this->isExists($pathRead);

        $reader = fopen($pathRead, 'r');

        $this->canReadFile($reader);

        $writer = fopen($pathWrite, 'a');

        $this->canReadFile($writer);

        file_put_contents($pathWrite, '');

        $this->saveContent($reader, $writer);

        return true;
    }

    private function createNameFromExistsFile(string $path): string
    {
        $folders = explode('/', $path);

        $file = $folders[count($folders) - 1];

        $fileData = explode('.', $file);

        $fileData[0] = $fileData[0] . '_Translated';

        $folders[count($folders) - 1] = implode('.', $fileData);

        return implode('/', $folders);

    }

    /**
     * @param resource|false $file
     * @throws Exception
     */
    private function canReadFile($file): void
    {
        if (FALSE === $file) {
            throw new Exception("Cannot open file ($file)");
        }
    }

    /**
     * @throws Exception
     */
    private function isExists(string $path): void
    {
        if (!file_exists($path)) {
            throw new Exception("File $path not exist");
        }
    }

    /**
     * @param resource $reader
     * @param resource $writer
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    private function saveContent($reader, $writer): void
    {
        while (($buffer = fgets($reader, 4096)) !== false) {
            if ($buffer !== PHP_EOL) {
                $line = str_replace(PHP_EOL, '', $buffer);

                $translatedLine = $this->translator->translate($line);

                $str = $line . ' (' . $translatedLine . ')' . PHP_EOL . PHP_EOL;

                echo $str;

                if (fwrite($writer, $str) === FALSE) {
                    echo "Cannot write to file $writer";
                    exit;
                }
            }
        }

        fclose($reader);

        fclose($writer);
    }
}
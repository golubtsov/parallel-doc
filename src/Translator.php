<?php

declare(strict_types=1);

namespace Nigo\Doc;

use Nigo\Translator\LibreTranslator;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class Translator
{
    private LibreTranslator $translator;

    public function __construct(string $target)
    {
        $this->translator = LibreTranslator::create($target);
    }

    /**
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function translate(string $line): string
    {
        return $this->translator->translate($line);
    }
}

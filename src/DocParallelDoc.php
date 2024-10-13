<?php

namespace Nigo\Doc;

use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DocParallelDoc extends AbstractParallelDoc
{
    protected function setFormat(): void
    {
        $this->format = 'doc';
    }

    protected function markup(
        string $line,
        string $translatedLine
    ): string
    {
        return '<p><b>' . $line . '</b> (' . $translatedLine . ')' . '</p>';
    }
}
<?php

namespace Nigo\Doc;

class TxtParallelDoc extends AbstractParallelDoc
{
    protected function setFormat(): void
    {
        $this->format = 'txt';
    }

    protected function markup(
        string $line,
        string $translatedLine
    ): string
    {
        return $line . ' (' . $translatedLine . ')' . PHP_EOL . PHP_EOL;
    }
}
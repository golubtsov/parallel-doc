<?php

declare(strict_types=1);

namespace Nigo\Doc;

abstract class MarkupFormatter
{
    abstract public function markup(
        string $line,
        string $translatedLine
    ): string;
}

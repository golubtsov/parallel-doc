<?php

declare(strict_types=1);

namespace Nigo\Doc;

final class TxtMarkupFormatter extends MarkupFormatter
{
    #[\Override]
    public function markup(
        string $line,
        string $translatedLine
    ): string {
        return "{$line}\n\n({$translatedLine})\n\n";
    }
}

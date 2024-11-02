<?php

declare(strict_types=1);

namespace Nigo\Doc;

interface ParallelDocI
{
    public function generate(
        string $pathRead,
    ): string|false;
}

<?php

namespace Nigo\Doc;

interface ParallelDocInterface
{
    public function generate(
        string $pathRead,
    ): string|false;
}
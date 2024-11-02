<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
use Nigo\Doc\ParallelDoc;
use Nigo\Doc\TxtMarkupFormatter;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->load();

$markupFormatter = new TxtMarkupFormatter();

$parallelDoc = new ParallelDoc('en', 'txt', $markupFormatter);

$pathWrite = $parallelDoc->generate(__DIR__ . '/../texts/doc.txt');

echo "Translated file saved to: {$pathWrite}\n";

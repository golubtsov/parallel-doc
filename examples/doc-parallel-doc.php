<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
use Nigo\Doc\DocxMarkupFormatter;
use Nigo\Doc\ParallelDoc;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->load();

$markupFormatter = new DocxMarkupFormatter();

$parallelDoc = new ParallelDoc('en', 'docx', $markupFormatter);

$pathWrite = $parallelDoc->generate(__DIR__ . '/../texts/doc.txt');

echo "Translated file saved to: {$pathWrite}\n";

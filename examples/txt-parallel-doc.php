<?php

require_once 'vendor/autoload.php';

use Dotenv\Dotenv;
use Nigo\Doc\TxtParallelDoc;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->load();

$doc = new TxtParallelDoc('ru');

//$doc->generate(__DIR__ . '/../texts/doc.txt', __DIR__ . '/../texts/res.txt');

$doc->generate(__DIR__ . '/../texts/doc.txt');
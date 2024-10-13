<?php

require_once 'vendor/autoload.php';
require_once 'helpers.php';

use Dotenv\Dotenv;
use Nigo\Doc\TxtParallelDoc;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->load();

$doc = new TxtParallelDoc('ru');

printPathToFile($doc->generate(__DIR__ . '/../texts/doc.txt'));
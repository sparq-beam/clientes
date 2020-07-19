<?php
/*
 * Este código foi copiado e alterado dos exemplos de monolog, ver:
 * https://github.com/Seldaek/monolog/blob/master/doc/01-usage.md
 */
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

$logger = new Logger('logger');

$dateFormat = 'Y-m-d H:i:s T';
$formatter = new LineFormatter("%datetime%: %message% %context% \n", $dateFormat);

$handler = new StreamHandler('../info.log', Logger::INFO);
$handler->setFormatter($formatter);

$logger->pushHandler($handler);
?>
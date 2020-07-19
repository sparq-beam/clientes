<?php
/* Controlador que gera a página que lista os clientes. */
include_once 'common.php';
include_once 'db.php';
include_once 'dbUtils.php';
include_once 'Client.php';
include_once 'ClientIterator.php';
include_once 'formFields.php';

/*
 * Como exceções podem aparecer depois de já termos iniciado o output, usamos o
 * output buffer para poder limpá-lo em caso de erro.
 */
ob_start();

try {
    $clientIterator = new ClientIterator($pdo);
} catch (DBException $e) {
    error_log($e);
    fatalError(DATABASE_ERROR);
}

$viewData = [
    'title' => 'Lista de Clientes',
    'clientIt' => $clientIterator,
    'cssFile' => "css/list.css"
];

try {
    include 'view/list.php';
} catch (DBException $e) {
    error_log($e);
    fatalError(DATABASE_ERROR);
}

ob_end_flush();
?>

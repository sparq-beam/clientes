<?php
/* Criação da conexão à base de dados. */
include_once 'common.php';

try {
    /* Queremos que a função fetch levante exceções. */
    $pdo = new PDO("{$config['pdoDsn']}", null, null,
        array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ));
} catch (PDOException $e) {
    error_log($e);

    fatalError(DATABASE_ERROR);
}
?>

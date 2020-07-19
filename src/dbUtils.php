<?php
/*
 * Funções úteis para manipular a base de dados e facilitar o
 * gerenciamento de erros.
 *
 * A documentação de PDO não é muito clara com respeito ao
 * gerenciamento de erros. O objeto PDO é aberto especificando
 * PDO::ATTR_ERRMODE como PDO::ERRMODE_EXCEPTION. Contudo, a
 * documentação de várias funções só indica o retorno de FALSE como
 * indicador de erro. Somente "PDOStatement::prepare" documenta o uso
 * de exceções para indicar erros.
 *
 * Ainda assim, usamos exeções com o intuito de detectar erros da
 * chamada de "PDOStatement::fetch". A documentação indica que este
 * método retorna FALSE quando falha, mas o uso comum parece ser de
 * usar FALSE como indicação de que não há mais resultados, e um
 * comentário no código fonte do interpretador php também indica isto.
 * Este código parece também indicar que este método pode levantar
 * exceções.
 *
 * Para as outras funções (como "PDOStatement::execute" e
 * "PDOStatement::closeCursor"), verificamos tanto o retorno quanto as
 * exceções.
 */
include_once 'DBException.php';

function queryOrThrow($pdo, $query)
{
    try {
        $ret = $pdo->query("$query");
    } catch (PDOException $e) {
        throw new DBException($e);
    }

    if (! $ret) {
        throw new DBException();
    }

    return $ret;
}

function executeOrThrow($pdoStatement, $input_parameters = NULL)
{
    try {
        $ret = $pdoStatement->execute($input_parameters);
    } catch (PDOException $e) {
        throw new DBException($e);
    }

    if (! $ret) {
        throw new DBException();
    }
}

function prepareOrThrow($pdo, $query)
{
    try {
        $pdoStatement = $pdo->prepare($query);
    } catch (PDOException $e) {
        throw new DBException($e);
    }

    return $pdoStatement;
}

function fetchOrThrow($pdoStatement)
{
    try {
        $row = $pdoStatement->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new DBException($e);
    }

    return $row;
}

function closeCursorOrThrow($pdoStatement)
{
    try {
        $ret = $pdoStatement->closeCursor();
    } catch (PDOException $e) {
        throw new DBException($e);
    }

    if (! $ret) {
        throw new DBException();
    }
}

function beginTransactionOrFail($pdo)
{
    try {
        queryOrThrow($pdo, "BEGIN TRANSACTION");
    } catch (DBException $e) {
        error_log($e);
        fatalError(DATABASE_ERROR);
    }
}

function endTransactionOrFail($pdo)
{
    try {
        queryOrThrow($pdo, "END TRANSACTION");
    } catch (DBException $e) {
        error_log($e);
        fatalError(DATABASE_ERROR);
    }
}
?>

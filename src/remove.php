<?php
/* Controlador para remover um usuário. */
include_once 'common.php';
include_once 'db.php';
include_once 'dbUtils.php';
include_once 'formFields.php';
include_once 'validate.php';

const CLIENT_QUERY = 'SELECT id FROM clients WHERE id = :id FOR UPDATE';

const DELETE_QUERY = 'DELETE FROM clients WHERE id = :id';

$id = validateId();

/*
 * Faremos um transação para determinar se o cliente existe antes de
 * tentar removê-lo, assim podemos emitir um erro dizendo que o
 * cliente não existe caso isso seja o caso. A lista de clientes só
 * permite remover clientes que são listados, mas:
 *
 * 1) O usuário pode criar uma requisição POST com um id que não está
 * na lista.
 *
 * 2) Como são requisições separadas, um cliente pode ser
 * removido por outro usuário entre a listagem e o pedido de remoção.
 *
 * Usamos um SELECT ... FOR UPDATE para travar o cliente depois de
 * encontrá-lo, assim temos certeza que ele ainda existe ao removê-lo.
 * Acredito que no postgres mesmo em REPEATABLE READ isto pode falhar
 * sem o FOR UPDATE. De toda forma, estamos usando READ COMMITTED.
 */

beginTransactionOrFail($pdo);

try {
    $pdoStatement = prepareOrThrow($pdo, CLIENT_QUERY);

    executeOrThrow($pdoStatement, array(
        ':id' => $id
    ));

    $row = fetchOrThrow($pdoStatement);

    if (! $row) {
        endTransactionOrFail($pdo);
        fatalError(NO_CLIENT_ERROR);
    }

    closeCursorOrThrow($pdoStatement);

    $pdoStatement = prepareOrThrow($pdo, DELETE_QUERY);

    executeOrThrow($pdoStatement, array(
        ':id' => $id
    ));

} catch (DBException $e) {
    error_log($e);
    endTransactionOrFail($pdo);
    fatalError(DATABASE_ERROR);
}

endTransactionOrFail($pdo);

$logger->info ("Cliente removido.", ['id' => $id]);

header('Location: index.php');
?>

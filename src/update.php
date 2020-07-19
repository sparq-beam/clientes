<?php
/* Controlador para atualizar ou inserir um novo cliente. */
include_once 'common.php';
include_once 'db.php';
include_once 'dbUtils.php';
include_once 'formFields.php';
include_once 'validate.php';

const CLIENT_QUERY = 'SELECT id FROM clients WHERE id = :id FOR UPDATE';

const INSERT_QUERY = 'INSERT INTO clients (name, email, cpf, phone) VALUES (:name, :email, :cpf, :phone)';

const UPDATE_QUERY = 'UPDATE clients SET name = :name, email = :email, cpf = :cpf, phone = :phone WHERE id = :id';

$name = validateName();
$email = validateEmail();
$cpf = validateCPF();
$phone = validatePhone();

if (array_key_exists(FORM_CLIENT_ID, $_POST)) {
    /*
     * Vamos editar um usuário existente. Ver a explicação em remove.php para a
     * necessidade do SELECT ... FOR UPDATE e a transação.
     */
    $id = validateId();

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

        $pdoStatement = prepareOrThrow($pdo, UPDATE_QUERY);

        executeOrThrow($pdoStatement,
            array(
                ':id' => $id,
                ':name' => $name,
                ':email' => $email,
                ':cpf' => $cpf,
                ':phone' => $phone
            ));
    } catch (DBException $e) {
        error_log($e);
        endTransactionOrFail($pdo);
        fatalError(DATABASE_ERROR);
    }

    endTransactionOrFail($pdo);
    
    $logger->info ("Cliente alterado.", ['id' => $id]);
} else {
    /* Vamos criar um novo usuário. */
    try {
        $pdoStatement = prepareOrThrow($pdo, INSERT_QUERY);

        executeOrThrow($pdoStatement,
            array(
                ':name' => $name,
                ':email' => $email,
                ':cpf' => $cpf,
                ':phone' => $phone
            ));
    } catch (DBException $e) {
        error_log($e);
        fatalError(DATABASE_ERROR);
    }
    
    $logger->info ("Novo cliente.", ['name' => $name]);
}

/*
 * Não há página de confirmação em caso de sucesso, simplesmente mostramos a
 * lista atualizada.
 */
header('Location: index.php');
?>

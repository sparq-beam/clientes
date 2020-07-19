<?php

/*
 * Estas funções verificam se os campos necessários estão disponíveis
 * no _POST e, dependendo do campo, se tem um formato específico. Não é o
 * suficiente dependermos dos validadores no lado do cliente, pois ele pode
 * ainda assim criar uma request que não segue os requerimentos. Note que que o
 * validador do lado do cliente, no html (o atributo pattern das tags input), é
 * um pouco diferente destas expressões regulares.
 */
const ID_RE = '/^[0-9]+$/';

const EMAIL_RE = "/^[\.[:alnum:]]+@[\.[:alnum:]]+$/";

const CPF_RE = "/^[0-9]{3,3}\.[0-9]{3,3}-[0-9]{2,2}$/";

const PHONE_RE = "/^[0-9]{2,2}-[0-9]{3,3}$/";

function validateId()
{
    if (! array_key_exists(FORM_CLIENT_ID, $_POST)) {
        fatalError(INVALID_CLIENT_ID_ERROR);
    }

    $id = $_POST[FORM_CLIENT_ID];

    $match = preg_match(ID_RE, $id);

    if ($match === 0) {
        fatalError(INVALID_CLIENT_ID_ERROR);
    } else if (! $match) {
        fatalError(INTERNAL_ERROR);
    }

    return $id;
}

function validateName()
{
    if (! array_key_exists(FORM_CLIENT_NAME, $_POST)) {
        fatalError(INVALID_NAME_ERROR);
    }

    $name = $_POST[FORM_CLIENT_NAME];

    if ($name === '') {
        fatalError(INVALID_NAME_ERROR);
    }

    /*
     * Não sei como validar um nome, já que muitos caracteres
     * diferentes podem ser usados, e letras que não estão no alfabeto
     * latim. Esperemos que usar os statements preparados do PDO
     * diminua a possibilidade de injeções SQL.
     */
    return $name;
}

function validateEmail()
{
    if (! array_key_exists(FORM_CLIENT_EMAIL, $_POST)) {
        fatalError(INVALID_EMAIL_ERROR);
    }

    $email = $_POST[FORM_CLIENT_EMAIL];

    $match = preg_match(EMAIL_RE, $email);

    if ($match === 0) {
        fatalError(INVALID_EMAIL_ERROR);
    } else if (! $match) {
        fatalError(INTERNAL_ERROR);
    }

    return $email;
}

function validateCPF()
{
    if (! array_key_exists(FORM_CLIENT_CPF, $_POST)) {
        fatalError(INVALID_CPF_ERROR);
    }

    $cpf = $_POST[FORM_CLIENT_CPF];

    $match = preg_match(CPF_RE, $cpf);

    if ($match === 0) {
        fatalError(INVALID_CPF_ERROR);
    } else if (! $match) {
        fatalError(INTERNAL_ERROR);
    }

    return $cpf;
}

function validatePhone()
{
    if (! array_key_exists(FORM_CLIENT_PHONE, $_POST)) {
        fatalError(INVALID_PHONE_ERROR);
    }

    $phone = $_POST[FORM_CLIENT_PHONE];

    /*
     * O telefone não é obrigatório. Retornamos NULL para facilmente usar esta
     * função antes de inserir dados na base de dados.
     */
    if ($phone === '')
        return NULL;

    $match = preg_match(PHONE_RE, $phone);

    if ($match === 0) {
        fatalError(INVALID_PHONE_ERROR);
    } else if (! $match) {
        fatalError(INTERNAL_ERROR);
    }

    return $phone;
}
?>

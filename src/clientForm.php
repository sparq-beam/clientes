<?php

/*
 * Controlador que gera o formulário de criação ou edição de clientes. Ambos
 * usam o mesmo formulário.
 */
include_once 'common.php';
include_once 'validate.php';
include_once 'formFields.php';

ob_start();

if (array_key_exists(FORM_CLIENT_ID, $_POST)) {
    /*
     * Um id foi enviado, este é um formulário para editar um cliente
     * existente.
     */
    $new = FALSE;

    $id = validateId();
    $name = validateName();
    $email = validateEmail();
    $cpf = validateCPF();
    $phone = validatePhone();

    $viewData = [
        'title' => 'Editar cliente',
        'submitValue' => 'Confirmar',
        'id' => $id,
        'name' => $name,
        'email' => $email,
        'cpf' => $cpf,
        'phone' => $phone
    ];
} else {
    /* Nenhum id enviado, o formulário é para criar um novo cliente. */
    $viewData = [
        'title' => "Novo cliente",
        'submitValue' => 'Criar'
    ];
}

include 'view/clientForm.php';

ob_end_flush();
?>

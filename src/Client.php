<?php

/*
 * Simples representação de um cliente, usado por ClientIterator para listar os
 * clientes.
 */
class Client
{

    public $id;

    public $name;

    public $email;

    public $cpf;

    public $phone;

    /* $rowArray é um array com todas as propriedades de um cliente. */
    function __construct($rowArray)
    {
        $this->id = $rowArray['id'];
        $this->name = $rowArray['name'];
        $this->email = $rowArray['email'];
        $this->cpf = $rowArray['cpf'];
        $this->phone = $rowArray['phone'];
    }
}
?>

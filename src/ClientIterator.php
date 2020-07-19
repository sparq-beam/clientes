<?php

/*
 * Iterador de Clientes para ser usado com um foreach na listagem de clientes.
 */
class ClientIterator implements Iterator
{

    private $pdo;

    private $pdoStatement;

    private $validState;

    private $current;

    private const CLIENT_QUERY = 'SELECT * FROM clients ORDER BY id';

    function __construct($pdo)
    {
        $this->pdo = $pdo;

        $this->pdoStatement = prepareOrThrow($this->pdo, self::CLIENT_QUERY);
    }

    function current()
    {
        return $this->current;
    }

    function key()
    {
        return $this->current->id;
    }

    function next()
    {
        $row = fetchOrThrow($this->pdoStatement);

        if ($row) {
            $this->current = new Client($row);
            $this->validState = TRUE;
        } else {
            $this->validState = FALSE;
        }
    }

    function rewind()
    {
        closeCursorOrThrow($this->pdoStatement);

        executeOrThrow($this->pdoStatement);

        $this->next();
    }

    function valid()
    {
        return $this->validState;
    }
}
?>

<?php

/*
 * Funcão que gera uma página de erro, e apaga o conteúdo que já está no buffer
 * atual.
 */
function fatalError($errorString)
{
    $viewData = [
        'title' => 'Erro',
        'errorString' => $errorString
    ];

    /*
     * A aplicação só usa um nível de output buffer, então esta condição é
     * suficiente.
     */
    if (ob_get_level() > 0)
        ob_clean();

    include 'view/error.php';

    if (ob_get_level() > 0)
        ob_end_flush();

    exit();
}
?>

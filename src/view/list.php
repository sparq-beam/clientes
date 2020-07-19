<?php include 'header.php'; ?>

<table>

<thead>

<tr>
<th>Nome</th>
<th>e-mail</th>
<th>Pseudo-CPF</th>
<th>Pseudo-telefone</th>
<th></th>
<th></th>
</tr>

</thead>

<tbody>

<?php foreach ($viewData['clientIt'] as $client): ?>

<tr>

<td>
<?= $client->name ?>

</td>

<td>
<?= $client->email ?> 

</td>

<td>
<?= $client->cpf ?>

</td>

<td>
<?= $client->phone ?>
</td>

<td>
<form action="/remove.php" method="post">
<input id="<?= FORM_CLIENT_ID ?>"
       name="<?= FORM_CLIENT_ID ?>"
       type="hidden"
       value="<?= $client->id ?>">
<input type="submit" value="Remover">
</form>
</td>

<td>

<form action="/edit.php" method="post">
<input id="<?= FORM_CLIENT_ID ?>"
       name="<?= FORM_CLIENT_ID ?>"
       type="hidden"
       value="<?= $client->id ?>">

<input id="<?= FORM_CLIENT_NAME ?>"
       name="<?= FORM_CLIENT_NAME ?>"
       type="hidden"
       value="<?= $client->name ?>">

<input id="<?= FORM_CLIENT_EMAIL ?>"
       name="<?= FORM_CLIENT_EMAIL ?>"
       type="hidden"
       value="<?= $client->email ?>">

<input id="<?= FORM_CLIENT_CPF ?>"
       name="<?= FORM_CLIENT_CPF ?>"
       type="hidden"
       value="<?= $client->cpf ?>">

<input id="<?= FORM_CLIENT_PHONE ?>"
       name="<?= FORM_CLIENT_PHONE ?>"
       type="hidden"
       value="<?= $client->phone ?>">

<input type="submit" value="Editar">
</form>
</td>

</tr>
<?php endforeach; ?>

</tbody>
</table>

<br>

<form action="/new.php" method="get">
<input type="submit" value="Novo cliente">
</form>

<?php include 'footer.php'; ?>

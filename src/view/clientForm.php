<?php include 'header.php'; ?>

<form action="/update.php" method="post">

<?php if (array_key_exists ('id', $viewData)): ?>
<input id="<?= FORM_CLIENT_ID ?>"
       name="<?= FORM_CLIENT_ID ?>"
       type="hidden"
       value="<?= $viewData['id'] ?>"
>
<?php endif; ?>

<label for="<?= FORM_CLIENT_NAME ?>">
Nome:
</label>
<input id="<?= FORM_CLIENT_NAME ?>"
       name="<?= FORM_CLIENT_NAME ?>"
       type="text"
       required="required"
       value=<?= isset ($viewData['name'])? $viewData['name'] : '' ?>
>

<br>

<label for="<?= FORM_CLIENT_EMAIL ?>">
E-mail:
</label>
<input id="<?= FORM_CLIENT_EMAIL ?>"
       name="<?= FORM_CLIENT_EMAIL ?>"
       type="text"
       required="required"
       pattern="^[\w.]+@[\w\.]+$"
       title="Somente alfanuméricos, ponto e um @"
       value=<?= isset ($viewData['email'])? $viewData['email'] : '' ?>
>

<br>

<label for="<?= FORM_CLIENT_CPF ?>">
Pseudo-CPF (XXX.XXX-XX):
</label>
<input id="<?= FORM_CLIENT_CPF ?>"
       name="<?= FORM_CLIENT_CPF ?>"
       type="text"
       required="required"
       pattern="^|(\d{3,3}\.\d{3,3}-\d{2,2})$"
       value=<?= isset ($viewData['cpf'])? $viewData['cpf'] : '' ?>
>

<br>

<label for="<?= FORM_CLIENT_PHONE ?>">
Pseudo-telefone (XX-XXX):
</label>
<input id="<?= FORM_CLIENT_PHONE ?>"
       name="<?= FORM_CLIENT_PHONE ?>"
       type="text"
       pattern="^\d{2,2}-\d{3,3}$"
       value=<?= isset ($viewData['phone'])? $viewData['phone'] : '' ?>
>

<br>

<input type="submit" value="<?= $viewData['submitValue'] ?>">
</form>

<form action="/index.php" method="get">
<input type="submit" value="Voltar">
</form>

<?php include 'footer.php'; ?>

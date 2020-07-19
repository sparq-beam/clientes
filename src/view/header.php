<!DOCTYPE html>
<html>

<head>

<title>
<?= $viewData['title'], "\n" ?>

</title>

<?php if (array_key_exists ('cssFile', $viewData)): ?>
<link type="text/css" rel="stylesheet" href="<?= $viewData['cssFile'] ?>">
<?php endif; ?>

</head>

<body>

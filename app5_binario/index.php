<?php
require_once '../includes/menu.php';
require_once 'Binario.php';

$menu = new Menu();
$entrada = '';
$resultado = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entrada = trim($_POST['numero'] ?? '');
    $numero = filter_var($entrada, FILTER_VALIDATE_INT);

    if ($numero === false) {
        $error = 'Ingrese un número entero válido.';
    } else {
        $resultado = (new Binario($numero))->convertir();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decimal a binario</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?= $menu->render('../') ?>
    <main>
        <h1>Convertir un número entero a binario</h1>
        <form method="post">
            <label for="numero">Número entero:</label>
            <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($entrada) ?>" required>
            <button type="submit">Convertir</button>
        </form>

        <?php if ($error !== ''): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($resultado !== null): ?>
            <div class="resultado">
                Binario: <strong><?= $resultado ?></strong>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
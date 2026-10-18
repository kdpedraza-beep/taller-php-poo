<?php
require_once '../includes/menu.php';
require_once 'Serie.php';

$menu = new Menu();
$entrada = '';
$operacion = 'fibonacci';
$serie = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entrada = trim($_POST['numero'] ?? '');
    $operacion = $_POST['operacion'] ?? 'fibonacci';
    $n = filter_var($entrada, FILTER_VALIDATE_INT);

    if ($n === false || $n < 1 || $n > 20) {
        $error = 'Ingrese un número entero entre 1 y 20.';
    } else {
        $objeto = new Serie($n);
        if ($operacion === 'factorial') {
            $serie = $objeto->factorial();
        } else {
            $serie = $objeto->fibonacci();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fibonacci y factorial</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?= $menu->render('../') ?>
    <main>
        <h1>Sucesión de Fibonacci o factorial</h1>
        <form method="post">
            <label for="numero">Número (1 a 20):</label>
            <input type="text" id="numero" name="numero" value="<?= htmlspecialchars($entrada) ?>" required>

            <label for="operacion">Operación:</label>
            <select id="operacion" name="operacion">
                <option value="fibonacci" <?= $operacion === 'fibonacci' ? 'selected' : '' ?>>Sucesión de Fibonacci</option>
                <option value="factorial" <?= $operacion === 'factorial' ? 'selected' : '' ?>>Factorial</option>
            </select>

            <button type="submit">Calcular</button>
        </form>

        <?php if ($error !== ''): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($serie !== null): ?>
            <div class="resultado">
                Serie: <strong><?= implode(', ', $serie) ?></strong>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>

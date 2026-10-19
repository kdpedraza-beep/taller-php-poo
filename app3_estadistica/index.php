<?php
require_once '../includes/menu.php';
require_once 'Estadistica.php';

$menu = new Menu();
$entrada = '';
$error = '';
$cantidad = null;
$promedio = null;
$mediana = null;
$moda = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entrada = trim($_POST['numeros'] ?? '');
    $partes = preg_split('/[\s,;]+/', $entrada, -1, PREG_SPLIT_NO_EMPTY);

    $numeros = [];
    $valido = count($partes) > 0;

    foreach ($partes as $parte) {
        if (!is_numeric($parte)) {
            $valido = false;
            break;
        }
        $numeros[] = (float) $parte;
    }

    if (!$valido) {
        $error = 'Ingrese solo números, separados por espacios o comas. Los decimales van con punto.';
    } else {
        $estadistica = new Estadistica($numeros);
        $cantidad = count($numeros);
        $promedio = round($estadistica->promedio(), 2);
        $mediana = round($estadistica->mediana(), 2);
        $moda = $estadistica->moda();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promedio, mediana y moda</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?= $menu->render('../') ?>
    <main>
        <h1>Promedio, mediana y moda</h1>
        <form method="post">
            <label for="numeros">Números reales (los que desee):</label>
            <input type="text" id="numeros" name="numeros"
                   placeholder="Ejemplo: 4 8 8 15.5 23"
                   value="<?= htmlspecialchars($entrada) ?>" required>
            <button type="submit">Calcular</button>
        </form>

        <?php if ($error !== ''): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($promedio !== null): ?>
            <div class="resultado">
                Cantidad de números: <strong><?= $cantidad ?></strong><br>
                Promedio: <strong><?= $promedio ?></strong><br>
                Mediana: <strong><?= $mediana ?></strong><br>
                Moda: <strong><?= count($moda) > 0 ? implode(', ', $moda) : 'No hay moda' ?></strong>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
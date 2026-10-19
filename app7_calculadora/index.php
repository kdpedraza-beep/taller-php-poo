<?php
require_once '../includes/menu.php';
require_once 'Calculadora.php';
require_once 'Historial.php';

$menu = new Menu();
$historial = new Historial();

$a = '';
$b = '';
$operacion = 'suma';
$resultado = null;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (($_POST['accion'] ?? '') === 'borrar') {
        $historial->borrar();
    } else {
        $a = trim($_POST['a'] ?? '');
        $b = trim($_POST['b'] ?? '');
        $operacion = $_POST['operacion'] ?? 'suma';

        // Se acepta la coma como separador decimal
        $numeroA = str_replace(',', '.', $a);
        $numeroB = str_replace(',', '.', $b);

        if (!is_numeric($numeroA) || !is_numeric($numeroB)) {
            $error = 'Ingrese números enteros o decimales válidos.';
        } elseif ($operacion === 'division' && (float) $numeroB == 0) {
            $error = 'No se puede dividir entre cero.';
        } else {
            $calculadora = new Calculadora((float) $numeroA, (float) $numeroB);

            switch ($operacion) {
                case 'suma':
                    $resultado = $calculadora->sumar();
                    $texto = $a . ' + ' . $b;
                    break;
                case 'resta':
                    $resultado = $calculadora->restar();
                    $texto = $a . ' − ' . $b;
                    break;
                case 'multiplicacion':
                    $resultado = $calculadora->multiplicar();
                    $texto = $a . ' × ' . $b;
                    break;
                case 'division':
                    $resultado = $calculadora->dividir();
                    $texto = $a . ' ÷ ' . $b;
                    break;
                default:
                    $resultado = $calculadora->porcentaje();
                    $texto = $a . ' % de ' . $b;
                    break;
            }

            $resultado = round($resultado, 4);
            $historial->agregar($texto . ' = ' . $resultado);
        }
    }
}

// El historial más reciente se muestra primero
$operaciones = array_reverse($historial->obtener());
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calculadora</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?= $menu->render('../') ?>
    <main>
        <h1>Calculadora</h1>
        <form method="post">
            <label for="a">Primer número:</label>
            <input type="text" id="a" name="a" value="<?= htmlspecialchars($a) ?>" required>

            <label for="operacion">Operación:</label>
            <select id="operacion" name="operacion">
                <option value="suma" <?= $operacion === 'suma' ? 'selected' : '' ?>>Suma (+)</option>
                <option value="resta" <?= $operacion === 'resta' ? 'selected' : '' ?>>Resta (−)</option>
                <option value="multiplicacion" <?= $operacion === 'multiplicacion' ? 'selected' : '' ?>>Multiplicación (×)</option>
                <option value="division" <?= $operacion === 'division' ? 'selected' : '' ?>>División (÷)</option>
                <option value="porcentaje" <?= $operacion === 'porcentaje' ? 'selected' : '' ?>>Porcentaje (A % de B)</option>
            </select>

            <label for="b">Segundo número:</label>
            <input type="text" id="b" name="b" value="<?= htmlspecialchars($b) ?>" required>

            <button type="submit" name="accion" value="calcular">Calcular</button>
        </form>

        <?php if ($error !== ''): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($resultado !== null): ?>
            <div class="resultado">
                Resultado: <strong><?= $resultado ?></strong>
            </div>
        <?php endif; ?>

        <h2>Historial</h2>
        <?php if (count($operaciones) === 0): ?>
            <p>Aún no hay operaciones.</p>
        <?php else: ?>
            <ul class="historial">
                <?php foreach ($operaciones as $linea): ?>
                    <li><?= htmlspecialchars($linea) ?></li>
                <?php endforeach; ?>
            </ul>
            <form method="post">
                <button type="submit" name="accion" value="borrar">Borrar historial</button>
            </form>
        <?php endif; ?>
    </main>
</body>
</html>
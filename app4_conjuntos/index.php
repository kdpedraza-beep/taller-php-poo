<?php
require_once '../includes/menu.php';
require_once 'Conjuntos.php';

// Convierte el texto escrito en una lista de enteros (null si hay un error)
function leerEnteros(string $texto): ?array
{
    $partes = preg_split('/[\s,;]+/', trim($texto), -1, PREG_SPLIT_NO_EMPTY);
    if (count($partes) === 0) {
        return null;
    }

    $numeros = [];
    foreach ($partes as $parte) {
        $valor = filter_var($parte, FILTER_VALIDATE_INT);
        if ($valor === false) {
            return null;
        }
        $numeros[] = $valor;
    }
    return $numeros;
}

// Muestra un conjunto con llaves
function mostrar(array $conjunto): string
{
    if (count($conjunto) === 0) {
        return 'Conjunto vacío';
    }
    return '{ ' . implode(', ', $conjunto) . ' }';
}

$menu = new Menu();
$entradaA = '';
$entradaB = '';
$error = '';
$conjuntos = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entradaA = trim($_POST['conjunto_a'] ?? '');
    $entradaB = trim($_POST['conjunto_b'] ?? '');

    $listaA = leerEnteros($entradaA);
    $listaB = leerEnteros($entradaB);

    if ($listaA === null || $listaB === null) {
        $error = 'Ingrese solo números enteros, separados por espacios o comas.';
    } else {
        $conjuntos = new Conjuntos($listaA, $listaB);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operaciones entre conjuntos</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?= $menu->render('../') ?>
    <main>
        <h1>Operaciones entre conjuntos</h1>
        <form method="post">
            <label for="conjunto_a">Conjunto A:</label>
            <input type="text" id="conjunto_a" name="conjunto_a"
                   placeholder="Ejemplo: 1 2 3 4 5"
                   value="<?= htmlspecialchars($entradaA) ?>" required>

            <label for="conjunto_b">Conjunto B:</label>
            <input type="text" id="conjunto_b" name="conjunto_b"
                   placeholder="Ejemplo: 4 5 6 7"
                   value="<?= htmlspecialchars($entradaB) ?>" required>

            <button type="submit">Calcular</button>
        </form>

        <?php if ($error !== ''): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($conjuntos !== null): ?>
            <div class="resultado">
                A = <strong><?= mostrar($conjuntos->getA()) ?></strong><br>
                B = <strong><?= mostrar($conjuntos->getB()) ?></strong><br><br>
                Unión (A ∪ B): <strong><?= mostrar($conjuntos->union()) ?></strong><br>
                Intersección (A ∩ B): <strong><?= mostrar($conjuntos->interseccion()) ?></strong><br>
                Diferencia (A − B): <strong><?= mostrar($conjuntos->aMenosB()) ?></strong><br>
                Diferencia (B − A): <strong><?= mostrar($conjuntos->bMenosA()) ?></strong>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
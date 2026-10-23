<?php
require_once '../includes/menu.php';
require_once 'Serie.php';

$menu = new Menu();
$entrada = '';
$operacion = 'fibonacci';
$resultado = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['numero'])) {
        $entrada = $_POST['numero'];
    }
    if (isset($_POST['operacion'])) {
        $operacion = $_POST['operacion'];
    }

    $numero = (int) $entrada;

    if ($entrada === '') {
        $error = 'Ingrese un número.';
    } else if ($entrada != $numero || $numero < 1 || $numero > 20) {
        $error = 'Ingrese un número entero entre 1 y 20.';
    } else {
        $serie = new Serie($numero);
        if ($operacion == 'factorial') {
            $resultado = $serie->factorial();
        } else {
            $resultado = $serie->fibonacci();
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
    <?php echo $menu->render('../'); ?>
    <main>
        <h1>Sucesión de Fibonacci o factorial</h1>
        <form action="index.php" method="post">
            <label for="numero">Número (1 a 20):</label>
            <input type="number" id="numero" name="numero" min="1" max="20" value="<?php echo htmlspecialchars($entrada); ?>" required>

            <label for="operacion">Operación:</label>
            <select id="operacion" name="operacion">
                <option value="fibonacci" <?php if ($operacion == 'fibonacci') { echo 'selected'; } ?>>Sucesión de Fibonacci</option>
                <option value="factorial" <?php if ($operacion == 'factorial') { echo 'selected'; } ?>>Factorial</option>
            </select>

            <button type="submit">Calcular</button>
        </form>

        <?php
        if ($error !== '') {
            echo '<div class="error">' . $error . '</div>';
        }
        if ($resultado !== '') {
            echo '<div class="resultado">Serie: <strong>' . $resultado . '</strong></div>';
        }
        ?>
    </main>
</body>
</html>
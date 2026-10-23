<?php
require_once '../includes/menu.php';
require_once 'Binario.php';

$menu = new Menu();
$entrada = '';
$resultado = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['numero'])) {
        $entrada = $_POST['numero'];
    }

    if ($entrada === '') {
        $error = 'Ingrese un número.';
    } else if ($entrada != (int) $entrada) {
        $error = 'Ingrese un número entero, sin decimales.';
    } else {
        $binario = new Binario((int) $entrada);
        $resultado = $binario->convertir();
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
    <?php echo $menu->render('../'); ?>
    <main>
        <h1>Convertir un número entero a binario</h1>
        <form action="index.php" method="post">
            <label for="numero">Número entero:</label>
            <input type="number" id="numero" name="numero" step="1" value="<?php echo htmlspecialchars($entrada); ?>" required>
            <button type="submit">Convertir</button>
        </form>

        <?php
        if ($error !== '') {
            echo '<div class="error">' . $error . '</div>';
        }
        if ($resultado !== '') {
            echo '<div class="resultado">Binario: <strong>' . $resultado . '</strong></div>';
        }
        ?>
    </main>
</body>
</html>
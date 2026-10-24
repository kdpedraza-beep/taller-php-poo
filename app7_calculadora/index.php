<?php
require_once '../includes/menu.php';
require_once 'Calculadora.php';

$menu = new Menu();
$a = '';
$b = '';
$operacion = 'suma';
$resultado = '';
$error = '';
$historial = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // empieza vacío
    if (isset($_POST['historial']) && is_array($_POST['historial'])) {
        $historial = $_POST['historial'];
    }

    // Solo se calcula si llegaron los dos números
    if (isset($_POST['a']) && isset($_POST['b'])) {
        $a = $_POST['a'];
        $b = $_POST['b'];
        if (isset($_POST['operacion'])) {
            $operacion = $_POST['operacion'];
        }

        if ($a === '' || $b === '') {
            $error = 'Ingrese los dos números.';
        } else if ($operacion == 'division' && (float) $b == 0) {
            $error = 'No se puede dividir entre cero.';
        } else {
            $calculadora = new Calculadora((float) $a, (float) $b);

            switch ($operacion) {
                case 'suma':
                    $resultado = $calculadora->sumar();
                    $linea = $a . ' + ' . $b;
                    break;
                case 'resta':
                    $resultado = $calculadora->restar();
                    $linea = $a . ' - ' . $b;
                    break;
                case 'multiplicacion':
                    $resultado = $calculadora->multiplicar();
                    $linea = $a . ' × ' . $b;
                    break;
                case 'division':
                    $resultado = $calculadora->dividir();
                    $linea = $a . ' ÷ ' . $b;
                    break;
                default:
                    $resultado = $calculadora->porcentaje();
                    $linea = $a . ' % de ' . $b;
                    break;
            }

            $resultado = round($resultado, 4);
            $historial[] = $linea . ' = ' . $resultado;
        }
    }
}
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
    <?php echo $menu->render('../'); ?>
    <main>
        <h1>Calculadora</h1>

        <form action="index.php" method="post">
            <label for="a">Primer número:</label>
            <input type="number" step="any" id="a" name="a" value="<?php echo htmlspecialchars($a); ?>" required>

            <label for="operacion">Operación:</label>
            <select id="operacion" name="operacion">
                <option value="suma" <?php if ($operacion == 'suma') { echo 'selected'; } ?>>Suma (+)</option>
                <option value="resta" <?php if ($operacion == 'resta') { echo 'selected'; } ?>>Resta (-)</option>
                <option value="multiplicacion" <?php if ($operacion == 'multiplicacion') { echo 'selected'; } ?>>Multiplicación (×)</option>
                <option value="division" <?php if ($operacion == 'division') { echo 'selected'; } ?>>División (÷)</option>
                <option value="porcentaje" <?php if ($operacion == 'porcentaje') { echo 'selected'; } ?>>Porcentaje (A % de B)</option>
            </select>

            <label for="b">Segundo número:</label>
            <input type="number" step="any" id="b" name="b" value="<?php echo htmlspecialchars($b); ?>" required>

            <?php
            // Cada operación anterior viaja en un campo oculto
            foreach ($historial as $linea) {
                echo '<input type="hidden" name="historial[]" value="' . htmlspecialchars($linea) . '">';
            }
            ?>

            <button type="submit">Calcular</button>
        </form>

        <?php
        if ($error !== '') {
            echo '<div class="error">' . $error . '</div>';
        }
        if ($resultado !== '') {
            echo '<div class="resultado">Resultado: <strong>' . $resultado . '</strong></div>';
        }
        ?>

        <h2>Historial</h2>
        <?php
        if (count($historial) == 0) {
            echo '<p>Aún no hay operaciones.</p>';
        } else {
            // La operación más reciente se muestra primero
            echo '<ul class="historial">';
            for ($i = count($historial) - 1; $i >= 0; $i--) {
                echo '<li>' . htmlspecialchars($historial[$i]) . '</li>';
            }
            echo '</ul>';

            // Este formulario no envía el historial, por eso al usarlo queda vacío
            echo '<form action="index.php" method="post">';
            echo '<button type="submit" name="accion" value="borrar">Borrar historial</button>';
            echo '</form>';
        }
        ?>
    </main>
</body>
</html>
<?php
require_once '../includes/menu.php';
require_once 'Estadistica.php';

$menu = new Menu();
$cantidad = 0;
$valores = [];
$error = '';
$mostrarCampos = false;
$calculado = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['cantidad'])) {
        $cantidad = (int) $_POST['cantidad'];
    }

    if ($cantidad < 1 || $cantidad > 20) {
        $error = 'Ingrese una cantidad entre 1 y 20.';
        $cantidad = 0;
    } else if (!isset($_POST['numero0'])) {
        // Primer paso: ya se sabe la cantidad, faltan los números
        $mostrarCampos = true;
    } else {
        // llegaron los números
        $correcto = true;
        for ($i = 0; $i < $cantidad; $i++) {
            if (isset($_POST['numero' . $i]) && $_POST['numero' . $i] !== '') {
                $valores[] = (float) $_POST['numero' . $i];
            } else {
                $correcto = false;
            }
        }

        if ($correcto) {
            $estadistica = new Estadistica($valores);
            $promedio = $estadistica->promedio();
            $mediana = $estadistica->mediana();
            $moda = $estadistica->moda();
            $calculado = true;
        } else {
            $error = 'Complete todos los números.';
            $mostrarCampos = true;
        }
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
    <?php echo $menu->render('../'); ?>
    <main>
        <h1>Promedio, mediana y moda</h1>

        <form action="index.php" method="post">
            <label for="cantidad">¿Cuántos números va a ingresar? (1 a 20)</label>
            <input type="number" id="cantidad" name="cantidad" min="1" max="20" value="<?php if ($cantidad > 0) { echo $cantidad; } ?>" required>
            <button type="submit">Continuar</button>
        </form>

        <?php
        if ($error !== '') {
            echo '<div class="error">' . $error . '</div>';
        }

        if ($mostrarCampos) {
        ?>
            <form action="index.php" method="post">
                <input type="hidden" name="cantidad" value="<?php echo $cantidad; ?>">
                <?php for ($i = 0; $i < $cantidad; $i++) { ?>
                    <label for="numero<?php echo $i; ?>">Número <?php echo $i + 1; ?>:</label>
                    <input type="number" step="any" id="numero<?php echo $i; ?>" name="numero<?php echo $i; ?>" required>
                <?php } ?>
                <button type="submit">Calcular</button>
            </form>
        <?php
        }

        if ($calculado) {
            // los números ingresados
            $textoNumeros = '';
            for ($i = 0; $i < count($valores); $i++) {
                if ($i > 0) {
                    $textoNumeros = $textoNumeros . ', ';
                }
                $textoNumeros = $textoNumeros . $valores[$i];
            }

            // el texto de la moda
            if (count($moda) == 0) {
                $textoModa = 'No hay moda';
            } else {
                $textoModa = '';
                for ($i = 0; $i < count($moda); $i++) {
                    if ($i > 0) {
                        $textoModa = $textoModa . ', ';
                    }
                    $textoModa = $textoModa . $moda[$i];
                }
            }

            echo '<div class="resultado">';
            echo 'Números ingresados: ' . $textoNumeros . '<br>';
            echo 'Promedio: <strong>' . round($promedio, 2) . '</strong><br>';
            echo 'Mediana: <strong>' . round($mediana, 2) . '</strong><br>';
            echo 'Moda: <strong>' . $textoModa . '</strong>';
            echo '</div>';
        }
        ?>
    </main>
</body>
</html>
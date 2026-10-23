<?php
require_once '../includes/menu.php';
require_once 'Conjuntos.php';

// Convierte un conjunto en texto
function mostrar(array $conjunto): string
{
    if (count($conjunto) == 0) {
        return 'Conjunto vacío';
    }

    $texto = '{ ';
    for ($i = 0; $i < count($conjunto); $i++) {
        if ($i > 0) {
            $texto = $texto . ', ';
        }
        $texto = $texto . $conjunto[$i];
    }
    return $texto . ' }';
}

$menu = new Menu();
$cantidadA = 0;
$cantidadB = 0;
$error = '';
$mostrarCampos = false;
$calculado = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['cantidadA'])) {
        $cantidadA = (int) $_POST['cantidadA'];
    }
    if (isset($_POST['cantidadB'])) {
        $cantidadB = (int) $_POST['cantidadB'];
    }

    if ($cantidadA < 1 || $cantidadA > 10 || $cantidadB < 1 || $cantidadB > 10) {
        $error = 'Cada conjunto debe tener entre 1 y 10 elementos.';
        $cantidadA = 0;
        $cantidadB = 0;
    } else if (!isset($_POST['a0'])) {
        //ya se sabe la cantidad, faltan los números
        $mostrarCampos = true;
    } else {
        
        $correcto = true;
        $listaA = [];
        $listaB = [];

        for ($i = 0; $i < $cantidadA; $i++) {
            if (isset($_POST['a' . $i]) && $_POST['a' . $i] !== '' && $_POST['a' . $i] == (int) $_POST['a' . $i]) {
                $listaA[] = (int) $_POST['a' . $i];
            } else {
                $correcto = false;
            }
        }
        for ($i = 0; $i < $cantidadB; $i++) {
            if (isset($_POST['b' . $i]) && $_POST['b' . $i] !== '' && $_POST['b' . $i] == (int) $_POST['b' . $i]) {
                $listaB[] = (int) $_POST['b' . $i];
            } else {
                $correcto = false;
            }
        }

        if ($correcto) {
            $conjuntos = new Conjuntos($listaA, $listaB);
            $calculado = true;
        } else {
            $error = 'Complete todos los campos con números enteros.';
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
    <title>Operaciones entre conjuntos</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?php echo $menu->render('../'); ?>
    <main>
        <h1>Operaciones entre conjuntos</h1>

        <form action="index.php" method="post">
            <label for="cantidadA">Elementos del conjunto A (1 a 10):</label>
            <input type="number" id="cantidadA" name="cantidadA" min="1" max="10" value="<?php if ($cantidadA > 0) { echo $cantidadA; } ?>" required>

            <label for="cantidadB">Elementos del conjunto B (1 a 10):</label>
            <input type="number" id="cantidadB" name="cantidadB" min="1" max="10" value="<?php if ($cantidadB > 0) { echo $cantidadB; } ?>" required>

            <button type="submit">Continuar</button>
        </form>

        <?php
        if ($error !== '') {
            echo '<div class="error">' . $error . '</div>';
        }

        if ($mostrarCampos) {
        ?>
            <form action="index.php" method="post">
                <input type="hidden" name="cantidadA" value="<?php echo $cantidadA; ?>">
                <input type="hidden" name="cantidadB" value="<?php echo $cantidadB; ?>">

                <h2>Conjunto A</h2>
                <?php for ($i = 0; $i < $cantidadA; $i++) { ?>
                    <label for="a<?php echo $i; ?>">Elemento <?php echo $i + 1; ?>:</label>
                    <input type="number" step="1" id="a<?php echo $i; ?>" name="a<?php echo $i; ?>" required>
                <?php } ?>

                <h2>Conjunto B</h2>
                <?php for ($i = 0; $i < $cantidadB; $i++) { ?>
                    <label for="b<?php echo $i; ?>">Elemento <?php echo $i + 1; ?>:</label>
                    <input type="number" step="1" id="b<?php echo $i; ?>" name="b<?php echo $i; ?>" required>
                <?php } ?>

                <button type="submit">Calcular</button>
            </form>
        <?php
        }

        if ($calculado) {
            echo '<div class="resultado">';
            echo 'A = <strong>' . mostrar($conjuntos->getA()) . '</strong><br>';
            echo 'B = <strong>' . mostrar($conjuntos->getB()) . '</strong><br><br>';
            echo 'Unión (A ∪ B): <strong>' . mostrar($conjuntos->union()) . '</strong><br>';
            echo 'Intersección (A ∩ B): <strong>' . mostrar($conjuntos->interseccion()) . '</strong><br>';
            echo 'Diferencia (A − B): <strong>' . mostrar($conjuntos->aMenosB()) . '</strong><br>';
            echo 'Diferencia (B − A): <strong>' . mostrar($conjuntos->bMenosA()) . '</strong>';
            echo '</div>';
        }
        ?>
    </main>
</body>
</html>
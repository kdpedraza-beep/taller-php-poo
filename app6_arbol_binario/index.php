<?php
require_once '../includes/menu.php';
require_once 'ArbolBinario.php';

// Indica si un valor está dentro de una lista
function contiene(array $lista, string $valor): bool
{
    foreach ($lista as $elemento) {
        if ($elemento === $valor) {
            return true;
        }
    }
    return false;
}

// Indica si dos listas son iguales y en el mismo orden
function iguales(array $x, array $y): bool
{
    if (count($x) != count($y)) {
        return false;
    }
    for ($i = 0; $i < count($x); $i++) {
        if ($x[$i] !== $y[$i]) {
            return false;
        }
    }
    return true;
}

// Une los elementos de una lista con flechas: A → B → C
function unir(array $lista): string
{
    $texto = '';
    for ($i = 0; $i < count($lista); $i++) {
        if ($i > 0) {
            $texto = $texto . ' → ';
        }
        $texto = $texto . $lista[$i];
    }
    return $texto;
}

$menu = new Menu();
$titulos = ['pre' => 'Preorden', 'in' => 'Inorden', 'post' => 'Postorden'];
$cantidad = 0;
$filas = ['pre' => [], 'in' => [], 'post' => []];
$error = '';
$mostrarCampos = false;
$arbol = null;
$metodo = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['cantidad'])) {
        $cantidad = (int) $_POST['cantidad'];
    }

    if ($cantidad < 1 || $cantidad > 10) {
        $error = 'Ingrese una cantidad de nodos entre 1 y 10.';
        $cantidad = 0;
    } else {
        $mostrarCampos = true;

        // Se leen las tres filas; cada una puede venir completa o vacía
        $llenos = ['pre' => 0, 'in' => 0, 'post' => 0];
        foreach ($titulos as $nombre => $titulo) {
            for ($i = 0; $i < $cantidad; $i++) {
                $valor = '';
                if (isset($_POST[$nombre . $i])) {
                    $valor = trim($_POST[$nombre . $i]);
                }
                $filas[$nombre][] = $valor;
                if ($valor !== '') {
                    $llenos[$nombre]++;
                }
            }
        }

        // Solo se construye cuando se presiona el botón del segundo formulario
        if (isset($_POST['construir'])) {

            $completa = [];
            $totalCompletas = 0;
            foreach ($titulos as $nombre => $titulo) {
                $completa[$nombre] = ($llenos[$nombre] == $cantidad);
                if ($completa[$nombre]) {
                    $totalCompletas++;
                }
                if ($llenos[$nombre] > 0 && $llenos[$nombre] < $cantidad) {
                    $error = 'Cada recorrido debe estar completo o totalmente vacío.';
                }
            }

            if ($error === '' && $totalCompletas < 2) {
                $error = 'Ingrese al menos dos recorridos completos.';
            }

            if ($error === '') {
                // Se eligen los dos recorridos que se van a usar
                if ($completa['pre'] && $completa['in']) {
                    $usados = ['pre', 'in'];
                    $metodo = 'preorden e inorden';
                } else if ($completa['post'] && $completa['in']) {
                    $usados = ['post', 'in'];
                    $metodo = 'postorden e inorden';
                } else {
                    $usados = ['pre', 'post'];
                    $metodo = 'preorden y postorden';
                }

                $uno = $filas[$usados[0]];
                $dos = $filas[$usados[1]];

                // Los nodos no se pueden repetir dentro de un recorrido
                for ($i = 0; $i < $cantidad; $i++) {
                    for ($j = $i + 1; $j < $cantidad; $j++) {
                        if ($uno[$i] === $uno[$j] || $dos[$i] === $dos[$j]) {
                            $error = 'Los nodos no se pueden repetir dentro de un recorrido.';
                        }
                    }
                }

                // Los dos recorridos deben tener los mismos nodos
                for ($i = 0; $i < $cantidad; $i++) {
                    if (!contiene($dos, $uno[$i])) {
                        $error = 'Los recorridos deben tener los mismos nodos.';
                    }
                }
            }

            if ($error === '') {
                $arbol = new ArbolBinario();

                if ($metodo == 'preorden e inorden') {
                    $construido = $arbol->desdePreIn($filas['pre'], $filas['in']);
                } else if ($metodo == 'postorden e inorden') {
                    $construido = $arbol->desdePostIn($filas['post'], $filas['in']);
                } else {
                    $construido = $arbol->desdePrePost($filas['pre'], $filas['post']);
                }

                // Se comprueba que el árbol construido genere los recorridos ingresados
                if (!$construido) {
                    $error = 'Los recorridos ingresados no corresponden a un mismo árbol.';
                } else {
                    foreach ($usados as $nombre) {
                        if ($nombre == 'pre') {
                            $calculado = $arbol->preorden();
                        } else if ($nombre == 'in') {
                            $calculado = $arbol->inorden();
                        } else {
                            $calculado = $arbol->postorden();
                        }
                        if (!iguales($calculado, $filas[$nombre])) {
                            $error = 'Los recorridos ingresados no corresponden a un mismo árbol.';
                        }
                    }
                }

                if ($error !== '') {
                    $arbol = null;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Árbol binario</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?php echo $menu->render('../'); ?>
    <main>
        <h1>Árbol binario a partir de sus recorridos</h1>

        <form action="index.php" method="post">
            <label for="cantidad">¿Cuántos nodos tiene el árbol? (1 a 10)</label>
            <input type="number" id="cantidad" name="cantidad" min="1" max="10" value="<?php if ($cantidad > 0) { echo $cantidad; } ?>" required>
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
                <p>Escriba al menos dos recorridos completos y deje vacío el que no vaya a usar. Si escribe los tres, se usan preorden e inorden.</p>

                <?php
                foreach ($titulos as $nombre => $titulo) {
                    echo '<h2>' . $titulo . '</h2>';
                    echo '<div class="fila-nodos">';
                    for ($i = 0; $i < $cantidad; $i++) {
                        $valor = '';
                        if (isset($filas[$nombre][$i])) {
                            $valor = $filas[$nombre][$i];
                        }
                        echo '<input type="text" name="' . $nombre . $i . '" value="' . htmlspecialchars($valor) . '" maxlength="3">';
                    }
                    echo '</div>';
                }
                ?>

                <button type="submit" name="construir" value="1">Construir árbol</button>
            </form>
        <?php
        }

        if ($arbol !== null) {
            $inorden = $arbol->inorden();
            $niveles = $arbol->niveles();

            // La posición horizontal de cada nodo es su lugar en el inorden
            $columna = [];
            $ultimoNivel = 0;
            for ($i = 0; $i < count($inorden); $i++) {
                $columna[$inorden[$i]] = $i;
                if ($niveles[$inorden[$i]] > $ultimoNivel) {
                    $ultimoNivel = $niveles[$inorden[$i]];
                }
            }

            $ancho = count($inorden) * 60 + 20;
            $alto = ($ultimoNivel + 1) * 70 + 10;

            echo '<div class="resultado">Árbol construido con: <strong>' . $metodo . '</strong></div>';

            echo '<div class="dibujo-arbol">';
            echo '<svg width="' . $ancho . '" height="' . $alto . '">';

            // Primero las líneas entre cada nodo y sus hijos
            foreach ($inorden as $nodo) {
                $x = 40 + $columna[$nodo] * 60;
                $y = 30 + $niveles[$nodo] * 70;

                $hijos = [$arbol->getIzquierda($nodo), $arbol->getDerecha($nodo)];
                foreach ($hijos as $hijo) {
                    if ($hijo !== '') {
                        $x2 = 40 + $columna[$hijo] * 60;
                        $y2 = 30 + $niveles[$hijo] * 70;
                        echo '<line x1="' . $x . '" y1="' . $y . '" x2="' . $x2 . '" y2="' . $y2 . '"></line>';
                    }
                }
            }

            // Después los círculos con el nombre de cada nodo
            foreach ($inorden as $nodo) {
                $x = 40 + $columna[$nodo] * 60;
                $y = 30 + $niveles[$nodo] * 70;
                echo '<circle cx="' . $x . '" cy="' . $y . '" r="20"></circle>';
                echo '<text x="' . $x . '" y="' . $y . '" dy="5">' . htmlspecialchars($nodo) . '</text>';
            }

            echo '</svg>';
            echo '</div>';

            echo '<div class="resultado">';
            echo 'Preorden: <strong>' . unir($arbol->preorden()) . '</strong><br>';
            echo 'Inorden: <strong>' . unir($arbol->inorden()) . '</strong><br>';
            echo 'Postorden: <strong>' . unir($arbol->postorden()) . '</strong>';
            echo '</div>';
        }
        ?>
    </main>
</body>
</html>
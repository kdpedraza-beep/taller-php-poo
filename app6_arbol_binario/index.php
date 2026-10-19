<?php
require_once '../includes/menu.php';
require_once 'ArbolBinario.php';

// Convierte el texto escrito en una lista de nodos (null si el campo está vacío)
function leerRecorrido(string $texto): ?array
{
    $partes = preg_split('/[\s,;→]+/u', trim($texto), -1, PREG_SPLIT_NO_EMPTY);
    if (count($partes) === 0) {
        return null;
    }
    return $partes;
}

// Dos listas tienen los mismos nodos, sin importar el orden
function mismosNodos(array $x, array $y): bool
{
    sort($x, SORT_STRING);
    sort($y, SORT_STRING);
    return $x === $y;
}

// Dibuja un nodo y sus hijos como lista anidada 
function dibujar(Nodo $nodo): string
{
    $html = '<li><span class="nodo">' . htmlspecialchars($nodo->valor) . '</span>';

    if ($nodo->izquierda !== null || $nodo->derecha !== null) {
        $vacio = '<li><span class="nodo vacio">–</span></li>';
        $html .= '<ul>';
        $html .= $nodo->izquierda !== null ? dibujar($nodo->izquierda) : $vacio;
        $html .= $nodo->derecha !== null ? dibujar($nodo->derecha) : $vacio;
        $html .= '</ul>';
    }

    return $html . '</li>';
}

$menu = new Menu();
$entradaPre = '';
$entradaIn = '';
$entradaPost = '';
$error = '';
$arbol = null;
$metodo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entradaPre = trim($_POST['preorden'] ?? '');
    $entradaIn = trim($_POST['inorden'] ?? '');
    $entradaPost = trim($_POST['postorden'] ?? '');

    $pre = leerRecorrido($entradaPre);
    $in = leerRecorrido($entradaIn);
    $post = leerRecorrido($entradaPost);

    if (count(array_filter([$pre, $in, $post])) < 2) {
        $error = 'Ingrese al menos dos recorridos.';
    } else {
        // Si se ingresan los tres, se usan preorden e inorden
        if ($pre !== null && $in !== null) {
            $post = null;
            $metodo = 'preorden e inorden';
        } elseif ($post !== null && $in !== null) {
            $pre = null;
            $metodo = 'inorden y postorden';
        } else {
            $in = null;
            $metodo = 'preorden y postorden';
        }

        $usados = array_values(array_filter([$pre, $in, $post]));

        $repetidos = false;
        foreach ($usados as $recorrido) {
            if (count(array_unique($recorrido)) !== count($recorrido)) {
                $repetidos = true;
            }
        }

        if ($repetidos) {
            $error = 'Los nodos no se pueden repetir dentro de un recorrido.';
        } elseif (!mismosNodos($usados[0], $usados[1])) {
            $error = 'Los recorridos deben tener los mismos nodos.';
        } else {
            $arbol = new ArbolBinario();
            $arbol->construir($pre, $in, $post);

            // Se comprueba que el árbol construido genere los recorridos ingresados
            $coincide = true;
            if ($pre !== null && $arbol->preorden() !== $pre) {
                $coincide = false;
            }
            if ($in !== null && $arbol->inorden() !== $in) {
                $coincide = false;
            }
            if ($post !== null && $arbol->postorden() !== $post) {
                $coincide = false;
            }

            if (!$coincide) {
                $error = 'Los recorridos ingresados no corresponden a un mismo árbol.';
                $arbol = null;
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
    <?= $menu->render('../') ?>
    <main>
        <h1>Árbol binario a partir de sus recorridos</h1>
        <form method="post">
            <p>Ingrese al menos dos recorridos, con los nodos separados por espacios o comas.</p>

            <label for="preorden">Preorden:</label>
            <input type="text" id="preorden" name="preorden"
                   placeholder="Ejemplo: A B D E C"
                   value="<?= htmlspecialchars($entradaPre) ?>">

            <label for="inorden">Inorden:</label>
            <input type="text" id="inorden" name="inorden"
                   placeholder="Ejemplo: D B E A C"
                   value="<?= htmlspecialchars($entradaIn) ?>">

            <label for="postorden">Postorden:</label>
            <input type="text" id="postorden" name="postorden"
                   placeholder="Ejemplo: D E B C A"
                   value="<?= htmlspecialchars($entradaPost) ?>">

            <button type="submit">Construir árbol</button>
        </form>

        <?php if ($error !== ''): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($arbol !== null): ?>
            <div class="resultado">
                Árbol construido con: <strong><?= $metodo ?></strong>
            </div>

            <div class="arbol">
                <ul><?= dibujar($arbol->getRaiz()) ?></ul>
            </div>

            <div class="resultado">
                Preorden: <strong><?= implode(' → ', $arbol->preorden()) ?></strong><br>
                Inorden: <strong><?= implode(' → ', $arbol->inorden()) ?></strong><br>
                Postorden: <strong><?= implode(' → ', $arbol->postorden()) ?></strong>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
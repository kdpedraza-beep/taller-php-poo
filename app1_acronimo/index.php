<?php
require_once '../includes/menu.php';
require_once 'Acronimo.php';

$menu = new Menu();
$frase = '';
$resultado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $frase = trim($_POST['frase'] ?? '');
    if ($frase !== '') {
        $resultado = (new Acronimo($frase))->generar();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acrónimo</title>
    <link rel="stylesheet" href="../css/estilos.css">
</head>
<body>
    <?= $menu->render('../') ?>
    <main>
        <h1>Convertir una frase en acrónimo</h1>
        <form method="post">
            <label for="frase">Frase:</label><br>
            <input type="text" id="frase" name="frase" size="50"
                   value="<?= htmlspecialchars($frase) ?>" required><br>
            <button type="submit">Convertir</button>
        </form>

        <?php if ($resultado !== null): ?>
            <div class="resultado">
                Acrónimo: <strong><?= htmlspecialchars($resultado) ?></strong>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
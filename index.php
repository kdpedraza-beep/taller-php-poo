<?php require_once 'includes/menu.php'; $menu = new Menu(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taller PHP</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <?= $menu->render() ?>
    <main>
        <h1>Taller PHP con POO</h1>
        <p>Seleccione una de las aplicaciones:</p>
        <div class="tarjetas">
            <?php foreach ($menu->getOpciones() as $carpeta => $titulo): ?>
                <a class="tarjeta" href="<?= $carpeta ?>/index.php"><?= $titulo ?></a>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>
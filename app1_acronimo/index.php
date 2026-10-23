<?php
require_once '../includes/menu.php';
require_once 'Acronimo.php';

$menu = new Menu();
$frase = '';
$resultado = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['frase'])) {
        $frase = $_POST['frase'];
        $acronimo = new Acronimo($frase);
        $resultado = $acronimo->generar();
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
    <?php echo $menu->render('../'); ?>
    <main>
        <h1>Convertir una frase en acrónimo</h1>
        <form action="index.php" method="post">
            <label for="frase">Frase:</label><br>
            <input type="text" id="frase" name="frase" size="50" value="<?php echo htmlspecialchars($frase); ?>" required><br>
            <button type="submit">Convertir</button>
        </form>

        <?php
        if ($resultado != '') {
            echo '<div class="resultado">Acrónimo: <strong>' . htmlspecialchars($resultado) . '</strong></div>';
        }
        ?>
    </main>
</body>
</html>
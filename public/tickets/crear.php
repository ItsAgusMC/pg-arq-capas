<?php

declare(strict_types=1);

/**
 * CAPA DE PRESENTACIÓN
 *
 * Muestra el formulario, recibe los datos, inicia la creación del Ticket
 * y muestra el resultado. No contiene SQL ni decide el estado del Ticket.
 */

require_once __DIR__ . '/../../negocio/Ticket.php';
require_once __DIR__ . '/../../datos/Conexion.php';
require_once __DIR__ . '/../../datos/TicketRepository.php';

function e(string $texto): string
{
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}

$titulo = '';
$descripcion = '';
$mensaje = null;
$exito = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = (string) ($_POST['titulo'] ?? '');
    $descripcion = (string) ($_POST['descripcion'] ?? '');

    try {
        $ticket = Ticket::nuevo($titulo, $descripcion);          // negocio
        $repositorio = new TicketRepository(Conexion::obtener());
        $repositorio->guardar($ticket);                          // persistencia

        $exito = true;
        $mensaje = sprintf(
            'Ticket #%d creado correctamente con estado "%s".',
            $ticket->getId(),
            $ticket->getEstado()
        );
        $titulo = $descripcion = '';
    } catch (InvalidArgumentException $ex) {
        $mensaje = $ex->getMessage();
    } catch (PDOException $ex) {
        error_log($ex->getMessage());
        $mensaje = Conexion::describirError($ex);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alta de Ticket</title>
    <link rel="icon" href="data:,">
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="../js/crear.js" defer></script>
</head>
<body>
    <main class="tarjeta">
        <h1>Alta de Ticket</h1>

        <?php if ($mensaje !== null): ?>
            <p class="mensaje <?= $exito ? 'ok' : 'error' ?>"><?= e($mensaje) ?></p>
        <?php endif; ?>
        <p id="error-cliente" class="mensaje error oculto"></p>

        <form id="form-ticket" method="post" action="">
            <label for="titulo">Título</label>
            <input type="text" id="titulo" name="titulo" required
                   maxlength="<?= Ticket::TITULO_MAX ?>" value="<?= e($titulo) ?>">
            <div id="contador-titulo" class="contador"></div>

            <label for="descripcion">Descripción</label>
            <textarea id="descripcion" name="descripcion" required><?= e($descripcion) ?></textarea>

            <button type="submit">Crear Ticket</button>
        </form>

        <p><a href="../index.html">Volver al inicio</a></p>
    </main>
</body>
</html>

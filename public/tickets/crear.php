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
        $mensaje = 'No se pudo guardar el Ticket. Intente nuevamente más tarde.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alta de Ticket</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 560px; margin: 2rem auto; padding: 0 1rem; }
        label { display: block; margin-top: 1rem; font-weight: 600; }
        input, textarea { width: 100%; padding: .5rem; box-sizing: border-box; font: inherit; }
        textarea { min-height: 120px; }
        button { margin-top: 1rem; padding: .6rem 1.2rem; font: inherit; cursor: pointer; }
        .mensaje { padding: .75rem 1rem; border-radius: 6px; }
        .ok { background: #e6f4ea; color: #1e5631; }
        .error { background: #fdecea; color: #8a1c1c; }
    </style>
</head>
<body>
    <h1>Alta de Ticket</h1>

    <?php if ($mensaje !== null): ?>
        <p class="mensaje <?= $exito ? 'ok' : 'error' ?>"><?= e($mensaje) ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label for="titulo">Título</label>
        <input type="text" id="titulo" name="titulo" required
               maxlength="<?= Ticket::TITULO_MAX ?>" value="<?= e($titulo) ?>">

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" required><?= e($descripcion) ?></textarea>

        <button type="submit">Crear Ticket</button>
    </form>
</body>
</html>

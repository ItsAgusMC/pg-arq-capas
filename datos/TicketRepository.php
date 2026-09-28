<?php

declare(strict_types=1);

require_once __DIR__ . '/../negocio/Ticket.php';

/**
 * CAPA DE PERSISTENCIA
 *
 * Responsable exclusivamente de guardar Tickets en la base de datos.
 * Todo el SQL de la tabla "ticket" vive aquí (alta cohesión):
 * si cambia la base de datos, sólo cambia esta clase.
 */
class TicketRepository
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Inserta el Ticket y le asigna el identificador generado.
     * Guarda el estado que trae el Ticket: no decide ninguna regla de negocio.
     */
    public function guardar(Ticket $ticket): int
    {
        $sql = 'INSERT INTO ticket (titulo, descripcion, estado)
                VALUES (:titulo, :descripcion, :estado)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':titulo' => $ticket->getTitulo(),
            ':descripcion' => $ticket->getDescripcion(),
            ':estado' => $ticket->getEstado(),
        ]);

        $id = (int) $this->pdo->lastInsertId();
        $ticket->asignarId($id);

        return $id;
    }
}

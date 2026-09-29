<?php

declare(strict_types=1);

require_once __DIR__ . '/../negocio/Ticket.php';

class TicketRepository
{
    public function __construct(private PDO $pdo)
    {
    }

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

<?php

declare(strict_types=1);

class Ticket
{
    public const ESTADO_PENDIENTE = 'pendiente';

    public const TITULO_MAX = 150;

    private ?int $id = null;
    private string $titulo;
    private string $descripcion;
    private string $estado;

    private function __construct(string $titulo, string $descripcion, string $estado)
    {
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->estado = $estado;
    }

    public static function nuevo(string $titulo, string $descripcion): self
    {
        $titulo = trim($titulo);
        $descripcion = trim($descripcion);

        if ($titulo === '') {
            throw new InvalidArgumentException('El título es obligatorio.');
        }
        if (mb_strlen($titulo) > self::TITULO_MAX) {
            throw new InvalidArgumentException('El título no puede superar los ' . self::TITULO_MAX . ' caracteres.');
        }
        if ($descripcion === '') {
            throw new InvalidArgumentException('La descripción es obligatoria.');
        }

        return new self($titulo, $descripcion, self::ESTADO_PENDIENTE);
    }

    public function asignarId(int $id): void
    {
        if ($this->id !== null) {
            throw new LogicException('El Ticket ya tiene un identificador asignado.');
        }
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }
}

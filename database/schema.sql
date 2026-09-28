-- Base de datos para el ejercicio "Alta de Ticket"
CREATE DATABASE IF NOT EXISTS tickets_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE tickets_db;

-- Nota (SSOT): la columna estado NO tiene DEFAULT 'pendiente'.
-- El estado inicial es una regla de negocio y vive sólo en la clase Ticket.
CREATE TABLE IF NOT EXISTS ticket (
  id          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  titulo      VARCHAR(150)  NOT NULL,
  descripcion TEXT          NOT NULL,
  estado      VARCHAR(20)   NOT NULL,
  creado_en   TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
);

-- Datos de prueba
INSERT INTO ticket (titulo, descripcion, estado) VALUES
  ('No funciona la impresora', 'La impresora del segundo piso no imprime.', 'pendiente'),
  ('Alta de usuario', 'Crear usuario de correo para el nuevo empleado.', 'pendiente'),
  ('Error al iniciar sesión', 'El sistema muestra "clave incorrecta" con la clave correcta.', 'pendiente');

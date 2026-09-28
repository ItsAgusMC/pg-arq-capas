# Alta de Ticket – Arquitectura en tres capas (PHP)

Aplicación web mínima que permite registrar un **Ticket** (título y descripción) en una base de datos MySQL.
Todo Ticket nuevo comienza automáticamente en estado **pendiente**.

## Estructura

```
pg-arq-capas/
├── public/tickets/crear.php      → Capa de PRESENTACIÓN
├── negocio/Ticket.php            → Capa de NEGOCIO
├── datos/
│   ├── Conexion.php              → Capa de PERSISTENCIA (conexión PDO)
│   └── TicketRepository.php      → Capa de PERSISTENCIA (INSERT)
├── config/
│   ├── config.php                → Configuración (lee credenciales fuera del repo)
│   └── config.local.example.php  → Plantilla de credenciales
└── database/schema.sql           → Tabla ticket + datos de prueba
```

## Cómo ejecutarlo

1. Crear la base y la tabla: `mysql -u root -p < database/schema.sql`
2. Copiar `config/config.local.example.php` como `config/config.local.php` y completar usuario y clave
   (también se pueden usar las variables de entorno `DB_DSN`, `DB_USUARIO`, `DB_CLAVE`).
3. Levantar el servidor: `php -S localhost:8000 -t public`
4. Abrir <http://localhost:8000/tickets/crear.php>

> `config/config.local.php` está en `.gitignore`: las credenciales nunca se suben al repositorio.

## Comentario: ¿qué hace cada capa?

**Presentación (`public/tickets/crear.php`)**: muestra el formulario, recibe `titulo` y `descripcion`,
pide al negocio que cree el Ticket, pide a la persistencia que lo guarde y muestra un mensaje de éxito o error.
No contiene SQL ni decide el estado.

**Negocio (`negocio/Ticket.php`)**: la clase `Ticket` representa el concepto y sus reglas:
título y descripción obligatorios, largo máximo del título y estado inicial `pendiente`.
El constructor es privado; la única forma de crear un Ticket nuevo es `Ticket::nuevo()`,
por lo que es imposible que nazca con otro estado. No conoce ni la base de datos ni el HTML.

**Persistencia (`datos/`)**: `Conexion` crea la conexión PDO en un único lugar y
`TicketRepository` ejecuta el `INSERT` con una consulta preparada.

### ¿Por qué el INSERT está en `TicketRepository`?

Porque guardar datos es responsabilidad de la persistencia. Si el SQL estuviera en el formulario,
la pantalla quedaría **acoplada** a la estructura de la tabla y cualquier otra pantalla que
creara Tickets tendría que **repetir** la consulta (viola DRY). Al concentrarlo en el repositorio,
esa clase tiene **alta cohesión** (sólo habla con la tabla `ticket`) y un cambio en la base de datos
se resuelve en un solo archivo.

### ¿Por qué `pendiente` es una regla de negocio y no del formulario?

Porque es una política del sistema, no un dato que el usuario elija. Según el principio **Experto**,
quien tiene la información para decidir cómo nace un Ticket es el propio `Ticket`.
Si lo decidiera el formulario (por ejemplo, con un campo oculto), un usuario podría alterarlo
y cada nueva forma de crear Tickets (otra pantalla, una API, un script) tendría que acordarse de repetirlo.
Estando en `Ticket::nuevo()` se respeta siempre, venga de donde venga el alta.

### Principios aplicados

| Principio | Dónde se ve |
|---|---|
| **Experto** | `Ticket` valida sus datos y define su estado inicial; `TicketRepository` sabe cómo guardarlo. |
| **Bajo acoplamiento** | La presentación no conoce SQL; `Ticket` no conoce PDO; el repositorio recibe el `PDO` por constructor. |
| **Alta cohesión** | Cada clase tiene una sola responsabilidad: mostrar, representar reglas o persistir. |
| **DRY** | La conexión se crea sólo en `Conexion`; el `INSERT` existe sólo en `TicketRepository`. |
| **SSOT** | El estado `pendiente` se define sólo en `Ticket::ESTADO_PENDIENTE` (la tabla no tiene `DEFAULT`); el largo máximo del título sale de `Ticket::TITULO_MAX` y el formulario lo reutiliza; la configuración vive sólo en `config/`. |

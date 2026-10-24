# Evidencia 2

## Learning Outcome 2

El estudiante desarrolla una aplicación web con buenas prácticas de programación. En esta evidencia se implementó la lógica funcional de Halcon Web Application mediante Laravel 7.30.7, PHP 7.4.29 y MariaDB.

## Continuidad de Evidencia 1

La Evidencia 2 se desarrolló en el mismo proyecto y repositorio de Evidencia 1. Se conservaron sus diagramas, historial Git y decisiones de diseño. La implementación utiliza las entidades definidas originalmente: Role, User, Customer, Order, Material, OrderDetail y EvidencePhoto.

Evidencia 1 no definió una entidad Department ni una entidad Process. Por esa razón no se añadieron tablas o campos de departamento/proceso por suposición. Los usuarios se asignan a los roles originales y, para una orden `In Process`, la consulta pública presenta el estado `In Process` como proceso actual.

## Models

| Modelo | Relaciones Eloquent |
| --- | --- |
| `Role` | `hasMany(User)` |
| `User` | `belongsTo(Role)`, `hasMany(Order, created_by)`, `hasMany(EvidencePhoto, uploaded_by)` |
| `Customer` | `hasMany(Order)` |
| `Material` | `hasMany(OrderDetail)` |
| `Order` | `belongsTo(Customer)`, `belongsTo(User, created_by)`, `hasMany(OrderDetail)`, `hasMany(EvidencePhoto)` y `SoftDeletes` |
| `OrderDetail` | `belongsTo(Order)`, `belongsTo(Material)` |
| `EvidencePhoto` | `belongsTo(Order)`, `belongsTo(User, uploaded_by)` |

Los estados de orden son exactamente `Ordered`, `In Process`, `In Route` y `Delivered`. Los tipos de evidencia son `Loaded` y `Delivery`.

## Migrations

Todas las entidades de dominio usan una llave primaria `id` de tipo unsigned big integer.

| Tabla | Llaves foráneas y restricciones principales |
| --- | --- |
| `roles` | `name` único |
| `users` | `role_id` → `roles.id`; `email` único; `is_active` booleano |
| `customers` | `customer_number` único |
| `materials` | Existencias en `stock` |
| `orders` | `customer_id` → `customers.id`; `created_by` → `users.id`; `invoice_number` único; índice por estado/fecha; `deleted_at` |
| `order_details` | `order_id` → `orders.id` con cascade; `material_id` → `materials.id`; combinación orden/material única |
| `evidence_photos` | `order_id` → `orders.id` con cascade; `uploaded_by` → `users.id`; índice por orden/tipo |

Las migraciones se ejecutaron en el orden requerido por sus dependencias y todas aparecen aplicadas en `php artisan migrate:status`.

## Controllers

- `HomeController`: muestra el Home público y busca una orden por `invoice_number`.
- `DashboardController`: resume usuarios activos, órdenes abiertas y órdenes archivadas.
- `UserController`: listado, creación y edición de usuarios, roles y estado activo/inactivo.
- `OrderController`: listado descendente por fecha, creación, vista individual, edición, cambio de estado, carga de evidencia y archivado lógico.
- `ArchivedOrderController`: listado y vista de órdenes archivadas, además de su restauración.
- `LoginController`: restringe el inicio de sesión a usuarios activos.

## Seeders / Factories

`DatabaseSeeder` ejecuta `RoleSeeder` y `DemoDataSeeder`. Los datos se crean mediante `updateOrCreate`, por lo que el flujo es reproducible.

- Cinco roles del diseño original: Administrator, Sales, Purchasing, Warehouse y Route.
- Dos usuarios activos y un usuario Route inactivo.
- Dos clientes y dos materiales de demostración.
- Una orden de cada estado: Ordered, In Process, In Route y Delivered.
- Una orden In Process archivada lógicamente.
- Una evidencia local segura para In Route y una para Delivered.

Las imágenes demo se generan localmente con GD; no se descargan archivos de Internet. No se requirió una factory adicional porque los seeders cubren todos los estados solicitados con datos deterministas.

## Home público

La ruta `/` es pública y muestra un formulario de búsqueda por número de factura. La ruta GET `/track-order` valida `invoice_number` en el servidor y devuelve la orden activa correspondiente.

- Para `In Process`, muestra información básica, proceso actual y fecha.
- Para `Delivered`, muestra información básica, estado, fecha y fotografía de entrega.
- Una factura inexistente produce un mensaje de validación claro.
- Las órdenes archivadas no aparecen en la búsqueda pública.

## Registered users

Las rutas administrativas se agrupan bajo middleware `auth`. Un invitado es redirigido al login si intenta acceder a Dashboard, Users, Orders o Archived Orders. El registro público se encuentra deshabilitado y las contraseñas se guardan mediante hash.

### Dashboard

`/dashboard` muestra enlaces funcionales a Users, Orders y Archived Orders, con conteos básicos.

### Users

`/users` incluye usuarios activos e inactivos y muestra nombre, email, rol y estado. Es posible crear y editar usuarios, asignar uno de los roles definidos en Evidencia 1 y activar o inactivar la cuenta sin eliminarla. No se añadió Department porque no existe en el diseño original.

### Orders

`/orders` lista las órdenes desde la más reciente hasta la más antigua por `order_date`. El formulario permite crear y editar una orden con cliente, material, cantidad, fecha, dirección, notas y uno de los cuatro estados válidos. La vista individual presenta sus relaciones y evidencias.

### Archived Orders

`/archived-orders` muestra exclusivamente órdenes con eliminación lógica. Permite consultar cada orden archivada y restaurarla; al restaurar, desaparece del archivo y vuelve al listado normal.

## Evidence photographs

Las evidencias se almacenan en `storage/app/public/order-evidence`, y la base de datos conserva únicamente `file_path`. El enlace público se crea con `php artisan storage:link`.

El servidor acepta JPG, JPEG, PNG o WebP de hasta 5 MB. Solo permite evidencia cuando la orden está `In Route` o `Delivered`; el tipo guardado es `Loaded` o `Delivery`, respectivamente. No se admiten ejecutables ni blobs en la base de datos.

## Logical deletion

`Order` utiliza `Illuminate\Database\Eloquent\SoftDeletes` y la tabla `orders` contiene `deleted_at`. La acción Archive ejecuta eliminación lógica; los listados normales y la búsqueda pública excluyen esas órdenes. No se implementó eliminación física.

## Testing

Se ejecutaron 22 pruebas automatizadas con 56 assertions y sin fallos. Cubren:

1. Home público.
2. Búsqueda válida In Process.
3. Búsqueda Delivered con evidencia.
4. Búsqueda inexistente.
5. Protección de Dashboard para invitados.
6. Protección de Users para invitados.
7. Protección de Orders para invitados.
8. Acceso autenticado al Dashboard.
9. Creación de usuario.
10. Edición de usuario.
11. Inactivación de usuario.
12. Orden descendente del listado.
13. Creación de orden.
14. Actualización de orden.
15. Cambio de estado.
16. Carga de evidencia válida.
17. Vista individual de orden.
18. Archivado lógico.
19. Exclusión del listado normal.
20. Aparición en Archived Orders.
21. Restauración.
22. Regreso de la orden restaurada al listado normal.

## Evidencias

Las siguientes capturas se generaron desde la aplicación local en ejecución:

1. [Home público](evidencias/evidencia-2/01_home_search.png)
2. [Consulta In Process](evidencias/evidencia-2/02_search_in_process.png)
3. [Consulta Delivered](evidencias/evidencia-2/03_search_delivered.png)
4. [Dashboard](evidencias/evidencia-2/04_dashboard.png)
5. [Listado de usuarios](evidencias/evidencia-2/05_users_list.png)
6. [Creación de usuario](evidencias/evidencia-2/06_user_create.png)
7. [Edición de usuario](evidencias/evidencia-2/07_user_edit.png)
8. [Listado de órdenes](evidencias/evidencia-2/08_orders_list.png)
9. [Creación de orden](evidencias/evidencia-2/09_order_create.png)
10. [Actualización de orden](evidencias/evidencia-2/10_order_update.png)
11. [Evidencia In Route](evidencias/evidencia-2/11_in_route_evidence.png)
12. [Evidencia Delivered](evidencias/evidencia-2/12_delivered_evidence.png)
13. [Vista individual](evidencias/evidencia-2/13_order_show.png)
14. [Órdenes archivadas](evidencias/evidencia-2/14_archived_orders.png)
15. [Orden restaurada](evidencias/evidencia-2/15_order_restored.png)

Los resultados de migraciones, rutas y pruebas se documentan con la salida real de los comandos. No se añadieron imágenes simuladas de terminal.

## GitHub

Repositorio: <https://github.com/AlejandroVela333/Halcon-Web-Application>

## Conclusion

Evidencia 2 convierte el diseño de Evidencia 1 en una aplicación Laravel funcional: conserva el dominio original, implementa persistencia relacional, autenticación, validación, carga segura de evidencias, eliminación lógica y pruebas automatizadas. Los flujos públicos y administrativos quedaron comprobados sobre una base de desarrollo reproducible.

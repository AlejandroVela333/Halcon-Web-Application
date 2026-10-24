\# Halcon Web Application



\## Project Description



Halcon Web Application is a web system designed for a construction material distributor. Its purpose is to help the company manage customer orders and allow customers to check the current status of their orders.



The application will provide an administrative dashboard for company employees and a public order tracking screen for customers.



\## Objective



To develop a web application that allows Halcon to manage the complete life cycle of customer orders, from the moment an order is registered until it is delivered.



\## Users and Roles



The system will include the following roles:



\- \*\*Administrator:\*\* Registers users and assigns roles.

\- \*\*Sales:\*\* Registers customer orders.

\- \*\*Purchasing:\*\* Manages the purchase of materials that are not available in stock.

\- \*\*Warehouse:\*\* Checks stock, prepares orders and reports missing or low-stock materials.

\- \*\*Route:\*\* Manages order delivery and uploads photographic evidence.

\- \*\*Customer:\*\* Consults the status of an order using a customer number and invoice number.



Customers will not have user accounts in the system.



\## Order Statuses



An order can have the following statuses:



1\. \*\*Ordered:\*\* The order has been registered by Sales.

2\. \*\*In Process:\*\* The order is being prepared or missing materials are being purchased.

3\. \*\*In Route:\*\* The order has been prepared and sent for delivery.

4\. \*\*Delivered:\*\* The order has been delivered to the customer.



\## Main Features



\- Customer order tracking using customer number and invoice number.

\- User and role management.

\- Order registration.

\- Order status management.

\- Stock verification.

\- Management of missing materials.

\- Upload of photographic evidence by Route personnel.

\- Search orders by:

&#x20; - Invoice number

&#x20; - Customer number

&#x20; - Date

&#x20; - Status

\- Logical deletion of orders.

\- Display and restoration of logically deleted orders.



\## Planned Technologies



\- PHP

\- Laravel

\- MySQL

\- XAMPP

\- Git

\- GitHub



\## Project Documentation



The project design will include:



\- BPMN Diagram

\- Class Diagram

\- Activity Diagram

\- Use Case Diagram

\- Entity-Relationship Diagram



\## Repository



This repository will contain the source code and documentation developed for the Halcon Web Application project.

## Evidence 2

Evidence 2 implements the application logic on the same project and repository created for Evidence 1. The original diagrams and Git history remain intact.

- **Models and relationships:** Role, User, Customer, Material, Order, OrderDetail, and EvidencePhoto with their Eloquent relationships.
- **Migrations:** primary keys, foreign keys, unique constraints, indexes, timestamps, and `SoftDeletes` for orders.
- **Controllers:** separate public tracking, dashboard, user management, order management, and archived-order responsibilities.
- **Seeders:** reproducible roles, active/inactive users, customers, materials, all four order states, one archived order, and safe local evidence images.
- **Public invoice search:** guests can track active orders by invoice number without authentication. In Process results show the current process/date; Delivered results include delivery evidence.
- **Authentication:** administrative routes are protected by `auth`, public registration is disabled, and inactive users cannot log in.
- **Dashboard:** authenticated links to Users, Orders, and Archived Orders.
- **Users:** list, create, edit, assign roles, and mark accounts active or inactive.
- **Orders:** list newest first, create, view, edit, change status, and attach evidence for In Route or Delivered orders.
- **Evidence photographs:** validated JPG, PNG, or WebP files up to 5 MB are stored in Laravel public storage; only the path is persisted.
- **Logical deletion:** orders use `SoftDeletes` and are never physically removed by the Archive action.
- **Archived Orders:** archived orders can be viewed and restored.
- **Tests:** 22 automated tests with 56 assertions cover the requested public, authenticated, CRUD, evidence, archive, and restore flows.

The complete technical record and real application screenshots are available in [docs/EVIDENCIA_2.md](docs/EVIDENCIA_2.md).


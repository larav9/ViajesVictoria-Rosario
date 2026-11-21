# Viajes Vic-Ros

Plataforma web desarrollada en **CodeIgniter 4** para la publicación, búsqueda y gestión de viajes compartidos entre **Victoria (Entre Ríos)** y **Rosario (Santa Fe)**, conectando de forma directa a choferes con pasajeros por medio de whatsapp.

---

## Documentación y Diagramas

Para consultar la documentación técnica y funcional completa del proyecto, dirigirse a la carpeta **`documentos/`** del repositorio, donde se encuentran disponibles:

* **Manual de Usuario e Instalación:** Guía detallada paso a paso para el despliegue del entorno local y el uso del sistema según cada rol (Invitado, Pasajero y Chofer).
* **Diagrama Entidad-Relación (DER):** Modelo relacional de la base de datos `viajes_rosario`.
* **Diagrama de Casos de Uso:** Especificación de actores, permisos e interacciones principales del sistema.

---

## Funcionalidades Principales

* **Cartelera Pública de Viajes:** Visualización de viajes diarios y eventuales con información de horarios, vehículo asignado, capacidad disponible, precio base y recargos opcionales por zona.
* **Contacto Directo y Registro de Consultas:** Integración con WhatsApp que registra cada solicitud de contacto en la base de datos antes de abrir el chat con el chofer.
* **Panel de Gestión para Choferes:** Administración integral mediante ventanas modales para **Mis Viajes** (con soporte de días recurrentes o fechas puntuales), **Mis Vehículos** (datos de unidad y seguro) y **Mis Zonas** (recargos personalizados).
* **Reportes y Auditoría:** Tablero estadístico interactivo con gráficos de demanda por día de la semana y franja horaria, historial de consultas recibidas y registro automático de movimientos sobre los viajes.
* **Mapa Interactivo de Rutas:** Planificador visual con cálculo de recorrido y paradas intermedias entre Victoria y Rosario.
* **Gestión de Perfil y Seguridad:** Registro con validación de formato internacional de WhatsApp, contraseñas encriptadas (`password_hash`) con política de seguridad y edición de datos personales desde modal.

---

## Tecnologías e Inspiraciones Utilizadas

* **Backend:** PHP 8.2, CodeIgniter 4 
* **Base de Datos:** MySQL / MariaDB (`viajes_rosario`).
* **Frontend:** HTML5, CSS3 personalizado, JavaScript, jQuery y AJAX 
* **Librerías e Integraciones:** Chart.js, DataTables, Leaflet con OpenRouteService / Leaflet Routing Machine y API de enlaces directos de WhatsApp.
* **Diseños de inspiracion:** me inspire en esta página de diseños:[text](https://codepen.io/alice-mx/pen/LmxKqp) para el login y registro. Para el hover de las cards :[text](https://codemyui.com/blog-archive-image-card-hover-animation/).


## Mejoras Futuras y Próximas Implementaciones


* **Perfil Administrador:** Creación de un rol administrativo con panel propio para moderar cuentas, gestionar altas/bajas de usuarios, supervisar publicaciones.
* **Auditoría Integral del Sistema:** Ampliación del módulo de auditoría actual (centrado en los movimientos de viajes) para registrar trazabilidad completa sobre todas las entidades y acciones críticas (usuarios, vehículos, zonas, consultas e inicios de sesión). Ademas de tener una seccion para consultar la auditoria, actualmente solo esta disponible en la bbdd.
* **Guardado de Mapas y Rutas:** Persistencia en base de datos de los recorridos y paradas intermedias trazados desde el planificador, permitiendo asociarlos a cada viaje y visualizarlos directamente en la cartelera.
* **Filtros de Búsqueda en Cartelera:** Incorporación de filtros dinámicos 
* **Recuperación de Contraseña:** Implementación de un flujo de restablecimiento de clave mediante envío de enlaces o códigos temporales por correo electrónico.
* **Integración con Bot de WhatsApp para Gestión de Reservas:** Automatización mediante webhooks y WhatsApp Cloud API para que, una vez iniciada la consulta desde la cartelera, el pasajero y el chofer puedan confirmar el acuerdo desde el chat y transformar esa consulta en una reserva formal con actualización automática de cupos disponibles.

Realizado por Lara Vidoni 2026
Institución: Gaspar L.Benavento
Carrera: Tecnicatura Superior en Analisis y Desarrollo de Software
Docente: Delfor Castro
---------
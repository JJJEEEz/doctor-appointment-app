# Actividad 4 - Panel Administrativo con Laravel y Flowbite

Este proyecto es una implementación de un panel administrativo desarrollado en Laravel. La interfaz se construyó utilizando Blade para la estructura y la librería de componentes **Flowbite** para el diseño, cumpliendo con los requisitos de la Actividad 4 de la Unidad 1. El objetivo principal es demostrar el uso de layouts, slots y la integración de componentes de UI en un entorno Laravel.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Características y Cambios Implementados

### 1. Localización al Español
- **Paquete de Idioma**: Se integró el paquete `laravel-lang/common` para facilitar la traducción.
- **Configuración**: Se estableció el español (`es`) como el idioma predeterminado en `config/app.php` y el archivo `.env`.
- **Resultado**: Toda la interfaz de la aplicación, incluyendo formularios y mensajes de validación, se muestra ahora en español.

### 2. Configuración Regional
- **Zona Horaria**: Se configuró la zona horaria a `America/Merida` en `config/app.php`. Esto asegura que todas las fechas y horas (`timestamps`) se registren y muestren correctamente según la hora local del sureste de México.

### 3. Base de Datos MySQL
- **Conexión**: Se configuró el proyecto para conectarse a una base de datos MySQL, actualizando las credenciales en el archivo `.env`.
- **Migraciones**: Se ejecutó `php artisan migrate` para crear la estructura de tablas necesaria en la base de datos.

### 4. Funcionalidad de Foto de Perfil
- **Habilitación**: Se activó la función de carga de fotos de perfil de Jetstream.
- **Almacenamiento**: Se configuró el disco `public` como el sistema de archivos predeterminado (`FILESYSTEM_DISK=public`) para que las imágenes sean accesibles públicamente.
- **Resultado**: Los usuarios pueden subir y actualizar su foto de perfil desde su panel de cuenta.

## Implementación del Panel Administrativo

### 1. Creación de Layout Personalizado
- Se generó un componente de layout para el panel de administración con `php artisan make:component AdminLayout`.
- Se definió una nueva ruta para el dashboard administrativo:
  ```php
  Route::get('/admin', function () {
      return view('admin.dashboard');
  })->name('admin.dashboard');
  ```
- Se creó una vista de prueba en `resources/views/admin/dashboard.blade.php` para utilizar el nuevo layout:
  ```blade
  <x-admin-layout>
      Hola desde admin
  </x-admin-layout>
  ```

### 2. Integración de Flowbite
- Se instaló Flowbite a través de npm:
  ```bash
  npm install flowbite
  ```
- Se configuró `tailwind.config.js` para incluir los estilos y scripts de Flowbite:
  ```js
  module.exports = {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./node_modules/flowbite/**/*.js"
    ],
    plugins: [
      require('flowbite/plugin')
    ],
  }
  ```
- Se compiló el frontend con `npm run build`.
- Se adaptó un ejemplo de la documentación de Flowbite (Sidebar con Navbar) para crear la estructura principal del layout `admin.blade.php`.

### 3. Modularización de Vistas
- Para mantener el código organizado, el `navbar` y el `sidebar` se separaron en archivos independientes:
  - `resources/views/includes/navigation.blade.php`
  - `resources/views/includes/sidebar.blade.php`
- Estos componentes se integraron en el layout principal utilizando la directiva `@include`.

### 4. Contenido Dinámico
- Se utilizó la variable `{{ $slot }}` en el layout para inyectar dinámicamente el contenido de cada página.
- Se incorporó la información del usuario autenticado (nombre y foto de perfil) en el menú desplegable del navbar para una experiencia de usuario personalizada.

---

## 🗂️ Tablero de Trabajo — GitHub Projects

Se configuró un tablero de trabajo digital utilizando **GitHub Projects** para organizar y dar seguimiento a las tareas del proyecto.

El tablero está estructurado como **Kanban** e incluye las siguientes columnas:

- 📝 Pendiente
- 🔧 En proceso
- 🔍 En revisión
- ✅ Finalizado

En el tablero se registraron **10 actividades (issues)** relacionadas con el desarrollo del sistema:

- **5 actividades en Backlog (Pendiente)**
- **5 actividades marcadas como Finalizadas (Done)**

Estas actividades representan tareas reales del proyecto y permiten visualizar el progreso durante el curso.

---

## 🔗 Enlaces del Proyecto

- 📁 **Repositorio del proyecto:**  
  👉 _(agrega aquí el enlace de tu repositorio público)_

- 🗂️ **Tablero de GitHub Projects:**  
  👉 _(opcional: enlace directo al tablero Kanban)_


## Sobre Laravel

Laravel es un framework de aplicaciones web con una sintaxis expresiva y elegante, diseñado para hacer el desarrollo una experiencia creativa y agradable. Facilita tareas comunes como:

- Un motor de enrutamiento simple y rápido.
- Un potente contenedor de inyección de dependencias.
- Múltiples backends para almacenamiento de sesión y caché.
- Un ORM de base de datos expresivo e intuitivo.
- Migraciones de esquema agnósticas a la base de datos.
- Procesamiento robusto de trabajos en segundo plano.
- Transmisión de eventos en tiempo real.

Para más información, puedes consultar la [documentación oficial de Laravel](https://laravel.com/docs).

# UPTASK - Proyecto de Gestión de Tareas en PHP (MVC)

Este es un proyecto desarrollado como parte de mi portafolio profesional. Es una aplicación web para la gestión de proyectos y tareas con autenticación, panel de control y consumo de APIs, todo construido con PHP y JavaScript bajo una arquitectura MVC.

## 🚀 Características

- Sistema de autenticación (registro, login, recuperación de contraseña)
- Gestión de proyectos y tareas
- Interfaz dinámica con JavaScript y SCSS
- Consumo de APIs para tareas
- Arquitectura MVC desde cero
- Uso de Composer y NPM para gestión de dependencias y tareas

## 🧱 Tecnologías utilizadas

- PHP 8+
- JavaScript
- HTML5 + SCSS (compilado con Gulp)
- Composer
- MySQL
- Gulp.js

## 📂 Estructura principal
- classes/ # Clases auxiliares
- controllers/ # Controladores MVC
- includes/ # Configuración base
- models/ # Modelos de datos
- views/ # Vistas (auth, dashboard, templates)
- public/ # Punto de entrada (index.php, assets)
- src/ # Archivos fuente (JS/SCSS)


## ⚙️ Instalación local

1. Clona el repositorio:
   ```bash
   git clone https://github.com/MaryelLB/UPTASK_MVC.git
   cd UPTASK_MVC

2. Instala las dependencias de PHP y Node.js:
    ```bash
    composer install
    npm install

4. Construye los assets (JS/SCSS):
   ```bash
   npm run dev

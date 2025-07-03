#!/bin/bash

# Script de despliegue para Azure App Service
echo "🚀 Iniciando despliegue de Farmacia Magistral..."

# Instalar dependencias de producción
echo "📦 Instalando dependencias..."
composer install --optimize-autoloader --no-dev --no-interaction

# Generar clave de aplicación si no existe
echo "🔑 Configurando aplicación..."
php artisan key:generate --force

# Limpiar cachés
echo "🧹 Limpiando cachés..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ejecutar migraciones
echo "🗄️ Ejecutando migraciones..."
php artisan migrate --force

# Ejecutar seeders
echo "🌱 Ejecutando seeders..."
php artisan db:seed --class=CategoriaSeeder --force
php artisan db:seed --class=MarcaSeeder --force  
php artisan db:seed --class=UserSeeder --force
php artisan db:seed --class=ProductosPruebaSeeder --force

# Cachear configuraciones para producción
echo "⚡ Optimizando para producción..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Crear enlaces simbólicos para storage
echo "🔗 Configurando storage..."
php artisan storage:link

echo "✅ Despliegue completado exitosamente!" 
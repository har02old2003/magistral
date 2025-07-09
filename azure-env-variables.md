# Variables de Entorno para Azure App Service

Configura estas variables en: App Service → Configuración → Variables de aplicación

```
APP_NAME=Farmacia Magistral
APP_ENV=production
APP_KEY=base64:qj/2hzq1qS0kaoeCC6ZSNplk9oD2Bhki04Cg/UfWR1o=
APP_DEBUG=false
APP_URL=https://farmacia-sistema-2025.azurewebsites.net

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=[TU-SERVIDOR-MYSQL].mysql.database.azure.com
DB_PORT=3306
DB_DATABASE=farmacia_db
DB_USERNAME=farmaciaadmin
DB_PASSWORD=[TU_CONTRASEÑA_MYSQL]

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

# Variables adicionales recomendadas para Azure
SCM_DO_BUILD_DURING_DEPLOYMENT=true
WEBSITES_ENABLE_APP_SERVICE_STORAGE=false
```

## Reemplazar:
- `[TU-APP-NAME]` con el nombre de tu App Service
- `[TU-SERVIDOR-MYSQL]` con el nombre de tu servidor MySQL
- `[TU_CONTRASEÑA_MYSQL]` con la contraseña que configuraste 
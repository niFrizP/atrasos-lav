# Ruta de PHP en el servidor (ajústala si es diferente)
PHP_BIN="/usr/bin/php"

# Ruta del proyecto en el servidor
PROJECT_PATH="/home/lavcauqu/public_html/atrasos.lavcauquenes"

# Archivo de logs para verificar la salida de los comandos
LOG_FILE="/home/lavcauqu/cron_output.log"

# Ejecutar migraciones (para asegurarse de que la BD está actualizada)
$PHP_BIN $PROJECT_PATH/artisan migrate --force >> $LOG_FILE 2>&1

# Ejecutar tareas de Artisan adicionales si las tienes en artisan.sh
$PHP_BIN $PROJECT_PATH/artisan.sh >> $LOG_FILE 2>&1

# Enviar el resultado del cron por correo (opcional)
cat $LOG_FILE | mail -s "Resultado Artisan" c11nfp@gmail.com

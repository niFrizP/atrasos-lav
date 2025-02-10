# Configuración
$MigrationPath = "lav_db/migrations/"
$DatabaseName = "lav_db"   # 🔹 Reemplázalo con el nombre real de tu BD
$MySQLPath = "D:\xampp\mysql\bin\mysql.exe" # 🔹 Ruta del ejecutable de MySQL en XAMPP
$User = "root"  # 🔹 Usuario de MySQL
$Password = ""  # 🔹 Si tienes contraseña, colócala aquí (si no, déjala vacía)

# Comando SQL para obtener migraciones ordenadas por batch
$MySQLCommand = "SELECT migration, batch FROM migrations ORDER BY batch, migration;"

# Ejecutar el comando en MySQL y obtener las migraciones en PowerShell
$MigrationsFromDB = & $MySQLPath -u $User --password=$Password -D $DatabaseName -e $MySQLCommand 2>$null | ForEach-Object { ($_ -split '\t') } | Select-Object -Skip 1

# Verificar si hay migraciones
if (!$MigrationsFromDB) {
    Write-Host "❌ No se encontraron migraciones en la base de datos." -ForegroundColor Red
    exit
}

# Obtener archivos de migración en la carpeta local
$LocalMigrations = Get-ChildItem -Path $MigrationPath -Filter "*.php" | Sort-Object Name

# Diccionario para asignar nombres basados en batch
$Index = 1
$MigrationMap = @{}

foreach ($MigrationRow in $MigrationsFromDB) {
    $MigrationName = $MigrationRow[0]
    $BatchID = $MigrationRow[1]

    # Buscar el archivo correspondiente en la carpeta
    $OldFile = $LocalMigrations | Where-Object { $_.Name -match [regex]::Escape($MigrationName) }

    if ($OldFile) {
        # Crear el nuevo nombre con índice
        $NewName = "{0:D4}_{1}" -f $Index, $OldFile.Name

        # Verificar que no exista un archivo con ese nombre
        if (-not $MigrationMap.ContainsValue($NewName)) {
            # Renombrar archivo
            Rename-Item -Path $OldFile.FullName -NewName $NewName
            $MigrationMap[$MigrationName] = $NewName
            Write-Host "✅ Renombrado: $MigrationName → $NewName"
            $Index++
        }
    }
}

Write-Host "🚀 Migraciones renombradas correctamente en base al batch ID" -ForegroundColor Green
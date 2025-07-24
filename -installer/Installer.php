<?php
namespace MiMFa;
class Installer
{
    public static $Configurations = [];
    public static $ConfigurationsFile = 'installer.json';
    public static $DataBaseSchemaFile = 'schema.sql';

    public static function Install()
    {
        self::LoadConfig();
        if (self::ConstructDataBase(false) !== false)
            echo "✅ DataBase installation is completed successfully.\n";
        if (self::ConstructSource(true) !== false)
            echo "✅ Source installation is completed successfully.\n";
        self::StoreConfig();
        echo "✅ Finished!\n";
    }

    public static function Update()
    {
        self::LoadConfig();
        if (self::ConstructDataBase(true) !== false)
            echo "✅ DataBase update is completed successfully.\n";
        if (self::ConstructSource(false) !== false)
            echo "✅ Source update is completed successfully.\n";
        self::StoreConfig();
        echo "\n✅ FINISHED -----------------\n";
    }

    public static function ConstructDataBase($force = true)
    {
        echo "\nDATABASE SETUP -----------------\n";
        if (!isset(self::$Configurations["DataBase"]))
            self::$Configurations["DataBase"] = [];

        $host = ($force ? self::$Configurations["DataBase"]["Host"]??null : null) ?? (readline("Host [" . (self::$Configurations["DataBase"]["Host"] ?? "localhost") . "]: ") ?: 'localhost');
        $name = ($force ? self::$Configurations["DataBase"]["Name"]??null : null) ?? (readline("Database name [" . (self::$Configurations["DataBase"]["Name"] ?? "localhost") . "]: ") ?: 'localhost');
        if (empty($name)) {
            echo "❌ Database name required.\n";
            return false;
        }
        $username = ($force ? self::$Configurations["DataBase"]["Username"]??null : null) ?? (readline("usernamename [" . (self::$Configurations["DataBase"]["Username"] ?? "root") . "]: ") ?: 'root');
        $password = ($force ? self::$Configurations["DataBase"]["Password"]??null : null) ?? (readline("passwordword [" . (self::$Configurations["DataBase"]["Password"] ?? "root") . "]: ") ?: 'root');
        $prefix = ($force ? self::$Configurations["DataBase"]["Prefix"]??null : null) ?? (readline("Table prefix [" . (self::$Configurations["DataBase"]["Prefix"] ?? "aseq_") . "]: ") ?: 'aseq_');

        self::$Configurations["DataBase"]["Host"] = $host;
        self::$Configurations["DataBase"]["Name"] = $name;
        self::$Configurations["DataBase"]["Username"] = $username;
        self::$Configurations["DataBase"]["Password"] = $password;
        self::$Configurations["DataBase"]["Prefix"] = $prefix;

        self::$DataBaseSchemaFile = self::$Configurations["DataBase"]["SchemaFile"]??self::$DataBaseSchemaFile;
        $sqlFile = __DIR__ . DIRECTORY_SEPARATOR . self::$DataBaseSchemaFile; // Your base schema
        if (!file_exists($sqlFile)) {
            echo "⚠️ There is no database schema to install!\n";
            return null;
        }

        // try {
        //     $pdo = new \PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
        //     $pdo->exec("CREATE DATABASE IF NOT EXISTS `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        // } catch (\PDOException $e) {
        //     echo "❌ Could not create DataBase: " . $e->getMessage() . "\n";
        // }

        try {
            $pdo = new \PDO("mysql:host=$host;charset=".(self::$Configurations["DataBase"]["Charset"]??"utf8mb4"), $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            echo "📦 Server is Connected: $host\n";

            $schema = file_get_contents($sqlFile);
            $schema = str_replace('%%DATABASE%%', $name, $schema);
            $schema = str_replace('%%PREFIX%%', $prefix, $schema);
            echo "📦 Schema file read successfully!\n";

            $pdo->exec($schema);
            echo "✅ All tables created successfully!\n";
            return true;
        } catch (\PDOException $e) {
            echo "❌ Connection failed: " . $e->getMessage() . "\n";
            return false;
        }
    }

    public static function ConstructSource($force = true)
    {
        echo "\nSOURCE SETUP -----------------\n";
        if (!isset(self::$Configurations["Source"]))
            self::$Configurations["Source"] = [];

        $base = getcwd() . DIRECTORY_SEPARATOR;                    // Project root (destination)
        $source = dirname(__DIR__) . DIRECTORY_SEPARATOR;            // Source folder (your framework package root)
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        $sourceExcludePattern = self::$Configurations["Source"]["ExcludePattern"]??"/^.*(composer\.(json|lock))$/i";
        $dirPermission = self::$Configurations["Source"]["DirectoryPermission"]??0755;

        $i = 0;
        foreach ($iterator as $item) {
            $relPath = substr($item->getPathname(), strlen($source));
            $targetPath = $base . $relPath;

            if ($item->isDir()) {
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, $dirPermission, true);
                }
            } elseif (!preg_match($sourceExcludePattern, $targetPath) && $item !== $targetPath) {
                $shouldCopy = $force || !file_exists($targetPath) || filemtime($item) > filemtime($targetPath);
                if ($shouldCopy) {
                    // Create folder if it doesn't exist
                    $targetDir = dirname($targetPath);
                    if (!is_dir($targetDir)) {
                        mkdir($targetDir, $dirPermission, true);
                    }

                    copy($item, $targetPath);
                    $i++;
                    echo "📦 Copied: '$relPath'\n";
                }
            }
        }

        echo "✅ $i source files are copied.\n";
    }

    public static function LoadConfig()
    {
        try {
            $configFile = __DIR__ . DIRECTORY_SEPARATOR . self::$ConfigurationsFile;
            if (file_exists($configFile)) {
                self::$Configurations = json_decode(file_get_contents($configFile), flags: JSON_OBJECT_AS_ARRAY) ?: [];
                echo "✅ Configurations loaded successfully!\n";
                return self::$Configurations;
            }
        } catch (\PDOException $e) {
            echo "⚠️ Could not loaded configurations: " . $e->getMessage() . "\n";
        }
        return self::$Configurations = [];
    }
    public static function StoreConfig()
    {
        try {
            $configFile = __DIR__ . DIRECTORY_SEPARATOR . self::$ConfigurationsFile;
            if ($res = file_put_contents($configFile, json_encode(self::$Configurations, flags: JSON_OBJECT_AS_ARRAY))) {
                echo "✅ Configurations stored successfully!\n";
                return $res;
            }
        } catch (\PDOException $e) {
            echo "⚠️ Could not store configurations: " . $e->getMessage() . "\n";
        }
    }
}
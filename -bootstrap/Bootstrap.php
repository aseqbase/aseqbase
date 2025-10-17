<?php
namespace MiMFa;
class Bootstrap
{
    public static $Configurations = [];
    public static $ConfigurationsFile = 'Bootstrap.json';
    public static $DataBaseSchemaFile = 'schema.sql';
    public static $DestinationDirectory = null;


    public static function Start()
    {
        self::LoadConfig();
        if (!isset(self::$Configurations["Origin"]))
            self::$Configurations["Origin"] = [];
        [$host, $port] = explode(":", isset(self::$Configurations["Origin"]["Host"]) ? self::$Configurations["Origin"]["Host"] . ":" : "localhost:8000");
        $host = $host ?: "localhost";
        $port = $port ?: "8000";
        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            exec("start /B php -S $host:$port");
            exec('net start MySQL');
        } else {
            exec("php -S $host:$port > /dev/null 2>&1 &");
            exec('sudo service mysql start');
        }
        self::$Configurations["Origin"]["Host"] = $host;
        self::$Configurations["Origin"]["Port"] = $port;
        self::StoreConfig();
    }

    public static function Install()
    {
        self::LoadConfig();
        if (self::ConstructSource(false) !== false)
            echo "✅ Source installation is completed successfully.\n";
        if (self::ConstructDataBase(false) !== false)
            echo "✅ DataBase installation is completed successfully.\n";
        if (self::ConstructSetting(false) !== false)
            echo "✅ Setting installation is completed successfully.\n";
        self::StoreConfig();
        echo "\n✅ FINISHED -----------------\n";
    }

    public static function Update()
    {
        self::LoadConfig();
        if (self::ConstructSource(true) !== false)
            echo "✅ Source update is completed successfully.\n";
        if (self::ConstructDataBase(true) !== false)
            echo "✅ DataBase update is completed successfully.\n";
        if (self::ConstructSetting(true) !== false)
            echo "✅ Setting update is completed successfully.\n";
        self::StoreConfig();
        echo "\n✅ FINISHED -----------------\n";
    }

    public static function Uninstall()
    {
        if (strtolower(readline(("Are you sure to start uninstallation process (y/N)?") . "") ?: "n") === "y") {
            self::LoadConfig();
            if (strtolower(readline(("Are you sure about removing all files and folders permanently (y/N)?") . "") ?: "n") === "y") {
                if (self::DestructSource(false) !== false)
                    echo "✅ Source uninstallation is completed successfully.\n";
            }
            if (strtolower(readline(("Are you sure about removing all tables permanently (y/N)?") . "") ?: "n") === "y") {
                if (self::DestructDataBase(false) !== false)
                    echo "✅ DataBase uninstallation is completed successfully.\n";
            }
            self::StoreConfig();
            echo "\n✅ FINISHED -----------------\n";
        }
    }

    public static function ConstructSource($force = true)
    {
        echo "\nSOURCE INSTALLATION -----------------\n";
        self::$DestinationDirectory = $force ? self::$DestinationDirectory : (readline("Destination Directory [" . self::$DestinationDirectory . "]: ") ?: self::$DestinationDirectory); // Project root (destination)
        if (!str_ends_with(self::$DestinationDirectory, DIRECTORY_SEPARATOR))
            self::$DestinationDirectory .= DIRECTORY_SEPARATOR;

        $source = dirname(__DIR__) . DIRECTORY_SEPARATOR;// Source folder (your framework package root)
        if (self::$DestinationDirectory === $source) {
            echo "✅ All files are ready.\n";
            self::$Configurations["Destination"]["Root"] = self::$DestinationDirectory;
        } else {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::SELF_FIRST
            );

            $sourceExcludePattern = self::$Configurations["Source"]["ExcludePattern"] ?? "/^.*(composer\.(json|lock))$/i";
            $dirPermission = self::$Configurations["Source"]["DirectoryPermission"] ?? 0755;

            $i = 0;
            $rl = strlen($source);
            foreach ($iterator as $item) {
                $relPath = substr($item->getPathname(), $rl);
                if (
                    str_starts_with($relPath, "~") ||
                    str_starts_with($relPath, ".git" . DIRECTORY_SEPARATOR) ||
                    str_starts_with($relPath, "vendor" . DIRECTORY_SEPARATOR)
                )
                    continue;
                $targetPath = self::$DestinationDirectory . $relPath;

                if ($item->isDir()) {
                    if (!is_dir($targetPath)) {
                        mkdir($targetPath, $dirPermission, true);
                    }
                } elseif (!preg_match($sourceExcludePattern, $targetPath) && $item !== $targetPath) {
                    $shouldCopy = !$force || !file_exists($targetPath) || filemtime($item) > filemtime($targetPath);
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
            self::$Configurations["Destination"]["Root"] = self::$DestinationDirectory;
        }

        $isInVendor = preg_match("/vendor[\/\\\]aseqbase[\/\\\][\w\s\-\.\~]+[\/\\\]$/i", $source);
        if ($isInVendor)
            try {
                $vc = json_decode(file_get_contents($source . "composer.json"), flags: JSON_OBJECT_AS_ARRAY);
                $c = json_decode(file_get_contents(self::$DestinationDirectory . "composer.json"), flags: JSON_OBJECT_AS_ARRAY);
                if (isset($vc["scripts"]["dev:start"])) {
                    $c["scripts"] = $c["scripts"] ?? [];
                    $c["scripts"]["start"] = $vc["scripts"]["dev:start"];
                }
                if (file_put_contents(self::$DestinationDirectory . "composer.json", json_encode($c, flags: JSON_OBJECT_AS_ARRAY)))
                    echo "✅ Scripts in 'composer.json' is updated.\n";
                else
                    echo "❌ Could not update scripts in 'composer.json'.\n";
            } catch (\Exception $e) {
                echo "❌ Could not update scripts in 'composer.json': " . $e->getMessage() . "\n";
            }
        return true;
    }
    public static function ConstructDataBase($force = true)
    {
        echo "\nDATABASE INSTALLATION -----------------\n";
        if (!isset(self::$Configurations["DataBase"]))
            self::$Configurations["DataBase"] = [];

        self::$DataBaseSchemaFile = self::$Configurations["DataBase"]["SchemaFile"] ?? self::$DataBaseSchemaFile;
        $sqlFile = __DIR__ . DIRECTORY_SEPARATOR . self::$DataBaseSchemaFile; // Your base schema
        if (!file_exists($sqlFile)) {
            echo "⚠️ There is no database schema to install!\n";
            return null;
        }

        $host = ($force ? self::$Configurations["DataBase"]["Host"] ?? null : null) ?? (readline("Database Host [" . (self::$Configurations["DataBase"]["Host"] ?? "localhost") . "]: ") ?: 'localhost');
        $name = ($force ? self::$Configurations["DataBase"]["Name"] ?? null : null) ?? (readline("Database Name [" . (self::$Configurations["DataBase"]["Name"] ?? "localhost") . "]: ") ?: 'localhost');
        if (empty($name)) {
            echo "❌ Database name required.\n";
            return self::ConstructDataBase($force);
        }
        $username = ($force ? self::$Configurations["DataBase"]["Username"] ?? null : null) ?? (readline("Username [" . (self::$Configurations["DataBase"]["Username"] ?? "root") . "]: ") ?: 'root');
        $password = ($force ? self::$Configurations["DataBase"]["Password"] ?? null : null) ?? (readline("Password [" . (self::$Configurations["DataBase"]["Password"] ?? "root") . "]: ") ?: 'root');
        $prefix = ($force ? self::$Configurations["DataBase"]["Prefix"] ?? null : null) ?? (readline("Table prefix [" . (self::$Configurations["DataBase"]["Prefix"] ?? "aseq_") . "]: ") ?: 'aseq_');

        self::$Configurations["DataBase"]["Host"] = $host;
        self::$Configurations["DataBase"]["Name"] = $name;
        self::$Configurations["DataBase"]["Username"] = $username;
        self::$Configurations["DataBase"]["Password"] = $password;
        self::$Configurations["DataBase"]["Prefix"] = $prefix;

        try {
            $pdo = new \PDO("mysql:host=$host;charset=" . (self::$Configurations["DataBase"]["Charset"] ?? "utf8mb4"), $username, $password);
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
            self::$Configurations["DataBase"] = [];
            echo "❌ Connection failed: " . $e->getMessage() . "\n";
            return self::ConstructDataBase($force);
        }
    }
    public static function ConstructSetting($force = true)
    {
        echo "\nSETTING INSTALLATION -----------------\n";
        try {
            $configFile = self::$DestinationDirectory . "Config.php";
            if (!file_exists($configFile) || $force) {
                if (!isset(self::$Configurations["DataBase"]))
                    self::$Configurations["DataBase"] = [];
                [$host, $port] = explode(":", isset(self::$Configurations["DataBase"]["Host"]) ? self::$Configurations["DataBase"]["Host"] . ":" : "localhost");
                $host = $host ?: "localhost";
                $port = $port ?: "null";
                $name = isset(self::$Configurations["DataBase"]["Name"]) ? self::$Configurations["DataBase"]["Name"] : "localhost";
                $username = isset(self::$Configurations["DataBase"]["Username"]) ? self::$Configurations["DataBase"]["Username"] : "root";
                $password = isset(self::$Configurations["DataBase"]["Password"]) ? self::$Configurations["DataBase"]["Password"] : "root";
                $prefix = isset(self::$Configurations["DataBase"]["Prefix"]) ? self::$Configurations["DataBase"]["Prefix"] : "";
                if (
                    $host === "localhost" &&
                    $port === "null" &&
                    $name === "localhost" &&
                    $username === "root" &&
                    $password === "root" &&
                    $prefix === "aseq_"
                ) {
                    echo "✅ Settings left default!\n";
                    return true;
                }
                if (
                    $res = file_put_contents($configFile, "<?php
class Config extends ConfigBase {
     /**
      * The database HostName or IP
      * @var string
      * @category DataBase
      */
     public \$DataBaseHost = '$host';
     /**
      * The database Port or null for default
      * @var string
      * @category DataBase
      */
     public \$DataBasePort = $port;
     /**
      * The database UserName
      * @field password
      * @var string
      * @category DataBase
      */
     public \$DataBaseUser = '$username';
     /**
      * The database Password
      * @field password
      * @var string
      * @category DataBase
      */
     public \$DataBasePassword = '$password';
     /**
      * The database Name
      * @var string
      * @category DataBase
      */
     public \$DataBaseName = '$name';
     /**
      * The database tables Prefix
      * @var string
      * @category DataBase
      */
     public \$DataBasePrefix = '$prefix';
}
            ")
                ) {
                    echo "✅ Settings stored successfully!\n";
                    return $res;
                }
            }
        } catch (\PDOException $e) {
            echo "⚠️ Could not store settings: " . $e->getMessage() . "\n";
        }
    }


    public static function DestructSource($force = false)
    {
        echo "\nSOURCE UNINSTALLATION -----------------\n";
        self::$DestinationDirectory = $force ? self::$DestinationDirectory : (readline("Destination Directory [" . self::$DestinationDirectory . "]: ") ?: self::$DestinationDirectory); // Project root (destination)
        if (!str_ends_with(self::$DestinationDirectory, DIRECTORY_SEPARATOR))
            self::$DestinationDirectory .= DIRECTORY_SEPARATOR;

        $sourceExcludePattern = self::$Configurations["Source"]["ExcludePattern"] ?? "/^vendor$/i";

        $source = dirname(__DIR__) . DIRECTORY_SEPARATOR;// Source folder (your framework package root)
        try {
            $i = 0;
            $rl = strlen($source);
            foreach (glob($source . '*') as $item) {
                $relPath = substr($item, $rl);
                if (
                    str_starts_with($relPath, "~") ||
                    str_starts_with($relPath, ".git" . DIRECTORY_SEPARATOR) ||
                    str_starts_with($relPath, "vendor" . DIRECTORY_SEPARATOR)
                )
                    continue;
                $targetPath = self::$DestinationDirectory . $relPath;

                if (is_dir($targetPath))
                    $i += self::DeleteDirectory($targetPath, $sourceExcludePattern);
                elseif (!preg_match($sourceExcludePattern, $targetPath)) {
                    if (unlink($targetPath)) {
                        $i++;
                        echo "📦 Deleted: '$targetPath'\n";
                    } else
                        echo "⚠️ Failed to delete file: $targetPath\n";
                }
            }
            echo "✅ $i source files are deleted.\n";
            self::$Configurations["Destination"]["Root"] = self::$DestinationDirectory;
        } catch (\Exception $e) {
            echo "❌ Source failed: " . $e->getMessage() . "\n";
            return false;
        }
    }
    public static function DestructDataBase($force = false)
    {
        echo "\nDATABASE UNINSTALLATION -----------------\n";
        if (!isset(self::$Configurations["DataBase"]))
            self::$Configurations["DataBase"] = [];

        $host = ($force ? self::$Configurations["DataBase"]["Host"] ?? null : null) ?? (readline("Database Host [" . (self::$Configurations["DataBase"]["Host"] ?? "localhost") . "]: ") ?: 'localhost');
        $name = ($force ? self::$Configurations["DataBase"]["Name"] ?? null : null) ?? (readline("Database Name [" . (self::$Configurations["DataBase"]["Name"] ?? "localhost") . "]: ") ?: 'localhost');
        if (empty($name)) {
            echo "❌ Database name required.\n";
            return self::DestructDataBase($force);
        }
        $username = ($force ? self::$Configurations["DataBase"]["Username"] ?? null : null) ?? (readline("Username [" . (self::$Configurations["DataBase"]["Username"] ?? "root") . "]: ") ?: 'root');
        $password = ($force ? self::$Configurations["DataBase"]["Password"] ?? null : null) ?? (readline("Password [" . (self::$Configurations["DataBase"]["Password"] ?? "root") . "]: ") ?: 'root');

        self::$Configurations["DataBase"]["Host"] = $host;
        self::$Configurations["DataBase"]["Name"] = $name;
        self::$Configurations["DataBase"]["Username"] = $username;
        self::$Configurations["DataBase"]["Password"] = $password;

        try {
            $pdo = new \PDO("mysql:host=$host;charset=" . (self::$Configurations["DataBase"]["Charset"] ?? "utf8mb4"), $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            echo "📦 Server is Connected: $host\n";
            $pdo->exec("DROP DATABASE $name;");
            echo "✅ The $name database with all tables dropped successfully!\n";
            return true;
        } catch (\PDOException $e) {
            self::$Configurations["DataBase"] = [];
            echo "❌ Connection failed: " . $e->getMessage() . "\n";
            return self::DestructDataBase($force);
        }
    }


    public static function LoadConfig()
    {
        try {
            $configFile = __DIR__ . DIRECTORY_SEPARATOR . self::$ConfigurationsFile;
            if (file_exists($configFile)) {
                self::$Configurations = json_decode(file_get_contents($configFile), flags: JSON_OBJECT_AS_ARRAY) ?: [];
                if (!isset(self::$Configurations["Source"]))
                    self::$Configurations["Source"] = [];
                if (!isset(self::$Configurations["Destination"]))
                    self::$Configurations["Destination"] = [];
                self::$DestinationDirectory = self::$Configurations["Destination"]["Root"] ?? preg_replace("/vendor[\/\\\]aseqbase[\/\\\][\w\s\-\.\~]+[\/\\\]$/i", "", getcwd() . DIRECTORY_SEPARATOR);
                if (!str_ends_with(self::$DestinationDirectory, DIRECTORY_SEPARATOR))
                    self::$DestinationDirectory .= DIRECTORY_SEPARATOR;
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
            if (!isset(self::$Configurations["Destination"]))
                self::$Configurations["Destination"] = [];
            self::$Configurations["Destination"]["Root"] = self::$DestinationDirectory ?? preg_replace("/vendor[\/\\\]aseqbase[\/\\\][\w\s\-\.\~]+[\/\\\]$/i", "", getcwd() . DIRECTORY_SEPARATOR);
            if ($res = file_put_contents($configFile, json_encode(self::$Configurations, flags: JSON_OBJECT_AS_ARRAY))) {
                echo "✅ Configurations stored successfully!\n";
                return $res;
            }
        } catch (\PDOException $e) {
            echo "⚠️ Could not store configurations: " . $e->getMessage() . "\n";
        }
    }

    public static function DeleteDirectory($directory = null, $excludePattern = null)
    {
        $i = 0;
        $directory = rtrim($directory, "/\\");
        if ($directory && is_dir($directory)) {
            foreach (glob($directory . DIRECTORY_SEPARATOR . '*') as $file) {
                if (!$excludePattern || preg_match($excludePattern, basename($file)))
                    continue;
                elseif (is_file($file)) {
                    if (unlink($file)) {
                        $i++;
                        echo "📦 Deleted: '$file'\n";
                    } else
                        echo "⚠️ Failed to delete file: $file\n";
                } elseif (is_dir($file))
                    $i += self::DeleteDirectory($file, $excludePattern);
            }
            if (count(scandir($directory)) === 2)
                rmdir($directory); // Remove the root directory itself
        }
        return $i; // Number of files deleted
    }
}
<?php
namespace MiMFa;
class Bootstrap
{
    public static $Arguments = [];
    public static $Configurations = [];
    public static $ServerPID = null;
    public static $ConfigurationsFile = 'bootstrap.json';
    public static $DataBaseSchemaFile = 'schema.sql';
    public static $DestinationDirectory = null;
    public static $ScriptsPattern = "/[\{\}\[\]\;\,\"'?=\*<>\$\^&\|\!]|(^\d*\.?\d+$)|(^null$)/";


    public static function Start()
    {
        self::LoadConfig();

        self::$ServerPID = self::$Configurations["ServerPID"] ?? self::$ServerPID;

        if (self::$ServerPID) {
            self::SetWarning("Server appears to be already running with the PID: " . self::$ServerPID);
            return;
        }

        if (!isset(self::$Configurations["Origin"]))
            self::$Configurations["Origin"] = [];

        [$host, $port] = explode(":", isset(self::$Configurations["Origin"]["Host"]) ? self::$Configurations["Origin"]["Host"] . ":" : "localhost:8000");
        $host = (self::$Arguments["host"] ?? $host) ?: "localhost";
        $port = (self::$Arguments["port"] ?? $port) ?: "8000";
        $db = self::$Arguments["db"] ?? self::$Arguments["database"] ?? "MySQL";

        self::$ServerPID = 0;

        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            pclose(popen("start /B php -S $host:$port", "r"));
            exec("net start $db");
            exec('php index.php');

            self::SetSuccess("Server started on $host:$port. Please manually note and store the PID to stop it.");

        } else {
            $command = "php -S $host:$port > /dev/null 2>&1 & echo $!";
            self::$ServerPID = exec($command);

            exec("sudo service $db start");
            exec('php index.php');

            self::SetSuccess("Server started on $host:$port with the PID " . self::$ServerPID);
        }

        if (self::$ServerPID > 0)
            self::$Configurations["ServerPID"] = (int) self::$ServerPID;

        self::$Configurations["Origin"]["Host"] = $host;
        self::$Configurations["Origin"]["Port"] = $port;
        self::StoreConfig();
    }
    public static function Stop()
    {
        self::LoadConfig();

        self::$ServerPID = self::$Configurations["ServerPID"] ?? self::$ServerPID;

        if (!self::$ServerPID || self::$ServerPID === 0) {
            self::SetWarning("No running server PID found in configuration. Check running processes manually.");
            return;
        }

        $db = self::$Arguments["db"] ?? self::$Arguments["database"] ?? "MySQL"; // Assuming DB argument is available/same logic

        if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
            exec("taskkill /F /PID " . self::$ServerPID, $output, $returnVar);

            if ($returnVar === 0)
                self::SetSuccess("Successfully terminated server process with the PID: " . self::$ServerPID);
            else
                self::SetError("Failed to terminate process with PID: " . self::$ServerPID . " (Return Code: $returnVar). It might already be stopped.");

            exec("net stop $db");

        } else {
            exec("kill -9 " . self::$ServerPID, $output, $returnVar);

            if ($returnVar === 0)
                self::SetSuccess("Successfully terminated server process with the PID: " . self::$ServerPID);
            else
                self::SetError("Failed to terminate process with PID: " . self::$ServerPID . " (Return Code: $returnVar). It might already be stopped.");
            exec("sudo service $db stop");
        }

        unset(self::$Configurations["ServerPID"]);
        self::StoreConfig();
    }


    public static function Install()
    {
        self::LoadConfig();
        if (self::ConstructSource(false) !== false)
            self::SetSuccess("Source installation is completed successfully.");
        if (self::ConstructDataBase(false) !== false)
            self::SetSuccess("DataBase installation is completed successfully.");
        if (self::ConstructSetting(false) !== false)
            self::SetSuccess("Setting installation is completed successfully.");
        self::StoreConfig();
        self::SetSubject("FINISHED");
    }
    public static function Update()
    {
        self::LoadConfig();
        if (self::ConstructSource(true) !== false)
            self::SetSuccess("Source update is completed successfully.");
        if (self::ConstructDataBase(true) !== false)
            self::SetSuccess("DataBase update is completed successfully.");
        if (self::ConstructSetting(true) !== false)
            self::SetSuccess("Setting update is completed successfully.");
        self::StoreConfig();
        self::SetSubject("FINISHED");
    }
    public static function ConstructSource($force = true)
    {
        self::SetSubject("SOURCE INSTALLATION");
        self::GetInput("Destination Directory", $force, self::$DestinationDirectory, self::$DestinationDirectory, "destination");
        if (!str_ends_with(self::$DestinationDirectory, DIRECTORY_SEPARATOR))
            self::$DestinationDirectory .= DIRECTORY_SEPARATOR;
        self::$Configurations["Destination"]["Path"] = self::$DestinationDirectory;

        $source = dirname(__DIR__) . DIRECTORY_SEPARATOR;// Source folder (your framework package root)
        $isInVendor = preg_match("/vendor[\/\\\]aseqbase[\/\\\][\w\s\-\.\~]+[\/\\\]$/i", $source);
        if ($isInVendor) {
            self::$Configurations["Source"]["Path"] = $source;
            self::StoreConfig();
        }
        if (self::$DestinationDirectory === $source) {
            self::SetSuccess("All files are ready.");
            self::$Configurations["Destination"]["Path"] = self::$DestinationDirectory;
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
                        self::SetReport("Copied: '$relPath'");
                    }
                }
            }

            self::SetSuccess("$i source files are copied.");
            self::$Configurations["Destination"]["Path"] = self::$DestinationDirectory;

            if ($isInVendor)
                try {
                    $cmds = ["start", "stop", "install", "reinstall", "uninstall", "update", "create"];
                    $vc = json_decode(file_get_contents($source . "composer.json"), flags: JSON_OBJECT_AS_ARRAY);
                    $c = json_decode(file_get_contents(self::$DestinationDirectory . "composer.json"), flags: JSON_OBJECT_AS_ARRAY);
                    if (isset($vc["scripts"]["dev:start"])) {
                        $c["scripts"] = $c["scripts"] ?? [];
                        $c["scripts"]["start"] = $vc["scripts"]["dev:start"];
                        $c["scripts"]["stop"] = $vc["scripts"]["dev:stop"];
                        foreach ($cmds as $key => $cmd)
                            $c["scripts"]["dev:$cmd"] = $vc["scripts"]["dev:$cmd"] ?? "";
                    }
                    $baseDir = preg_replace("/^" . preg_quote(self::$DestinationDirectory) . "/", "", getcwd());
                    $baseName = basename($baseDir);
                    foreach ($cmds as $key => $cmd)
                        $c["scripts"]["$baseName:$cmd"] = [
                            "cd \"$baseDir\" && composer dev:$cmd"
                        ];
                    if (file_put_contents(self::$DestinationDirectory . "composer.json", json_encode($c, flags: JSON_OBJECT_AS_ARRAY)))
                        self::SetSuccess("Scripts in 'composer.json' is updated.");
                    else
                        self::SetWarning("Could not update scripts in 'composer.json'.");
                } catch (\Exception $e) {
                    self::SetError("Could not update scripts in 'composer.json': " . $e->getMessage() . "");
                }
        }
        return true;
    }
    public static function ConstructDataBase($force = true)
    {
        self::SetSubject("DATABASE INSTALLATION");
        if (!isset(self::$Configurations["DataBase"]))
            self::$Configurations["DataBase"] = [];

        self::$DataBaseSchemaFile = self::$Configurations["DataBase"]["SchemaFile"] ?? self::$DataBaseSchemaFile;
        $sqlFile = __DIR__ . DIRECTORY_SEPARATOR . self::$DataBaseSchemaFile; // Your base schema
        if (!file_exists($sqlFile)) {
            self::SetWarning("There is no database schema to install!");
            return null;
        }

        self::GetInput("Database Host", $force, self::$Configurations["DataBase"]["Host"] ?? "localhost", $host, "host");
        self::GetInput("Database Name", $force, self::$Configurations["DataBase"]["Name"] ?? "localhost", $name, "name");
        if (empty($name)) {
            self::SetError("Database name required.");
            return self::ConstructDataBase($force);
        }
        self::GetInput("Database Username", $force, self::$Configurations["DataBase"]["Username"] ?? "root", $username, "username");
        self::GetInput("Database Password", $force, self::$Configurations["DataBase"]["Password"] ?? "root", $password, "password");
        self::GetInput("Tables prefix", $force, self::$Configurations["DataBase"]["Prefix"] ?? "aseq_", $prefix, "prefix");

        self::$Configurations["DataBase"]["Host"] = $host;
        self::$Configurations["DataBase"]["Name"] = $name;
        self::$Configurations["DataBase"]["Username"] = $username;
        self::$Configurations["DataBase"]["Password"] = $password;
        self::$Configurations["DataBase"]["Prefix"] = $prefix;

        try {
            $pdo = new \PDO("mysql:host=$host;charset=" . (self::$Configurations["DataBase"]["Charset"] ?? "utf8mb4"), $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::SetReport("Server is Connected: $host");

            $schema = file_get_contents($sqlFile);
            $schema = str_replace('%%DATABASE%%', $name, $schema);
            $schema = str_replace('%%PREFIX%%', $prefix, $schema);
            self::SetReport("Schema file read successfully!");

            $pdo->exec($schema);
            self::SetSuccess("All tables created successfully!");
            return true;
        } catch (\PDOException $e) {
            self::$Configurations["DataBase"] = [];
            self::SetError("Connection failed: " . $e->getMessage());
            return self::ConstructDataBase($force);
        }
    }
    public static function ConstructSetting($force = true)
    {
        self::SetSubject("SETTING INSTALLATION");
        if (self::$Arguments["global"] ?? true)
            self::CreateGlobalFile(true);
        if (self::$Arguments["back"] ?? true)
            self::CreateBackFile(true);
        if (self::$Arguments["front"] ?? false)
            self::CreateFrontFile($force);
        if (self::$Arguments["route"] ?? false)
            self::CreateRouteFile($force);
        if (self::$Arguments["config"] ?? false)
            self::CreateConfigFile($force);
        if (self::$Arguments["info"] ?? false)
            self::CreateInfoFile($force);
    }


    public static function Uninstall()
    {
        $force = self::$Arguments["f"] ?? self::$Arguments["force"] ?? false;
        if (strtolower(self::GetInput("Are you sure about starting uninstallation process (y/N)?", false, "n")) === "\"y\"") {
            self::LoadConfig();
            if ($force || strtolower(self::GetInput("Are you sure about removing all files and folders permanently (y/N)?", $force, "n")) === "\"y\"") {
                if (self::DestructSource($force) !== false)
                    self::SetSuccess("Source uninstallation is completed successfully.");
            }
            if ($force || strtolower(self::GetInput("Are you sure about removing all tables permanently (y/N)?", $force, "n")) === "\"y\"") {
                if (self::DestructDataBase($force) !== false)
                    self::SetSuccess("DataBase uninstallation is completed successfully.");
            }
            self::StoreConfig();
            self::SetSubject("FINISHED");
        }
    }
    public static function DestructSource($force = false)
    {
        self::SetSubject("SOURCE UNINSTALLATION");
        self::GetInput("Destination Directory", $force, self::$DestinationDirectory, self::$DestinationDirectory, "destination");
        if (!str_ends_with(self::$DestinationDirectory, DIRECTORY_SEPARATOR))
            self::$DestinationDirectory .= DIRECTORY_SEPARATOR;
        self::$Configurations["Destination"]["Path"] = self::$DestinationDirectory;

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
                        self::SetReport("Deleted: '$targetPath'");
                    } else
                        self::SetWarning("Failed to delete file: $targetPath");
                }
            }
            self::SetSuccess("$i source files are deleted.");
            self::$Configurations["Destination"]["Path"] = self::$DestinationDirectory;
        } catch (\Exception $e) {
            self::SetError("Source failed: " . $e->getMessage());
            return false;
        }
    }
    public static function DestructDataBase($force = false)
    {
        self::SetSubject("DATABASE UNINSTALLATION");
        if (!isset(self::$Configurations["DataBase"]))
            self::$Configurations["DataBase"] = [];

        self::GetInput("Database Host", $force, self::$Configurations["DataBase"]["Host"] ?? "localhost", $host, "host");
        self::GetInput("Database Name", $force, self::$Configurations["DataBase"]["Name"] ?? "localhost", $name, "name");
        if (empty($name)) {
            self::SetError("Database name required.");
            return self::ConstructDataBase($force);
        }
        self::GetInput("Database Username", $force, self::$Configurations["DataBase"]["Username"] ?? "root", $username, "username");
        self::GetInput("Database Password", $force, self::$Configurations["DataBase"]["Password"] ?? "root", $password, "password");

        self::$Configurations["DataBase"]["Host"] = $host;
        self::$Configurations["DataBase"]["Name"] = $name;
        self::$Configurations["DataBase"]["Username"] = $username;
        self::$Configurations["DataBase"]["Password"] = $password;

        try {
            $pdo = new \PDO("mysql:host=$host;charset=" . (self::$Configurations["DataBase"]["Charset"] ?? "utf8mb4"), $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            self::SetReport("Server is Connected: $host");
            $pdo->exec("DROP DATABASE $name;");
            self::SetSuccess("The $name database with all tables dropped successfully!");
            return true;
        } catch (\PDOException $e) {
            self::$Configurations["DataBase"] = [];
            self::SetError("Connection failed: " . $e->getMessage());
            return self::DestructDataBase($force);
        }
    }


    public static function Create()
    {
        try {
            self::LoadConfig();
            $force = self::$Arguments["f"] ?? self::$Arguments["force"] ?? false;
            switch (strtolower(self::$Arguments[0] ?? "")) {
                case "info":
                case "information":
                    return self::CreateInfoFile($force);
                case "config":
                case "configuration":
                    return self::CreateConfigFile($force);
                case "global":
                    return self::CreateGlobalFile($force);
                case "route":
                    return self::CreateRouteFile($force);
                case "front":
                    return self::CreateFrontFile($force);
                case "back":
                    return self::CreateBackFile($force);
                case "user":
                case "router":
                default:
                    return self::CreateFile(self::$Arguments[0] ?? null, self::$Arguments[1] ?? null, $force);
            }
        } finally {
            self::StoreConfig();
        }
    }
    public static function CreateFile($path = null, $content = null, $force = null)
    {
        try {
            self::GetInput("Path", $force, (self::$Arguments["name"] ?? null) ? self::$DestinationDirectory . self::$Arguments["name"] : (self::$Arguments["path"] ?? $path), $path, "path");
            if (!$path)
                return self::SetError("The --path or --name is not defined!");
            if (!$force && file_exists($path))
                return self::SetWarning("The '$path' is created before! Use force (-f) to create anyway...");
            self::GetInput("Content", $force, $content, $content, argument: "content");
            if (!$content)
                return self::SetWarning("The --content is empty!");
            if ($res = file_put_contents($path, $content)) {
                self::SetSuccess("'$path' created successfully!");
                return $res;
            } else
                self::SetError("Could not store '$path'!");
        } catch (\PDOException $e) {
            self::SetError("Could not store '$path': " . $e->getMessage());
        }
    }
    public static function CreateConfigFile($force = null)
    {
        return self::CreateFile(self::$DestinationDirectory . "Config.php", function () {
            return "<?php
" . ((self::$Arguments["b"] ?? null) ? "class Config extends ConfigBase" : "run(\"global/AseqConfig\");
class Config extends AseqConfig") . " {
}";
        }, $force);
    }
    public static function CreateInfoFile($force = null)
    {
        if (!isset(self::$Configurations["Info"]))
            self::$Configurations["Info"] = [];
        return self::CreateFile(self::$DestinationDirectory . "Info.php", fn() => "<?php
" . ((self::$Arguments["b"] ?? null) ? "class Info extends InfoBase" : "run(\"global/AseqInfo\");
class Info extends AseqInfo") . " {
    public \$Owner = " . self::GetInput("OwnerName", $force, "MiMFa", self::$Configurations["Info"]["Owner"], "owner") . ";
	public \$FullOwner = " . self::GetInput("FullOwnerName", $force, "MiMFa", self::$Configurations["Info"]["FullOwner"], "full-owner") . ";
	public \$Name = " . self::GetInput("Name", $force, "aseqbase", self::$Configurations["Info"]["Name"], "name") . ";
	public \$FullName = " . self::GetInput("FullName", $force, "MiMFa aseqbase", self::$Configurations["Info"]["FullName"], "full-name") . ";
	public \$Slogan = " . self::GetInput("Slogan", $force, "<u>a seq</u>uence-<u>base</u>d framework", self::$Configurations["Info"]["Slogan"], "slogan") . ";
	public \$FullSlogan = " . self::GetInput("FullSlogan", $force, "Develop websites by <u>a seq</u>uence-<u>base</u>d framework", self::$Configurations["Info"]["FullSlogan"], "full-slogan") . ";
	public \$Description = " . self::GetInput("Description", $force, "An original, safe, very flexible, and innovative framework for web developments!", self::$Configurations["Info"]["Description"], "description") . ";
	public \$FullDescription = " . self::GetInput("FullDescription", $force, "A special framework for web development called \"aseqbase\" (a sequence-based framework) has been developed to implement safe, flexible, fast, and strong pure websites based on that, since 2018 so far.", self::$Configurations["Info"]["FullDescription"], "full-description") . ";
}", $force);
    }
    public static function CreateGlobalFile($force = null)
    {
        $parent = (self::$Arguments["b"] ?? null) ? ".base" : ".aseq";
        if (!isset(self::$Configurations["Global"]))
            self::$Configurations["Global"] = [];
        return self::CreateFile(self::$DestinationDirectory . "global.php", fn() => "<?php
\$ASEQ = " . self::GetInput("Current directory name", $force, self::$Configurations["Global"]["Aseq"] ?? "null", self::$Configurations["Global"]["Aseq"], argument: "aseq") . "; 	// (Optional) The current subdomain sequence, or leave null if this file is in the root directory
\$BASE = " . self::GetInput("Parent directory name", $force, self::$Configurations["Global"]["Base"] ?? $parent, self::$Configurations["Global"]["Base"], argument: "base") . "; 	// (Optional) The parent directory you want to inherit all properties except what you changed
// \$SEQUENCES_PATCH = []; 		            // (Optional) An array to apply your custom changes in \\_::\$Sequences
                                            // newdirectory, newaseq; // Add new directory to the \\_::\$Sequences
                                            // directory, newaseq; // Update directory in the \\_::\$Sequences
                                            // directory, null; // Remove thw directory from the \\_::\$Sequences
", $force);
    }
    public static function CreateRouteFile($force = null)
    {
        return self::CreateFile(self::$DestinationDirectory . "route.php", fn() => "<?php
// To unset the default router sat at the before sequences
\_::\$Router->On()->Reset();

/**
 * Use your routers by below formats
 * \_::\$Router->On(\"A Part Of Path?\")->Default(\"Route Name\");
 * Or use a suitable handler for example
 * \_::\$Router->On()->Default(fn(\$router)=>deliver(\MiMFa\Library\Html::Heading1(\"Hello World!\")));
 */

// To route other requests to the DefaultRouteName
\_::\$Router->On()->Default(\_::\$Router->DefaultRouteName);", $force);
    }
    public static function CreateBackFile($force = null)
    {
        return self::CreateFile(self::$DestinationDirectory . "Back.php", function () {
            if (!isset(self::$Configurations["DataBase"]))
                self::$Configurations["DataBase"] = [];
            [$host, $port] = explode(":", isset(self::$Configurations["DataBase"]["Host"]) ? self::$Configurations["DataBase"]["Host"] . ":" : "localhost:");
            $host = self::$Arguments["host"] ?? ($host ?: "localhost");
            $port = self::$Arguments["port"] ?? ($port ?: "null");
            $name = self::$Arguments["name"] ?? (isset(self::$Configurations["DataBase"]["Name"]) ? self::$Configurations["DataBase"]["Name"] : "localhost");
            $username = self::$Arguments["username"] ?? (isset(self::$Configurations["DataBase"]["Username"]) ? self::$Configurations["DataBase"]["Username"] : "root");
            $password = self::$Arguments["password"] ?? (isset(self::$Configurations["DataBase"]["Password"]) ? self::$Configurations["DataBase"]["Password"] : "root");
            $prefix = self::$Arguments["prefix"] ?? (isset(self::$Configurations["DataBase"]["Prefix"]) ? self::$Configurations["DataBase"]["Prefix"] : "");
            if (
                $host === "localhost" &&
                $port === "null" &&
                $name === "localhost" &&
                $username === "root" &&
                $password === "root" &&
                $prefix === "aseq_"
            ) {
                self::SetSuccess("Backs are default!");
                return null;
            }
            return "<?php
" . ((self::$Arguments["b"] ?? null) ? "class Back extends BackBase" : "run(\"global/AseqBack\");
class Back extends AseqBack") . " {
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
}";
        }, $force);
    }
    public static function CreateFrontFile($force = null)
    {
        return self::CreateFile(self::$DestinationDirectory . "Front.php", function () use ($force) {
            if (!isset(self::$Configurations["Front"]))
                self::$Configurations["Front"] = [];
            return "<?php
" . ((self::$Arguments["b"] ?? null) ? "class Front extends FrontBase" : "run(\"global/AseqFront\");
class Front extends AseqFront") . " {
	/**
	 * The website default template class
	 * @var string
	 * @default \"Main\"
	 * @category General
	 */
    public \$DefaultTemplate = " . self::GetInput("Template Name", $force, self::$Configurations["Front"]["DefaultTemplate"] ?? "Main", self::$Configurations["Front"]["DefaultTemplate"], "template") . ";
    /**
	 * Fore Colors Palette
	 * @field array<color>
	 * @template array [normal, inside, outside, special, special-input, special-output]
	 * @var mixed
	 */
	public \$ForeColorPalette = array(\"#151515\", \"#202020\", " .
                self::GetInput("Alternative ForeColor", $force, self::$Configurations["Front"]["AlternativeForeColor"] ?? "#3aa3e9", self::$Configurations["Front"]["AlternativeForeColor"], "back-color") .
                ", \"#040506\", \"#030303\", " .
                self::GetInput("Special ForeColor", $force, self::$Configurations["Front"]["SpecialForeColor"] ?? self::$Arguments["forecolor"] ?? self::$Configurations["Front"]["AlternativeForeColor"] ?? "#fdfeff", self::$Configurations["Front"]["SpecialForeColor"], "fore-color-special") .
                ");
	/**
	 * Back Colors Palette
	 * @field array<color>
	 * @template array [normal, inside, outside, special, special-input, special-output]
	 * @var mixed
	 */
	public \$BackColorPalette = array(\"#fdfeff\", \"#fafbfc\", " .
                self::GetInput("Alternative BackColor", $force, self::$Configurations["Front"]["AlternativeBackColor"] ?? "#fdfeff", self::$Configurations["Front"]["AlternativeBackColor"], "fore-color") .
                ", \"#fafcfd\", \"#fdfeff\", " .
                self::GetInput("Special BackColor", $force, self::$Configurations["Front"]["SpecialBackColor"] ?? self::$Arguments["backcolor"] ?? self::$Configurations["Front"]["AlternativeBackColor"] ?? "#3aa3e9", self::$Configurations["Front"]["SpecialBackColor"], "back-color-special") .
                ");
}";
        }, $force);
    }

    public static function LoadConfig()
    {
        try {
            self::$Arguments = self::GetArguments();
            $configFile = getcwd() . DIRECTORY_SEPARATOR . self::$ConfigurationsFile;
            if (file_exists($configFile)) {
                self::$Configurations = json_decode(file_get_contents($configFile), flags: JSON_OBJECT_AS_ARRAY) ?: [];
                if (!isset(self::$Configurations["Source"]))
                    self::$Configurations["Source"] = [];
                if (!isset(self::$Configurations["Destination"]))
                    self::$Configurations["Destination"] = [];
                self::$DestinationDirectory = (isset(self::$Configurations["Destination"]["Path"]) ? self::$Configurations["Destination"]["Path"] : null) ?: preg_replace("/vendor[\/\\\]aseqbase[\/\\\][\w\s\-\.\~]+[\/\\\]$/i", "", getcwd() . DIRECTORY_SEPARATOR);
                if (!str_ends_with(self::$DestinationDirectory, DIRECTORY_SEPARATOR))
                    self::$DestinationDirectory .= DIRECTORY_SEPARATOR;
                //self::SetSuccess("Configurations loaded successfully!");
                return self::$Configurations;
            }
        } catch (\PDOException $e) {
            self::SetWarning("Could not loaded configurations: " . $e->getMessage());
        }
        return self::$Configurations = [];
    }
    public static function StoreConfig()
    {
        try {
            $configFile = getcwd() . DIRECTORY_SEPARATOR . self::$ConfigurationsFile;
            if (!isset(self::$Configurations["Destination"]))
                self::$Configurations["Destination"] = [];
            self::$Configurations["Destination"]["Path"] = self::$DestinationDirectory ?? preg_replace("/vendor[\/\\\]aseqbase[\/\\\][\w\s\-\.\~]+[\/\\\]$/i", "", getcwd() . DIRECTORY_SEPARATOR);
            if ($res = file_put_contents($configFile, json_encode(self::$Configurations, flags: JSON_OBJECT_AS_ARRAY))) {
                //self::SetSuccess("Configurations stored successfully!");
                return $res;
            }
        } catch (\PDOException $e) {
            self::SetWarning("Could not store configurations: " . $e->getMessage());
        }
    }


    public static function GetArguments()
    {
        global $argv, $argc;
        self::$Arguments = [];
        $k = 0;
        if ($argv)
            for ($i = 2; $i < $argc; $i++)
                if (str_starts_with($argv[$i], "--"))
                    self::$Arguments[strtolower(str_replace("-", "", $argv[$i]))] = $argv[$i = $i + 1] ?? null;
                elseif (str_starts_with($argv[$i], "-"))
                    self::$Arguments[strtolower(str_replace("-", "", $argv[$i]))] = true;
                else
                    self::$Arguments[$k++] = $argv[$i];
        return self::$Arguments;
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
                        self::SetReport("Deleted: '$file'");
                    } else
                        self::SetWarning("Failed to delete file: $file");
                } elseif (is_dir($file))
                    $i += self::DeleteDirectory($file, $excludePattern);
            }
            if (count(scandir($directory)) === 2)
                rmdir($directory); // Remove the root directory itself
        }
        return $i; // Number of files deleted
    }

    public static function GetInput($message, $force = false, $default = null, &$input = null, $argument = "arg")
    {
        $ask = self::$Arguments["a"] ?? false;
        $default = self::$Arguments[str_replace("-", "", $argument)] ?? ($input ?: $default);
        $input = ($force && !$ask && !is_null($default)) ?
            (is_callable($default) ? $default() : $default) :
            (readline($message .
                ($argument === "arg" ? "" : " (--$argument)") .
                ($default ? (
                        is_callable($default) ? " [...]" :
                        " [" . (preg_match(self::$ScriptsPattern, $default) ? $default : "\"$default\"") .
                        "]"
                    ) : ""
                ) . ": "
            ) ?: (is_callable($default) ? $default() : $default));
        if(is_null($input)) return $input;
        if (preg_match("/^\"[\w\W]*[^\\\]\"$/", trim($input), $matches))
            return $matches[0];
        return preg_match(self::$ScriptsPattern, $input) ? $input : "\"$input\"";
    }
    public static function SetOutput($message = null)
    {
        echo "$message\n";
    }
    public static function SetSubject($message = null)
    {
        self::SetOutput("\n$message --------------------");
    }
    public static function SetSuccess($message = null)
    {
        self::SetOutput("✅  $message");
        return true;
    }
    public static function SetWarning($message = null)
    {
        self::SetOutput("⚠️  $message");
        return null;
    }
    public static function SetError($message = null)
    {
        self::SetOutput("❌  $message");
        return false;
    }
    public static function SetReport($message = null)
    {
        self::SetOutput("📦  $message");
    }
}
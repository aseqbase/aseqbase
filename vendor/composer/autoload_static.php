<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit68f6484b7c4cec24c08a282d2322ef29
{
    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'CoinRemitter' => 
            array (
                0 => __DIR__ . '/..' . '/coinremitterphp/coinremitter-php/src',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInit68f6484b7c4cec24c08a282d2322ef29::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit68f6484b7c4cec24c08a282d2322ef29::$classMap;

        }, null, ClassLoader::class);
    }
}

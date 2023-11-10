<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbc5ad906da36ad19a399c1bcf15e847c
{
    public static $files = array (
        'a1fed80401a41a55299e1f81e2b560d1' => __DIR__ . '/../..' . '/framework/helpers.php',
    );

    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Framework\\' => 10,
        ),
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Framework\\' => 
        array (
            0 => __DIR__ . '/../..' . '/framework',
        ),
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbc5ad906da36ad19a399c1bcf15e847c::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbc5ad906da36ad19a399c1bcf15e847c::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbc5ad906da36ad19a399c1bcf15e847c::$classMap;

        }, null, ClassLoader::class);
    }
}

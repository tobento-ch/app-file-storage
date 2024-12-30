<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

use Tobento\Service\FileStorage\StorageInterface;
use Psr\Container\ContainerInterface;
use function Tobento\App\{directory};

return [

    /*
    |--------------------------------------------------------------------------
    | Default File Storages Names
    |--------------------------------------------------------------------------
    |
    | Specify the default storage names you wish to use for your application.
    |
    | The default "primary" is used by the application for the default
    | StorageInterface implementation
    | used for autowiring classes and may be used in other app bundles.
    | If you do not need it at all, just ignore or remove it.
    |
    */

    'defaults' => [
        'primary' => 'uploads',
        'uploads' => 'uploads',
        'images' => 'images',
        'cache' => 'cache',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | File Storages
    |--------------------------------------------------------------------------
    |
    | Configure any file storages needed for your application.
    |
    */
    
    'storages' => [
        
        'uploads' => [
            'factory' => \Tobento\App\FileStorage\FilesystemStorageFactory::class,
            'config' => [
                // The location storing the files:
                'location' => directory('app').'storage/uploads/',
            ],
        ],
        
        'images' => [
            'factory' => \Tobento\App\FileStorage\FilesystemStorageFactory::class,
            'config' => [
                // The location storing the files:
                'location' => directory('public').'img/',
                //'public_url' => 'https://example.org/img/',
            ],
        ],
        
        'cache' => [
            'factory' => \Tobento\App\FileStorage\FilesystemStorageFactory::class,
            'config' => [
                // The location storing the files:
                'location' => directory('app').'storage/cache/',
            ],
        ],
        
        // you may remove it if you are not using the media picture feature
        // see: https://github.com/tobento-ch/app-media#picture-feature
        'picture-data' => [
            'factory' => \Tobento\App\FileStorage\FilesystemStorageFactory::class,
            'config' => [
                // The location storing the files:
                'location' => directory('app').'storage/picture-data/',
            ],
        ],
        
        /*
        // example using closure:
        'file' => function(ContainerInterface $c): StorageInterface {
            // create storage:
        },
        */
        
    ],

];
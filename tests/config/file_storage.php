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
use Tobento\Service\FileStorage\Flysystem;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\StreamFactoryInterface;
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
            ],
        ],
        
        'cache' => [
            'factory' => \Tobento\App\FileStorage\FilesystemStorageFactory::class,
            'config' => [
                // The location storing the files:
                'location' => directory('app').'storage/cache/',
            ],
        ],
        
        // example using closure:
        'videos' => function(ContainerInterface $c): StorageInterface {
            
            $filesystem = new \League\Flysystem\Filesystem(
                adapter: new \League\Flysystem\Local\LocalFilesystemAdapter(
                    location: directory('app').'storage/videos/',
                )
            );

            return new Flysystem\Storage(
                name: 'videos',
                flysystem: $filesystem,
                fileFactory: new Flysystem\FileFactory(
                    flysystem: $filesystem,
                    streamFactory: $c->get(StreamFactoryInterface::class),
                ),
            );
        },
        
    ],

];
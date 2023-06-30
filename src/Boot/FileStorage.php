<?php

/**
 * TOBENTO
 *
 * @copyright   Tobias Strub, TOBENTO
 * @license     MIT License, see LICENSE file distributed with this source code.
 * @author      Tobias Strub
 * @link        https://www.tobento.ch
 */

declare(strict_types=1);
 
namespace Tobento\App\FileStorage\Boot;

use Tobento\App\Boot;
use Tobento\App\Boot\Functions;
use Tobento\App\Boot\Config;
use Tobento\App\Migration\Boot\Migration;
use Tobento\App\Http\Boot\Http;
use Tobento\Service\FileStorage\StorageFactoryInterface;
use Tobento\Service\FileStorage\StorageInterface;
use Tobento\Service\FileStorage\StoragesInterface;
use Tobento\Service\FileStorage\Storages;
use Tobento\Service\FileStorage\StorageException;

/**
 * FileStorage
 */
class FileStorage extends Boot
{
    public const INFO = [
        'boot' => [
            'installs and loads file storage config file',
            'creates file storages based on storage config file',
        ],
    ];

    public const BOOT = [
        Functions::class,
        Config::class,
        Migration::class,
        Http::class,
    ];
    
    /**
     * Boot application services.
     *
     * @param Migration $migration
     * @param Config $config
     * @return void
     */
    public function boot(Migration $migration, Config $config): void
    {
        // install file storage config:
        $migration->install(\Tobento\App\FileStorage\Migration\FileStorage::class);
        
        // Storages implementation:
        $this->app->set(StoragesInterface::class, function() use ($config): StoragesInterface {
            
            // Load the file storage configuration without storing it:
            $config = $config->load(file: 'file_storage.php');
            
            // Create and register the storages:
            $storages = new Storages();
            
            foreach($config['defaults'] ?? [] as $name => $storage) {
                $storages->addDefault(name: $name, storage: $storage);
            }
            
            foreach($config['storages'] ?? [] as $name => $params)
            {
                $storages->register(name: $name, storage: function() use ($name, $params) {
                    
                    if (is_callable($params)) {
                        return $params($this->app->container());
                    }
                    
                    $factory = $this->app->get($params['factory']);

                    if (! $factory instanceof StorageFactoryInterface){ 
                        throw new StorageException(
                            sprintf(
                                'Storage config factory needs to be an instance of %s!',
                                StorageFactoryInterface::class
                            )
                        );
                    }

                    return $factory->createStorage($name, $params['config']);
                });
            }
            
            return $storages;
        });
        
        // Default StorageInterface:
        $this->app->set(StorageInterface::class, function(): StorageInterface {
            return $this->app->get(StoragesInterface::class)->default('primary');
        });
    }
}
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

namespace Tobento\App\FileStorage\Test\Boot;

use PHPUnit\Framework\TestCase;
use Tobento\App\FileStorage\Boot\FileStorage;
use Tobento\Service\FileStorage\StoragesInterface;
use Tobento\Service\FileStorage\StorageInterface;
use Tobento\App\AppInterface;
use Tobento\App\AppFactory;
use Tobento\Service\Filesystem\Dir;
    
/**
 * FileStorageTest
 */
class FileStorageTest extends TestCase
{
    protected function createApp(bool $deleteDir = true): AppInterface
    {
        if ($deleteDir) {
            (new Dir())->delete(__DIR__.'/../app/');
        }
        
        (new Dir())->create(__DIR__.'/../app/');
        
        $app = (new AppFactory())->createApp();
        
        $app->dirs()
            ->dir(realpath(__DIR__.'/../../'), 'root')
            ->dir(realpath(__DIR__.'/../app/'), 'app')
            ->dir($app->dir('app').'config', 'config', group: 'config', priority: 10)
            ->dir($app->dir('root').'vendor', 'vendor')
            // for testing only we add public within app dir.
            ->dir($app->dir('app').'public', 'public');
        
        return $app;
    }
    
    public static function tearDownAfterClass(): void
    {
        (new Dir())->delete(__DIR__.'/../app/');
    }
    
    public function testInterfacesAreAvailable()
    {
        $app = $this->createApp();
        $app->boot(FileStorage::class);
        $app->booting();
        
        $this->assertInstanceof(StoragesInterface::class, $app->get(StoragesInterface::class));
        $this->assertInstanceof(StorageInterface::class, $app->get(StorageInterface::class));
    }
    
    public function testDefaultStoragesAreAvailable()
    {
        $app = $this->createApp();
        $app->boot(FileStorage::class);
        $app->booting();
        
        $storages = $app->get(StoragesInterface::class);
        $this->assertInstanceof(StorageInterface::class, $storages->default('primary'));
        $this->assertInstanceof(StorageInterface::class, $storages->default('uploads'));
        $this->assertInstanceof(StorageInterface::class, $storages->default('images'));
        $this->assertInstanceof(StorageInterface::class, $storages->default('cache'));
    }
    
    public function testWithClosureConfigStorage()
    {
        $app = $this->createApp();
        
        $app->dirs()->dir(realpath(__DIR__.'/../config/'), 'config-test', group: 'config', priority: 20);
        
        $app->boot(FileStorage::class);
        $app->booting();
        
        $storages = $app->get(StoragesInterface::class);
        $this->assertInstanceof(StorageInterface::class, $storages->get('videos'));
    }
}
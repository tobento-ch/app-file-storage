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

namespace Tobento\App\FileStorage\Test;

use PHPUnit\Framework\TestCase;
use Tobento\App\FileStorage\FilesystemStorageFactory;
use Tobento\Service\FileStorage\StorageFactoryInterface;
use Tobento\Service\FileStorage\StorageInterface;
use Tobento\Service\FileStorage\StorageException;
use Nyholm\Psr7\Factory\Psr17Factory;
    
/**
 * FilesystemStorageFactoryTest
 */
class FilesystemStorageFactoryTest extends TestCase
{
    public function testConstructMethod()
    {
        $factory = new FilesystemStorageFactory(
            streamFactory: new Psr17Factory(),
        );
        
        $this->assertInstanceof(StorageFactoryInterface::class, $factory);
    }
    
    public function testCreateStorageMethod()
    {
        $factory = new FilesystemStorageFactory(
            streamFactory: new Psr17Factory(),
        );
        
        $this->assertInstanceof(
            StorageInterface::class,
            $factory->createStorage(
                name: 'name',
                config: [
                    'location' => __DIR__.'/../tmp/',
                ]
            )
        );
    }
    
    public function testCreateStorageMethodWithoutLocationThrowsStorageException()
    {
        $this->expectException(StorageException::class);
        
        $factory = new FilesystemStorageFactory(
            streamFactory: new Psr17Factory(),
        );
        
        $this->assertInstanceof(
            StorageInterface::class,
            $factory->createStorage(
                name: 'name',
                config: []
            )
        );
    }
}
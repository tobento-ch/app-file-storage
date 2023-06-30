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

namespace Tobento\App\FileStorage;

use Tobento\Service\FileStorage\StorageFactoryInterface;
use Tobento\Service\FileStorage\StorageInterface;
use Tobento\Service\FileStorage\StorageException;
use Tobento\Service\FileStorage\Flysystem;
use Psr\Http\Message\StreamFactoryInterface;

/**
 * FilesystemStorageFactory
 */
class FilesystemStorageFactory implements StorageFactoryInterface
{
    /**
     * Create a new FilesystemStorageFactory.
     *
     * @param StreamFactoryInterface $streamFactory
     */
    public function __construct(
        protected StreamFactoryInterface $streamFactory,
    ) {}

    /**
     * Create a new Storage based on the configuration.
     *
     * @param string $name Any storage name.
     * @param array $config Configuration data.
     * @return StorageInterface
     * @throws StorageException
     */
    public function createStorage(string $name, array $config = []): StorageInterface
    {
        if (empty($config['location'])) {
            throw new StorageException('Config "location" is missing');
        }
        
        try {
            $filesystem = new \League\Flysystem\Filesystem(
                adapter: new \League\Flysystem\Local\LocalFilesystemAdapter(
                    location: $config['location']
                )
            );
        } catch (\League\Flysystem\UnableToCreateDirectory $e) {
            throw new StorageException($e->getMessage(), $e->getCode(), $e);
        }

        return new Flysystem\Storage(
            name: $name,
            flysystem: $filesystem,
            fileFactory: new Flysystem\FileFactory(
                flysystem: $filesystem,
                streamFactory: $this->streamFactory,
            ),
        );
    }
}
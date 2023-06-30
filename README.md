# App File Storage

File storage support for the app.

## Table of Contents

- [Getting Started](#getting-started)
    - [Requirements](#requirements)
- [Documentation](#documentation)
    - [App](#app)
    - [File Storage Boot](#file-storage-boot)
        - [File Storage Config](#file-storage-config)
        - [File Storage Usage](#file-storage-usage)
- [Credits](#credits)
___

# Getting Started

Add the latest version of the app file storage project running this command.

```
composer require tobento/app-file-storage
```

## Requirements

- PHP 8.0 or greater

# Documentation

## App

Check out the [**App Skeleton**](https://github.com/tobento-ch/app-skeleton) if you are using the skeleton.

You may also check out the [**App**](https://github.com/tobento-ch/app) to learn more about the app in general.

## File Storage Boot

The file storage boot does the following:

* installs and loads file storage config file
* creates file storages based on storage config file

```php
use Tobento\App\AppFactory;

// Create the app
$app = (new AppFactory())->createApp();

// Add directories:
$app->dirs()
    ->dir(realpath(__DIR__.'/../'), 'root')
    ->dir(realpath(__DIR__.'/../app/'), 'app')
    ->dir($app->dir('app').'config', 'config', group: 'config')
    ->dir($app->dir('root').'public', 'public')
    ->dir($app->dir('root').'vendor', 'vendor');

// Adding boots:
$app->boot(\Tobento\App\FileStorage\Boot\FileStorage::class);

// Run the app:
$app->run();
```

You may check out the [**File Storage Service**](https://github.com/tobento-ch/service-file-storage) to learn more about it.

### File Storage Config

The configuration for the file storage is located in the ```app/config/file_storage.php``` file at the default App Skeleton config location where you can specify the pools and caches for your application.

### File Storage Usage

You can access the file storage(s) in several ways:

**Using the app**

```php
use Tobento\App\AppFactory;
use Tobento\Service\FileStorage\StoragesInterface;
use Tobento\Service\FileStorage\StorageInterface;

$app = (new AppFactory())->createApp();

// Add directories:
$app->dirs()
    ->dir(realpath(__DIR__.'/../'), 'root')
    ->dir(realpath(__DIR__.'/../app/'), 'app')
    ->dir($app->dir('app').'config', 'config', group: 'config')
    ->dir($app->dir('root').'public', 'public')
    ->dir($app->dir('root').'vendor', 'vendor');

// Adding boots:
$app->boot(\Tobento\App\FileStorage\Boot\FileStorage::class);
$app->booting();

$storages = $app->get(StoragesInterface::class);

$defaultStorage = $app->get(StorageInterface::class);

// Run the app:
$app->run();
```

You may check out the [**File Storage Service**](https://github.com/tobento-ch/service-file-storage) to learn more about in general.

Check out the [**Storages Interface**](https://github.com/tobento-ch/service-file-storage#storages-interface) to learn more about storages.

Check out the [**Storage Interface**](https://github.com/tobento-ch/service-file-storage#storage-interface) to learn more about the storage.

**Using autowiring**

You can also request the interfaces in any class resolved by the app:

```php
use Tobento\Service\FileStorage\StoragesInterface;
use Tobento\Service\FileStorage\StorageInterface;

class SomeService
{
    public function __construct(
        protected StoragesInterface $storages,
        protected StorageInterface $storage,
    ) {}
}
```

# Credits

- [Tobias Strub](https://www.tobento.ch)
- [All Contributors](../../contributors)
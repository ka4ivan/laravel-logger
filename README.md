# A Laravel package for advanced logging, providing structured logs and tracking model changes

[![License](https://img.shields.io/packagist/l/ka4ivan/laravel-logger.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-logger)
[![Build Status](https://img.shields.io/github/stars/ka4ivan/laravel-logger.svg?style=for-the-badge)](https://github.com/ka4ivan/laravel-logger)
[![Latest Stable Version](https://img.shields.io/packagist/v/ka4ivan/laravel-logger.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-logger)
[![Total Downloads](https://img.shields.io/packagist/dt/ka4ivan/laravel-logger.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-logger)
[![Quality Score](https://img.shields.io/scrutinizer/g/ka4ivan/laravel-logger.svg?style=for-the-badge)](https://scrutinizer-ci.com/g/ka4ivan/laravel-logger/?branch=main)

# Laravel Logger 📦
A Laravel package for advanced logging, providing structured logs and tracking model changes

## 📖 Table of Contents
- [Installation](#installation)
- [Usage](#usage)
    - [Logging](#logging)
    - [Logging Methods](#logging-methods)
    - [Tracking Model Changes](#tracking-model-changes)
        - [Preparing Your Model](#preparing-your-model)
    - [Helpers](#helpers)
        - [json_pretty](#json_pretty)
- [License](#license)

## Installation

1️⃣ Require this package using Composer:  
```shell  
composer require ka4ivan/laravel-logger  
```

2️⃣ Publish the package resources:  
```shell  
php artisan vendor:publish --provider="Ka4ivan\LaravelLogger\ServiceProvider"  
```

This command publishes:
- Configuration file
- Views

3️⃣ Add a route to your `web.php` file:  
```php  
Route::get('logs', [\Ka4ivan\LaravelLogger\Http\Controllers\LogViewerController::class, 'index'])->name('logs');  
```

### 🔧 Default Configuration

Here’s the default `config` file for reference:  
```php
<?php  

return [  
    'default' => env('LOG_CHANNEL', 'stack'),  

    'tracking' => [  
        'default' => 'tracking',  
    ],  

    'user' => [  
        'fields' => ['id', 'email', 'name'],  
    ],  

    'channels' => [  
        'tracking' => [  
            'driver' => 'daily',  
            'path' => storage_path('logs/_tracking.log'),  
            'level' => env('LOG_LEVEL', 'debug'),  
            'days' => 30,  
            'active' => env('LOGGING_ROUTES_ACTIVE', true),  
        ],  
    ],  

    'max_file_size' => 52428800, // 50MB  

    'pattern' => env('LOGGER_PATTERN', '*.log'),  

    'storage_path' => env('LOGGER_STORAGE_PATH', storage_path('logs')),  
];  
```  

## Usage

### Logging

You can log anything using the package’s facade.  

```php  
use Ka4ivan\LaravelLogger\Facades\Llog;  

// Example  
Llog::warning('Something happened', [  
    'users' => User::count(),  
    'products' => Product::count(),  
    'variations' => Product::count(),  
    'orders' => Order::count(),  
    'leads' => Lead::count(),  
]);  
```  

Or without a message:
```php
use Ka4ivan\LaravelLogger\Facades\Llog;  

// Example  
Llog::warning([  
    'cart' => Order::firstWhere(['number' => '1001']),  
    'order' => Order::firstWhere(['number' => '1002']),  
]);  
```

### Logging Methods

All Laravel logging methods are available:  
- `emergency`  
- `alert`  
- `critical`  
- `error`  
- `warning`  
- `notice`  
- `info`  
- `debug`  
- `log`  

### Tracking Model Changes

#### Preparing Your Model

Use the `HasTracking` trait to automatically track model changes (create, update, delete).  

```php  
use Ka4ivan\LaravelLogger\Models\Traits\HasTracking;  

class Article extends Model  
{  
    use HasTracking;  
}  
```  

It has the following structure:

### Helpers

#### json_pretty

Formats a JSON string for better readability.  

```php  
$data = Article::first();  

$res = json_pretty($data);  
```  

## License

This package is licensed under the [MIT License](https://opensource.org/licenses/MIT). You can freely use, modify, and distribute this package as long as you include a copy of the license.  

# A Laravel package for advanced logging, providing structured logs and tracking model changes

[![License](https://img.shields.io/packagist/l/ka4ivan/laravel-logger.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-logger)
[![Build Status](https://img.shields.io/github/stars/ka4ivan/laravel-logger.svg?style=for-the-badge)](https://github.com/ka4ivan/laravel-logger)
[![Latest Stable Version](https://img.shields.io/packagist/v/ka4ivan/laravel-logger.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-logger)
[![Total Downloads](https://img.shields.io/packagist/dt/ka4ivan/laravel-logger.svg?style=for-the-badge)](https://packagist.org/packages/ka4ivan/laravel-logger)

# Laravel Logger 📦
A Laravel package for advanced logging, providing structured logs and tracking model changes

<img width="1918" height="906" alt="image" src="https://github.com/user-attachments/assets/9d1e8646-cbda-4150-9870-6a0a43ab2cbe" />

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
    /*
     * Default log channel
     *
     * This defines the default logging channel to be used by the application.
     * The value is retrieved from the LOG_CHANNEL environment variable.
     */
    'default' => env('LOG_CHANNEL', 'stack'),

    /*
     * Tracking logs configuration
     */
    'tracking' => [
        /*
         * Defines the default logging channel for tracking events.
         */
        'default' => 'tracking',
    ],

    'user' => [
        /*
         * Specifies which fields from the authenticated user should be included in the logs.
         */
        'fields' => ['id', 'email', 'name'],
    ],

    /*
     * Log channels configuration
     *
     * Defines different logging channels with their respective settings.
     */
    'channels' => [
        /*
         * Tracking log channels
         */
        'tracking' => [
            'driver' => 'daily',
            'path' => storage_path('logs/_tracking.log'),
            'level' => env('LOG_LEVEL', 'debug'),
            'days' => 30,
            'active' => env('LOGGING_ROUTES_ACTIVE', true),
        ],
    ],
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

<img width="1222" height="231" alt="image" src="https://github.com/user-attachments/assets/20b05915-86b3-4c7e-98f5-98f6c24757e3" />

Or without a message:
```php
use Ka4ivan\LaravelLogger\Facades\Llog;  

// Example  
Llog::info([  
    'first' => Brand::find('545e94e7-720f-4df6-9bef-bc0684f30690'),  
    'second' => Brand::find('16df9b24-52f3-4d39-9d96-ae24b6ad3a6a'),  
]);  
```
<img width="1222" height="374" alt="image" src="https://github.com/user-attachments/assets/9635f0c5-2e42-483c-8c1e-264d4dc3ab0f" />

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

<img width="1211" height="539" alt="image" src="https://github.com/user-attachments/assets/239eb2cd-0245-453e-89bd-e4c69ee0dc3e" />
<img width="1205" height="390" alt="image" src="https://github.com/user-attachments/assets/590b8725-5354-4587-8f5c-5868eaf311cc" />
<img width="1205" height="291" alt="image" src="https://github.com/user-attachments/assets/91558bf8-26de-494f-b48e-7290155b3c1d" />

### Helpers

#### json_pretty

Formats a JSON string for better readability.  

```php  
$data = Article::first();  

$res = json_pretty($data);  
```  

## License

This package is licensed under the [MIT License](https://opensource.org/licenses/MIT). You can freely use, modify, and distribute this package as long as you include a copy of the license.  

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Idharf\PhpMvc\App\Router;
use Idharf\PhpMvc\Controller\HomeController;
use Idharf\PhpMvc\Controller\UserController;
use Idharf\PhpMvc\Controller\ProductController;
use Idharf\PhpMvc\Middleware\AuthMiddlewareImpl;

Router::add('GET', '/', HomeController::class, 'index', []);
Router::add('GET', '/hallo', HomeController::class, 'hallo', [AuthMiddlewareImpl::class]);

Router::add('GET', '/users/register', UserController::class, 'register', []);
Router::add('POST', '/users/register', UserController::class, 'postRegister', []);
Router::add('GET', '/users/login', UserController::class, 'login', []);
Router::add('POST', '/users/login', UserController::class, 'postLogin', []);
Router::add('GET', '/users/profile', UserController::class, 'profile', []);
Router::add('GET', '/users/logout', UserController::class, 'logout', []);

Router::add('GET', '/hey', HomeController::class, 'hey', [AuthMiddlewareImpl::class]);
Router::add('GET', '/products/([0-9a-zA-Z]*)/categories/([0-9a-zA-Z]*)', ProductController::class, 'categories', [AuthMiddlewareImpl::class]);

Router::run();


<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// Simple autoloader
// spl_autoload_register(function ($className) {
//     // Convert namespace to file path
//     $className = str_replace('App\\', '', $className); // Replace "App\" with ""
//     $className = str_replace('\\', '/', $className); // Replace "\" with "/"
//     $file = __DIR__ . '/../src/' . $className . '.php'; // Look in the src directory
    
//     if (file_exists($file)) {
//         require_once $file;
//     } else {
//         echo "Failed to load class: $className (File: $file)<br>";
//     }
// });

//! Autoload classes
spl_autoload_register(function ($className) {
       // Replace namespace separator with directory separator
    $className = str_replace('\\', '/', $className);
    
    // Remove 'App/' prefix if it exists to map to the 'src/' directory
    if (strpos($className, 'App/') === 0) {
        $className = 'src/' . substr($className, 4);
    }
    
    $file = __DIR__ . '/../' . $className . '.php';
    
    //! When u want to debug something when wrong with route , plz uncomment this
    //echo "Looking for class: $className at path: $file<br>";
    
    if (file_exists($file)) {
        require_once $file;
        //! For debug too
        //echo "Found and loaded!<br>";
    } else {
        echo "File not found!<br>";
    }
});


// Create router instance
$router = new App\Core\Router();

//* Admin dashboard routes
// $router->get('/', 'App\Controllers\Admin\DashboardController@index');
$router->get('/admin', 'App\Controllers\Admin\DashboardController@index');
$router->get('/admin/dashboard', 'App\Controllers\Admin\DashboardController@index');

// //* User Module
$router->get('/admin/users', 'App\Controllers\Admin\UserController@index');

// //* Auth Module
$router->get("/admin/login", 'App\Controllers\Admin\AuthController@loginPage');
$router->post('/admin/login', 'App\Controllers\Admin\AuthController@login');
$router->get('/admin/logout', 'App\Controllers\Admin\AuthController@logout');

//* Job Management
$router->get('/admin/jobs', 'App\Controllers\Admin\JobController@index');
$router->get('/admin/jobs/create', 'App\Controllers\Admin\JobController@create');
$router->post('/admin/jobs/store', 'App\Controllers\Admin\JobController@store');
$router->get('/admin/jobs/edit/:id', 'App\Controllers\Admin\JobController@edit');
$router->post('/admin/jobs/update/:id', 'App\Controllers\Admin\JobController@update');
$router->get('/admin/jobs/view/:id', 'App\Controllers\Admin\JobController@view');
$router->post('/admin/jobs/delete/:id', 'App\Controllers\Admin\JobController@delete');


//* SaveJob Module
$router->get('/admin/save-jobs', 'App\Controllers\Admin\SaveJobController@index');
$router->get('/admin/save-jobs/delete/:id', 'App\Controllers\Admin\SaveJobController@delete');

//!Front
$router->get('/', 'App\Controllers\Front\HomeController@index');
$router->get('/jobs', 'App\Controllers\Front\JobController@index');
$router->get('/jobs/category/:slug', 'App\Controllers\Front\JobController@browseByCategory');
$router->get('/jobs/:slug', 'App\Controllers\Front\JobController@view');
$router->get('/companies', 'App\Controllers\Front\CompanyController@index');
$router->get('/companies/:slug', 'App\Controllers\Front\CompanyController@view');
$router->post('/jobs/report', 'App\Controllers\Front\JobReportController@submit');
$router->post('/jobs/save', 'App\Controllers\Front\SaveJobController@toggle');

// Candidate routes
$router->get('/saved-jobs', 'App\Controllers\Front\SaveJobController@mySavedJobs');

// Auth routes
$router->get('/auth/login', 'App\Controllers\Front\AuthController@loginPage');
$router->post('/auth/login', 'App\Controllers\Front\AuthController@login');
$router->get('/auth/register', 'App\Controllers\Front\AuthController@registerPage');
$router->post('/auth/register', 'App\Controllers\Front\AuthController@register');
$router->get('/auth/logout', 'App\Controllers\Front\AuthController@logout');

// Dispatch request
$router->dispatch();
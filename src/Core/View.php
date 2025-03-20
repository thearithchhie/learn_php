<?php
namespace App\Core;

class View {
    public static function render($view, $data = []) {
        // Extract data to make variables available in the view
        extract($data);
        
        // Build the view path
        $viewPath = __DIR__ . '/../../views/' . $view . '.php';
        
        // Check if view exists
        if (!file_exists($viewPath)) {
            throw new \Exception("View {$view} not found.");
        }
        
        // Include the view
        require $viewPath;
    }
}
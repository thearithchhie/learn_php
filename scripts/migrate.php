<?php
// scripts/migrate.php

// Load the database configuration
$config = require_once __DIR__ . '/../config/database.php';

// Check if config is properly loaded
if (!is_array($config)) {
    die("Error: Database configuration is not properly formatted. Check your config file.\n");
}

try {
    // Create the DSN string for PostgreSQL
    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    
    // Create the database connection
    $pdo = new PDO($dsn, $config['username'], $config['password'], $options);
    
    // Create migrations table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id SERIAL PRIMARY KEY,
            migration VARCHAR(255) NOT NULL UNIQUE,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Get already executed migrations
    $stmt = $pdo->query("SELECT migration FROM migrations");
    $executedMigrations = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Get all SQL files from migrations directory
    $migrations = glob(__DIR__ . '/../migrations/*.sql');
    
    if (empty($migrations)) {
        echo "No migration files found in the migrations directory.\n";
        exit;
    }
    
    sort($migrations); // Sort files to ensure sequential execution
    
    $newMigrationsRun = 0;
    
    foreach ($migrations as $migration) {
        $migrationName = basename($migration);
        
        // Skip already executed migrations
        if (in_array($migrationName, $executedMigrations)) {
            echo "Skipping {$migrationName} (already executed)\n";
            continue;
        }
        
        $sql = file_get_contents($migration);
        echo "Running migration: {$migrationName}\n";
        
        // Start transaction
        $pdo->beginTransaction();
        
        try {
            // Execute the migration
            $pdo->exec($sql);
            
            // Record the migration
            $stmt = $pdo->prepare("INSERT INTO migrations (migration) VALUES (?)");
            $stmt->execute([$migrationName]);
            
            // Commit the transaction
            $pdo->commit();
            echo "Migration completed successfully.\n";
            $newMigrationsRun++;
        } catch (Exception $e) {
            // Roll back the transaction on error
            $pdo->rollBack();
            echo "Migration failed: " . $e->getMessage() . "\n";
            exit(1);
        }
    }
    
    if ($newMigrationsRun > 0) {
        echo "{$newMigrationsRun} new migrations executed successfully.\n";
    } else {
        echo "No new migrations to execute.\n";
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage() . PHP_EOL);
}
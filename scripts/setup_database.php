<?php
// scripts/setup_database.php
// Script to set up the database with tables and sample data

require_once __DIR__ . '/../config/config.php';
$config = require_once __DIR__ . '/../config/database.php';

echo "Setting up database...\n";

try {
    // Connect to PostgreSQL
    $dsn = "pgsql:host={$config['host']};port={$config['port']};dbname={$config['database']};";
    $pdo = new PDO($dsn, $config['username'], $config['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    
    echo "Connected to database successfully.\n";
    
    // Check if tables already exist
    $result = $pdo->query("SELECT to_regclass('public.users') IS NOT NULL as exists")->fetch();
    $tablesExist = $result['exists'];
    
    if (!$tablesExist) {
        // Read and execute the migration SQL
        $sql = file_get_contents(__DIR__ . '/../migrations/001_create_tables.sql');
        $pdo->exec($sql);
        echo "Tables created successfully.\n";
    } else {
        echo "Tables already exist, skipping creation.\n";
    }
    
    // Insert sample admin user
    $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $pdo->exec("INSERT INTO users (name, email, password, user_type, status, email_verified) 
                VALUES ('Admin User', 'admin@example.com', '$hashedPassword', 'admin', 'active', TRUE)
                ON CONFLICT (email) DO NOTHING");
    
    // Insert sample job seekers
    $pdo->exec("INSERT INTO users (name, email, password, user_type, status, email_verified) 
                VALUES 
                ('John Doe', 'john@example.com', '$hashedPassword', 'job_seeker', 'active', TRUE),
                ('Jane Smith', 'jane@example.com', '$hashedPassword', 'job_seeker', 'active', TRUE)
                ON CONFLICT (email) DO NOTHING");
    
    // Insert sample employer
    $pdo->exec("INSERT INTO users (name, email, password, user_type, status, email_verified) 
                VALUES ('Employer User', 'employer@example.com', '$hashedPassword', 'employer', 'active', TRUE)
                ON CONFLICT (email) DO NOTHING");
    
    // Check if employer exists and get ID
    $employerId = $pdo->query("SELECT id FROM users WHERE email = 'employer@example.com'")->fetchColumn();
    
    if ($employerId) {
        // Check if company already exists for this employer
        $companyExists = $pdo->query("SELECT COUNT(*) FROM companies WHERE user_id = $employerId")->fetchColumn();
        
        if (!$companyExists) {
            // Insert sample company
            $pdo->exec("INSERT INTO companies (user_id, name, industry, location, size, status) 
                        VALUES 
                        ($employerId, 'Tech Company Inc.', 'Technology', 'San Francisco, CA', '50-100', 'verified')");
        }
    }
    
    // Insert sample categories
    $pdo->exec("INSERT INTO categories (name, slug, description) 
                VALUES 
                ('Web Development', 'web-development', 'Jobs related to web development'),
                ('Mobile Development', 'mobile-development', 'Jobs related to mobile app development'),
                ('Data Science', 'data-science', 'Jobs related to data science and analytics')
                ON CONFLICT (slug) DO NOTHING");
    
    echo "Sample data inserted successfully.\n";
    echo "Database setup complete!\n";
    
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage() . "\n";
    exit(1);
} 
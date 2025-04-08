<?php
include_once "./database/Database.php";

$db = new Database();
$conn = $db->getConnection();


$queryCreateUsersTable = "CREATE TABLE IF NOT EXISTS users(
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(100) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP 
)";

if (!$conn->query($queryCreateUsersTable)) {
    echo "create table users has something when wrong". $conn->error;
} else {
    echo "table [users] create success\n";
}

// perform something query
$queryCreateTodosTable = "CREATE TABLE IF NOT EXISTS todos(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    is_completed BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    updated_at TIMESTAMP,
    updated_by INT,
    deleted_at TIMESTAMP,
    deleted_by INT,
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id),
    FOREIGN KEY (deleted_by) REFERENCES users(id)
)";

if (!$conn->query($queryCreateTodosTable)) {
    echo "create table todos has something when wrong". $conn->error;
} else {
    echo "table [todos] create success\n";
}


// INSERT 2 users
$queryInsertNewUser = "
    INSERT INTO users(username,password)
                VALUES
                    ('admin','123'),
                    ('hr','123');
";

if (!$conn->query($queryInsertNewUser)) {
    echo "insert not success". $conn->error;
}

// insert 2 todos by admin user
$queryInsertTodosWithAdminUser = "
    INSERT INTO todos(user_id,title,created_by)
            VALUES
            ((SELECT id FROM users WHERE username = 'admin' LIMIT 1), 'todos-1',(SELECT id FROM users WHERE username = 'admin'  LIMIT 1)),
            ((SELECT id FROM users WHERE username = 'admin'  LIMIT 1), 'todos-2',(SELECT id FROM users WHERE username = 'admin'  LIMIT 1));
";
if (!$conn->query($queryInsertTodosWithAdminUser)) {
    echo "insert todos not success". $conn->error;
}




$db->closeConnection();
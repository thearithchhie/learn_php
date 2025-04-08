<?php

use function PHPSTORM_META\type;

include_once "./database/Database.php";

if($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST["action"])) {
    session_start();
    //isset(): Returns true if $variable exists and is not null
    $title = trim($_POST["title"]);
    $isCompleted = isset($_POST["is_completed"]) ? 1 : 0;

    try {
        $db = new Database();
        $conn = $db->getConnection();

        $message_error = "";

        // remove spacial character from about form , Doc: https://stackoverflow.com/questions/9814642/can-mysql-real-escape-string-alone-prevent-all-kinds-of-sql-injection
        $safeTitle = $conn->real_escape_string($title);
        $safeIsCompleted = $conn->real_escape_string($isCompleted);

        // admin user 
        $result = $conn->query("SELECT id FROM users WHERE username = 'admin'");
        $row = $result->fetch_assoc(); // fetch one record
        $userId = $row["id"];

        // prepare sql , for prevent SQL Injection
        $sql = "INSERT INTO todos(user_id,title,is_completed, created_at, created_by) 
                VALUES(?,?,?,NOW(),?);
            ";
        $stmt = mysqli_prepare($conn, $sql);
        // bind 
        mysqli_stmt_bind_param($stmt,'isii',$userId, $title, $isCompleted, $userId);

        //Execute
        // Execute
        if(mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            $db->closeConnection();
            // Redirect to success page
            header("Location: index.php");
            exit();
        } else {
            $error = mysqli_stmt_error($stmt);
            // Store error in session
            $_SESSION['error_message'] = $error;
            
            mysqli_stmt_close($stmt);
            $db->closeConnection();
            // Redirect to error page
            header("Location: error_page.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error_message'] = $e->getMessage();
        // Redirect to error page
        header("Location: error_page.php");
        exit();
    }
}

//View
if (isset($_GET["method"]) && $_GET["method"] == "GET" && isset($_GET["action"]) && $_GET["action"] == "view") {
    try {
        $db = new Database();
        $conn = $db->getConnection();

        $id = isset($_GET["id"]);

        $sql = "SELECT * FROM todos WHERE id = ? AND deleted_at IS NULL";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt,'i',$id);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $todo = mysqli_fetch_assoc($result);
            
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <link rel="stylesheet" href="./public/css/style.css">
                <title>View Todo</title>
            </head>
            <body>
                <div class="container">
                    <h1>Todo Details</h1>
                    <div class="todo-details">
                        <p><strong>ID:</strong> <?php echo $todo['id']; ?></p>
                        <p><strong>Title:</strong> <?php echo htmlspecialchars($todo['title']); ?></p>
                        <p><strong>Status:</strong> <?php echo $todo['is_completed'] ? 'Completed' : 'Pending'; ?></p>
                        <p><strong>Created:</strong> <?php echo date("F j, Y, g:i a", strtotime($todo['created_at'])); ?></p>
                        
                        <?php if (!empty($todo['updated_at'])): ?>
                        <p><strong>Updated:</strong> <?php echo date("F j, Y, g:i a", strtotime($todo['updated_at'])); ?></p>
                        <?php endif; ?>
                        
                        <a href="index.php">Back to List</a>
                    </div>
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Todo not found";
        }

        mysqli_stmt_close($stmt);
        $db->closeConnection();
    } catch(Exception $e) {
        echo "Error viewing todo: " . $e->getMessage();
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "update") {

    $id = isset($_POST["id"]) ? $_POST["id"] : 0;

    $title = trim($_POST["title"]);
    $is_completed = isset($_POST["is_completed"]) ? 1 : 0;
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        $sqlCheck = "SELECT COUNT(*) as count FROM todos WHERE id = ? AND deleted_at IS NULL";
        $checkStmt = mysqli_prepare($conn, $sqlCheck);
        mysqli_stmt_bind_param($checkStmt, 'i', $id);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);
        $row = mysqli_fetch_assoc($checkResult);

        if($row["count"] == 0) {
            echo "record not found";
        }
       
        $sqlUpdateTodo = "UPDATE todos SET title = ?, is_completed = ?, updated_at = NOW() WHERE id = ?";

        
        $stmtUpdate = mysqli_prepare($conn,  $sqlUpdateTodo);
        mysqli_stmt_bind_param($stmtUpdate, 'sii', $title, $is_completed, $id);
       
        if(mysqli_stmt_execute($stmtUpdate)) {
            $result = mysqli_stmt_get_result($stmtUpdate);
            mysqli_stmt_close($stmtUpdate);
            var_dump($result); 
            $db->closeConnection();
            header("location: index.php");
            exit();
        }else {
            throw new Exception("Update failed: " . mysqli_stmt_error($updateStmt));
        }
    }catch(Exception $e) {
        throw new Exception("update todo unsuccess". $e->getMessage());
    }


}

// Update
    if(isset($_GET["method"]) && $_GET["method"] == "GET" && isset($_GET["action"]) && $_GET["action"] == "edit") {
        $db = new Database();
        $conn = $db->getConnection();
        
        $id = isset($_GET["id"]) ? $_GET["id"] : 0;
        if ($id) {
            try {
                $sql = "SELECT * FROM todos WHERE id=? AND deleted_at IS NULL";
                $stmt = mysqli_prepare($conn, $sql);
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);

                $result = mysqli_stmt_get_result($stmt);
                if($result && mysqli_num_rows($result) > 0) {
                    $todo = mysqli_fetch_assoc($result);
                
                    ?>
                        <!DOCTYPE html>
                        <html lang="en">
                        <head>
                            <meta charset="UTF-8">
                            <meta name="viewport" content="width=device-width, initial-scale=1.0">
                            <link rel="stylesheet" href="./public/css/style.css">
                            <title>Document</title>
                        </head>
                        <body>
                            <div class="container">
                                <h3 style="text-align: center;">Update Todo</h3>
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <input type="hidden" name="action" value="update">
                                <input type="hidden" name="id" value="<?php echo $todo['id']; ?>">
                                    <label>Title</label>
                                    <input type="text" name="title" value="<?php echo htmlspecialchars($todo["title"]) ?>" /><br/><br/>
                                    <input  type="checkbox" name="is_completed"  <?php echo $todo['is_completed'] ? 'checked' : ''; ?> /><br/>
                                    <label>Complete</label><br/><br/>
                                   <div>
                                  <a href="index.php">
                                  <input type="button" value="Cancel" style="width: 100px;" />
                                  </a>
                                  <input type="submit" value="Update" style="width: 100px;" />
                                   </div>
                                </form>
                            </div>
                        </body>
                        </html>
                    <?php
                }

            } catch(Exception $e) {
                throw new Exception("update todo not success". $e->getMessage());
            }
        }else {
            echo "update todo with this id=$id not correct";
        }
        
    }

    // delete 
    if(isset($_GET["method"]) && $_GET["method"] == "GET" && isset($_GET["action"]) && $_GET["action"] == "delete") {
        // check it exist or not
        
        $id = isset($_GET['id']) ? $_GET['id'] : 0;

        try {
            $db = new Database();
            $conn = $db->getConnection();

            $sqlCheckRecordExist = "SELECT COUNT(*) as count FROM todos WHERE id = ? AND deleted_at IS NULL";
            $checkStmt = mysqli_prepare($conn, $sqlCheckRecordExist);
            mysqli_stmt_bind_param($checkStmt,'i', $id);
            mysqli_stmt_execute($checkStmt);
            $result = mysqli_stmt_get_result($checkStmt);
            if(mysqli_num_rows($result) > 0) {
                mysqli_stmt_close($checkStmt);

                // process delete todos
                $sqlDelete = "UPDATE todos SET deleted_at = NOW() WHERE id=?";
                $stmt = mysqli_prepare($conn, $sqlDelete);
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $db->closeConnection();
                header("location: index.php");
            }

        }catch(Exception $e) {
            throw new Exception("something when wrong when delete todo". $e->getMessage());
        }
    }

?>



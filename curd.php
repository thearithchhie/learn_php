<?php
include_once "./Database/Database.php";

if(isset($_GET["action"])) {
    if ($_GET["action"] == "create" && $_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST["username"];
        $phone = $_POST["phone"];
        insert($username, $phone);
    }
    if ($_GET["action"] == "view" && $_SERVER["REQUEST_METHOD"] == "GET") 
    {
        $id = isset($_GET["id"]) ? $_GET["id"] : 0;
        view($id);
    }
    if ($_GET["action"] == "update" && $_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["id"];
        $username = $_POST["username"];
        $phone = $_POST["phone"];
        update($id, $username, $phone);
    }
}

function insert(string $username, string $phone) {
    $db = new Database();
    $conn = $db->getConnection();

    try {
        $sql = "INSERT INTO users (username, phone) VALUES(?,?)";
        // prepare sql 
        $stmt = mysqli_prepare($conn, $sql); 
        // bind params
        mysqli_stmt_bind_param($stmt, "ss", $username, $phone);
        // execute sql

        $response = array();

       if( mysqli_stmt_execute($stmt)) {
            $userId = mysqli_insert_id($conn);
            $sqlUserExist = "SELECT * FROM users WHERE id =?";
            $stmtGet = mysqli_prepare($conn, $sqlUserExist);
            mysqli_stmt_bind_param($stmtGet,'i', $userId);
            mysqli_stmt_execute($stmtGet);
            $result = mysqli_stmt_get_result($stmtGet);
            $user = mysqli_fetch_assoc($result);
            $response["success"] = true;
            $response["user"] = $user;
           mysqli_stmt_close($stmtGet);
       }else {
        $response["success"] = false;
        $response["user"] = null;
       }
        mysqli_stmt_close($stmt);
        $db->closeConnection();
        echo json_encode($response);
        exit();
    }catch(Exception $e) {
        echo $e->getMessage();
    }
   
}

function  view(int $id){
    $db = new Database();
    $conn = $db->getConnection();

    $response = array();
    $response["success"] = true;

    try{

    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    // Set proper JSON content type header
    header('Content-Type: application/json');
    if($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $response["success"] = true;
        $response["user"] = $user;
    }else {
        $response["success"] = false;
        $response["message"] = "user not found";
    }
    mysqli_stmt_close($stmt);
    $db->closeConnection();
    echo json_encode($response);
    exit();
    }catch(Exception $e) {
        $response["success"] = false;
        $response["message"] = $e->getMessage();
        echo json_encode($response);
        exit();
    }
}

function update(int $id, string $username, string $phone) {
    $response = array();    
    try {
        $db = new Database();
        $conn = $db->getConnection();

        $checkSQL = "SELECT COUNT(*) as count FROM users WHERE id = ?";
        $checkStmt = mysqli_prepare($conn, $checkSQL);
        mysqli_stmt_bind_param($checkStmt, 'i', $id);
        mysqli_execute($checkStmt);
        $result = mysqli_stmt_get_result($checkStmt);
        $row = mysqli_fetch_assoc($result);
        if ($row["count"] == 0) {
            $response["success"] = false;
            $response["message"] = "user not found";
            mysqli_stmt_close($checkStmt);
            $db->closeConnection();
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }

        // update user
        $sqlUpdate = "UPDATE users SET username = ?, phone = ? WHERE id = ?";
        $updateStmt = mysqli_prepare($conn, $sqlUpdate);
        mysqli_stmt_bind_param($updateStmt, "ssi", $username, $phone, $id);
        if(mysqli_execute($updateStmt)) {
            $sqlGetAllUser = "SELECT * FROM users WHERE id = ?";
            $selectUserStmt =  mysqli_prepare($conn, $sqlGetAllUser);
            mysqli_stmt_bind_param($selectUserStmt, 'i', $id);
            mysqli_execute($selectUserStmt);
            $selectUserResult = mysqli_stmt_get_result($selectUserStmt);
            $user = mysqli_fetch_assoc($selectUserResult);

            $response["success"] = true;
            $response["user"] = $user;
            $db->closeConnection();

            header("Content-Type: application/json");
            echo json_encode($response);
            exit();
        }

     $db->closeConnection();
     $response["success"] = false;
     $response["user"] = "update failed";
     header("Content-Type: application/json");
     echo json_encode($response);
     exit();
      exit();  
    }catch(Exception $e) {
        throw new Exception($e->getMessage());
    }
}

function delete(int $id) {
    
}
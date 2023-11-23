<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type");
    
    include 'dbConnect.php';
    $objDb = new DbConnect;
    $conn = $objDb->connect();

    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($contentType === "application/json") {
        //Receive the RAW post data.
        $content = trim(file_get_contents("php://input"));
        $user = json_decode($content, true);
    } else {
        $user = $_POST;
    }
    
    $method = $_SERVER['REQUEST_METHOD'];

    switch($method) {
        case 'GET':
            $sql = "SELECT * FROM users";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($users);
            break;

        case 'POST':
            $passwordHash = password_hash($user['password'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $user['email']);
            $stmt->bindParam(':password', $passwordHash);
            $stmt->execute();
            echo json_encode($user);
            break;
            
        case 'PUT':
            $sql = "UPDATE users SET ";
            if (isset($user['email'])) {
                $sql .= "email = :email, ";
            }
            if (isset($user['password'])) {
                $sql .= "password = :password, ";
            }
            $sql = rtrim($sql, ', ');
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            if (isset($user['email'])) {
                $stmt->bindParam(':email', $user['email']);
            }
            if (isset($user['password'])) {
                $stmt->bindParam(':password', $user['password']);
            }
            $stmt->bindParam(':id', $user['id']);
            $stmt->execute();
            echo json_encode($user);
            break;
            
        case 'DELETE':
            $sql = "DELETE FROM users WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $user['id']);
            $stmt->execute();
            echo json_encode($user);
            break;
        }
?>     
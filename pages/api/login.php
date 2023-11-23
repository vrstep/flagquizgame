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
        case 'POST':
            $sql = "SELECT * FROM users WHERE email = :email AND password = :password";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $user['email']);
            $stmt->bindParam(':password', $user['password']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Login successful']);
            } else {
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Wrong email or password']);
            }
            break;
    }
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
            // Validate email
            if (empty($user['email'])) {
                echo json_encode(['status' => 'error', 'message' => 'Please fill out the email field']);
                exit();  // Stop the script
            } elseif (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
                exit();  // Stop the script
            }

            // Validate password
            if (empty($user['password'])) {
                echo json_encode(['status' => 'error', 'message' => 'Please fill out the password field']);
                exit();  // Stop the script
            }

            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $user['email']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($result && password_verify($user['password'], $result['password'])) {
                echo json_encode(['status' => 'success', 'message' => 'Login successful']);
            } else {
                http_response_code(401);
                echo json_encode(['status' => 'error', 'message' => 'Wrong email or password']);
            }
            break;        
    }
?>

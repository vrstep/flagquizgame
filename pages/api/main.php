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

            // Check if email already exists
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $user['email']);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                echo json_encode(['status' => 'error', 'message' => 'Email already exists']);
                exit();  // Stop the script
            }

            $passwordHash = password_hash($user['password'], PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $user['email']);
            $stmt->bindParam(':password', $passwordHash);
            $stmt->execute();
            echo json_encode(['status' => 'success', 'message' => 'User registered successfully']);
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

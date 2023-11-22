<?php

    header("Access-Control-Allow-Origin: *");
    include 'dbConnect.php';

    $objDb = new DbConnect;
    $conn = $objDb->connect();

    $user = $_POST;
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
            $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $user['email']);
            $stmt->bindParam(':password', $user['password']);
            $stmt->execute();
            echo json_encode($user);
            break;
        case 'PUT':
            $sql = "UPDATE users SET email = :email, password = :password WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':email', $user['email']);
            $stmt->bindParam(':password', $user['password']);
            $stmt->bindParam(':id', $user['id']);
            $stmt->execute();
            echo json_encode($user);
            break;
    }
?>

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
        $flag = json_decode($content, true);
    } else {
        $flag = $_POST;
    }
    
    $method = $_SERVER['REQUEST_METHOD'];

    switch($method) {
        case 'GET':
            $sql = "SELECT id, country, code FROM flags";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $flags = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($flags as $i => $flag) {
                $sql = "SELECT image FROM flags WHERE id = :id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id', $flag['id']);
                $stmt->execute();
                $image = $stmt->fetchColumn();
                $flags[$i]['image'] = base64_encode($image);
            }
            echo json_encode($flags);
            break;

            case 'POST':
                // Validate country name
                if (empty($flag['country'])) {
                    echo json_encode(['status' => 'error', 'message' => 'Please fill out the country field']);
                    exit();  // Stop the script
                }
            
                // Validate country code
                if (empty($flag['code'])) {
                    echo json_encode(['status' => 'error', 'message' => 'Please fill out the country code field']);
                    exit();  // Stop the script
                }
            
                // Validate image
                if (!isset($_FILES['image'])) {
                    echo json_encode(['status' => 'error', 'message' => 'Image file is required']);
                    exit();  // Stop the script
                }
            
                // Check if flag already exists
                $sql = "SELECT * FROM flags WHERE country = :country";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':country', $flag['country']);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if ($result) {
                    echo json_encode(['status' => 'error', 'message' => 'Flag already exists']);
                    exit();  // Stop the script
                }
            
                // Insert new flag
                $image = file_get_contents($_FILES['image']['tmp_name']);
                $sql = "INSERT INTO flags (country, code, image) VALUES (:country, :code, :image)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':country', $flag['country']);
                $stmt->bindParam(':code', $flag['code']);
                $stmt->bindParam(':image', $image, PDO::PARAM_LOB);
                $stmt->execute();
                echo json_encode(['status' => 'success', 'message' => 'Flag added successfully']);
                break;
            

            $sql = "INSERT INTO flags (country, code, image) VALUES (:country, :code, :image)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':country', $flag['country']);
            $stmt->bindParam(':code', $flag['code']);
            $stmt->bindParam(':image', $flag['image']);
            $stmt->execute();
            echo json_encode(['status' => 'success', 'message' => 'Flag added successfully']);
            break;
            
        case 'PUT':
            $sql = "UPDATE flags SET ";
            if (isset($flag['country'])) {
                $sql .= "country = :country, ";
            }
            if (isset($flag['code'])) {
                $sql .= "code = :code, ";
            }
            if (isset($flag['image'])) {
                $sql .= "image = :image, ";
            }
            $sql = rtrim($sql, ', ');
            $sql .= " WHERE id = :id";
            $stmt = $conn->prepare($sql);
            if (isset($flag['country'])) {
                $stmt->bindParam(':country', $flag['country']);
            }
            if (isset($flag['code'])) {
                $stmt->bindParam(':code', $flag['code']);
            }
            if (isset($flag['image'])) {
                $stmt->bindParam(':image', $flag['image']);
            }
            $stmt->bindParam(':id', $flag['id']);
            $stmt->execute();
            echo json_encode($flag);
            break;
                
        case 'DELETE':
            $sql = "DELETE FROM flags WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $flag['id']);
            $stmt->execute();
            echo json_encode(['status' => 'success', 'message' => 'Flag deleted successfully']);
            break;
    }
?>

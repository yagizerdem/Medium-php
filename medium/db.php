<?php
class Database {
    private $servername = "localhost";
    private $username = "root"; // default username
    private $password = ''; // default password
    private $dbname = "medium";
    private $port = 3307;
    private $conn = null;


    function __construct() {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname , $this->port);
        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Method to register a user
    function registerUser($firstName, $lastName, $email, $password) {
        $isUserAlreadyRegistered = $this->getUserByEmail($email);
        if($isUserAlreadyRegistered){
            return ['success' => false, 'message' => "Execution failed: " . "user already registered ..."];
        }
        // Prepare and bind
        $stmt = $this->conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }

        // Hash the password for security
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Bind parameters
        $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
    
        // Execute the statement
        if ($stmt->execute()) {
            // Close the statement
            $stmt->close();
            return ['success' => true, 'message' => "Registration successful!"];
        } else {
            // Close the statement
            $stmt->close();
            return ['success' => false, 'message' => "Execution failed: " . $stmt->error];
        }
    }
    function getUserByEmail($email){
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        // Fetch the user data
        if ($user = $result->fetch_assoc()) {
            $stmt->close();
            // User found
            return $user;
        } else {
            // User not found
            $stmt->close();
            return null;
        }
    }
    function insertPost($postModel){
        $userId = intval($postModel->userId);
        $stmt = $this->conn->prepare("INSERT INTO posts (title, small_description, body, header_image_path ,userId) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("ssssi", $postModel->title, $postModel->smallDescription,$postModel->body , $postModel->headerImagePath, $userId);
        // Execute the statement
        if ($stmt->execute()) {
            // Close the statement
            $result = ['success' => true, 'data' => "post inserted successsfull ..."];
        } else {
            // Close the statement
            $result =['success' => false, 'message' => "Execution failed: " . $stmt->error];
        }
        $stmt->close();
        return $result ;
    }
    
    // Destructor to close the connection
    function __destruct() {
        $this->conn->close();
    }
    function _getPostWithPagination($limit, $page) {
        // Calculate the offset for pagination
        $offset = $page * $limit;
    
        // Prepare the SQL statement with pagination
        $stmt = $this->conn->prepare("SELECT posts.* , users.first_name , users.last_name , users.email FROM posts LEFT JOIN users ON posts.userId = users.Id LIMIT ?, ?");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
    
        // Bind parameters (offset and limit)
        $stmt->bind_param("ii", $offset, $limit);
    
        // Execute the statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $posts = $result->fetch_all(MYSQLI_ASSOC); // Fetch all posts as an associative array
    
            // Return the result
            $stmt->close();
            return [
                'success' => true,
                'data' => $posts
            ];
        } else {
            // Handle execution failure
            $stmt->close();
            return [
                'success' => false,
                'message' => "Execution failed: " . $stmt->error
            ];
        }
    }
    function _getPostById($postId){
        $stmt = $this->conn->prepare("SELECT  posts.* , users.first_name , users.last_name , users.email FROM posts LEFT JOIN users ON posts.userId = users.Id WHERE posts.Id = ?");
        if ($stmt === false) {
            die("Prepare failed: " . $this->conn->error);
        }
        $stmt->bind_param("i", $postId);
        // Execute the statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $post = $result->fetch_all(MYSQLI_ASSOC); // Fetch all posts as an associative array
    
            // Return the result
            $stmt->close();
            return [
                'success' => true,
                'data' => $post
            ];
        } else {
            // Handle execution failure
            $stmt->close();
            return [
                'success' => false,
                'message' => "Execution failed: " . $stmt->error
            ];
        }

    }

}



?>
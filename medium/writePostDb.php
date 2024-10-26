<?php
session_start();
if(!$_SESSION["isAuthenticated"]){
    header("location: index.php");
};
?>
<?php
    require("./db.php");
    require("./models/postModel.php");
    $db = new Database();
// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the data from the POST request
    $title = $_POST['title'] ?? '';
    $smallDescription = $_POST['smallDescription'] ?? '';
    $body = $_POST['body'] ?? '';

    $uniqueFileName = "";
    //validat post model
    $postModel = new Post($title , $smallDescription);
    $result =  $postModel->validate();
    if($result['success'] == false){
        // Handle invalid request method
        http_response_code(422); 
        echo json_encode(['status' => 'error', 'message' => $result['message']]);
        exit();
    }
    //

    // process file 
    if (isset($_FILES['headerImage'])) {
        $headerImage = $_FILES['headerImage'];
          // Check for errors
          if ($headerImage['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/'; // Ensure this directory exists
            $fileExtension = pathinfo($headerImage['name'], PATHINFO_EXTENSION);
            $uniqueFileName = md5(uniqid(rand(), true)) . '.' . $fileExtension;
            $uploadFile = $uploadDir . $uniqueFileName ;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true); // Create the directory if it doesn't exist
            }

            // Move the uploaded file to the designated directory
            if (!move_uploaded_file($headerImage['tmp_name'], $uploadFile)) {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to move uploaded file'
                ];
            } 
    }
}
    // You can now process the data, for example, save it to a database
    // For demonstration purposes, we'll just return the received data
    
    $userId = $_SESSION['id'];
    $postModel = new Post($title , $smallDescription , json_encode($body) , $uniqueFileName , $userId);

    // validate 

    $result =  $db->insertPost($postModel);
    if($result['success']){
        $response = [
            'status' => 'success',
            'data' =>  $result['data']
        ];
            // Set the content type to JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    }
    else{
        // Handle invalid request method
        http_response_code(422); 
        echo json_encode(['status' => 'error', 'message' => $result['message']]);
    }


} else {
    // Handle invalid request method
    http_response_code(405); // Method Not Allowed
    echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
}
?>
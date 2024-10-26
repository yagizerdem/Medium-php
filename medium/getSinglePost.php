<?php
require('./db.php');
$all_headers = getallheaders();
$postId = isset($all_headers['postId']) ? (int)$all_headers['postId'] : -1;
$db = new Database();

$result = $db->_getPostById($postId);


header('Content-Type: application/json');
echo json_encode($result);
?>

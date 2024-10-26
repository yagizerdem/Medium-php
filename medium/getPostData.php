<?php
require('./db.php');
$all_headers = getallheaders();
$pageNumber = isset($all_headers['pageNumber']) ? (int)$all_headers['pageNumber'] : 0;
$db = new Database();
$limit = 10;
$result = $db->_getPostWithPagination($limit,$pageNumber);


header('Content-Type: application/json');
echo json_encode($result);
?>
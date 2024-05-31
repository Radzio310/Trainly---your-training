<?php
session_start();

$response = array();

if (!isset($_SESSION['user_id'])) {
    $response['status'] = 'expired';
} else {
    $response['status'] = 'active';
}

echo json_encode($response);
?>
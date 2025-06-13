<?php
require_once 'config.php';
session_start();

function get_login_user() {
    global $db;
    if (!isset($_SESSION['user_id'])) return null;
    $uid = intval($_SESSION['user_id']);
    $sql = "SELECT * FROM users WHERE id=$uid";
    $row = $db->query($sql)->fetch_assoc();
    return $row;
}

function is_user_vip($user_id) {
    global $db;
    $sql = "SELECT vip FROM users WHERE id=$user_id";
    $row = $db->query($sql)->fetch_assoc();
    return isset($row['vip']) && $row['vip'] == 1;
}
?>
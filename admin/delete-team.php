<?php

session_start();
if (empty($_SESSION['admin'])) {
  header('Location: /admin/login.php');
  die();
}

require_once '../db.php';
$lib = new Database();
$db = $lib->getConnection();

$id = $_GET['id'];

if (isset($id) && intval($id) > 0) {
  $stmt = $db->prepare('DELETE FROM teams WHERE id = :id');
  $stmt->bindParam(':id', $id);
  $stmt->execute();
}

header('Location: /admin/teams.php');
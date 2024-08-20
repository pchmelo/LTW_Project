<?php
  declare (strict_types= 1);
    require_once(__DIR__ . '/../utils/session.php');
    session_start();
    session_destroy();
    header("Location: ../pages");
    exit;
?>
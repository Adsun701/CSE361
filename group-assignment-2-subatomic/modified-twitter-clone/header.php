<?php
  require_once 'functions.php';

  session_set_cookie_params(['SameSite' => 'Lax', 'Secure' => true, 'path' => 'subatomic.cse361-spring2023.com']);
  session_start();
  header('Set-Cookie: ' . session_name() . '=' . session_id() . '; SameSite=Lax; Secure');
  require_once 'msg_dal.php';
  require_once 'components/navbar.php';
?>

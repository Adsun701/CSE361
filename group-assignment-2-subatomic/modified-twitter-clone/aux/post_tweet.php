<?php
  require_once "../msg_dal.php";
  session_start();

if (isset($_POST["txt_msg"]) && isset($_POST['token']) && isset($_SESSION["userID"])) {
  $token = $_POST["token"];
  $msg_text = $_POST["txt_msg"];
  $author = $_SESSION["userID"];
  echo "hello";

  // Save the message into the database
  postNewMsg($author, $msg_text, $token, $connect);
}

redirect("../index.php");
?>

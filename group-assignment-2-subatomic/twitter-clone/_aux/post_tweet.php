<?php
  require_once "../msg_dal.php";
  session_start();

if (isset($_POST["txt_msg"]) && isset($_SESSION["userID"])) {
  $msg_text = $_POST["txt_msg"];
  $author = $_SESSION["userID"];
  echo "hello";
  // Save the message into the database
  postNewMsg($author, $msg_text, $connect);
}

redirect("../index.php");
?>

<?php
session_start();
  require_once 'functions.php';


  function postNewMsg($author, $message, $token, $db) {
    if ($token === $_SESSION['token']) {
    $sanitized_message = htmlspecialchars($message);
    $db->query("INSERT INTO messages(author, msg_text) VALUES($author, '$sanitized_message');");
    }
  }

  function delMsg($messageID, $db) {
    $db->query("DELETE FROM messages WHERE id=$messageID ;");
  }

  function getAllMsg($db) {
    $response = $db->query("SELECT * FROM messages");
    return turnQueryToReverseArray($response);
  }

  function getMsgByUserID($user, $db) {
    $response = $db->query("SELECT * FROM messages WHERE author IN (SELECT followed FROM followers WHERE follower=$user) ORDER BY id DESC;");
    return turnQueryToArray($response);
  }

  /**
   * Returns all messages that includes the text passed in the filter
   * @param database: object
   * @param filter: string
   */
  function filterMsgByText($filter, $db) {
    $response = $db->query("SELECT * FROM messages WHERE msg_text LIKE '%$filter%';");
    return turnQueryToReverseArray($response);
  }

  function filterMsgByUname($author, $db) {
    $response = $db->query("SELECT * FROM messages WHERE author=$author ;");
    return turnQueryToReverseArray($response);
  }

  function getAuthorName($db, $author) {
    $response = $db->query("SELECT uname FROM users WHERE id=$author;");
    return $response->fetch_array(MYSQLI_NUM)[0];
  }

  function updateMsgText($msg, $text, $db) {
    $response = $db->query("UPDATE messages SET msg_text='$text' WHERE id=$msg ;");
    return $response;
  }
  ?>

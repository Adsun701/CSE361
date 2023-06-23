<?php
session_start();
  require_once 'functions.php';

  function postNewMsg($author, $message, $token, $db) {
    // check that supplied token is not null.
    if (is_null($token)) {
      return;
    }
    // check that session token exists and is not null.
    if (!array_key_exists('token', $_SESSION) || is_null($_SESSION['token'])) {
      return;
    }

    // check if supplied token is equal to the session token.
    if ($token != $_SESSION['token']) {
      return;
    }

    // check that both the supplied token and the session token are valid.
    $constructed_token_sans_random_bytes = strlen($_SESSION["uname"]) . $_SESSION["uname"];
    $supplied_token_sans_random_bytes = substr($token, 0, -20);
    $session_token_sans_random_bytes = substr($_SESSION['token'], 0, -20);
    if ($constructed_token_sans_random_bytes != $supplied_token_sans_random_bytes || $constructed_token_sans_random_bytes != $session_token_sans_random_bytes) {
      return;
    }

    $supplied_random_bytes = substr($token, -20, 20);
    $session_random_bytes = substr($_SESSION['token'], -20, 20);
    if (!(ctype_xdigit($supplied_random_bytes) && ctype_xdigit($session_random_bytes))) {
      return;
    }

    // now that our token has been checked, sanitize our message before posting.
    $sanitized_message = htmlspecialchars($message);
    $stmt = $db->prepare("INSERT INTO messages(author, msg_text) VALUES(?, ?);");
    $stmt->bind_param("is", $author, $sanitized_message);
    $stmt->execute();
  }

  function delMsg($messageID, $db) {
    $stmt = $db->prepare("DELETE FROM messages WHERE id=?;");
    $stmt->bind_param("i", $messageID);
    $stmt->execute();
  }

  function getAllMsg($db) {
    $stmt = $db->prepare("SELECT * FROM messages;");
    $stmt->execute();
    $response = $stmt->get_result();
    return turnQueryToReverseArray($response);
  }

  function getMsgByUserID($user, $db) {
    $stmt = $db->prepare("SELECT * FROM messages WHERE author IN (SELECT followed FROM followers WHERE follower=?) ORDER BY id DESC;");
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $response = $stmt->get_result();
    return turnQueryToArray($response);
  }

  /**
   * Returns all messages that includes the text passed in the filter
   * @param database: object
   * @param filter: string
   */
  function filterMsgByText($filter, $db) {
    $stmt = $db->prepare("SELECT * FROM messages WHERE msg_text LIKE CONCAT('%', ?, '%')");
    $stmt->bind_param("s", $filter);
    $stmt->execute();
    $response = $stmt->get_result();
    return turnQueryToReverseArray($response);
  }

  function filterMsgByUname($author, $db) {
    $stmt = $db->prepare("SELECT * FROM messages WHERE author=?;");
    $stmt->bind_param("s", $author);
    $stmt->execute();
    $response = $stmt->get_result();
    return turnQueryToReverseArray($response);
  }

  function getAuthorName($db, $author) {
    $stmt = $db->prepare("SELECT uname FROM users WHERE id=?;");
    $stmt->bind_param("i", $author);
    $stmt->execute();
    $response = $stmt->get_result();
    return $response->fetch_array(MYSQLI_NUM)[0];
  }

  function updateMsgText($msg, $text, $db) {
    $stmt = $db->prepare("UPDATE messages SET msg_text=? WHERE id=?;");
    $stmt->bind_param("si", $text, $msg);
    $stmt->execute();
    $response = $stmt->get_result();
    return $response;
  }
  ?>

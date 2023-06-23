<?php
  // Database configuration
  require_once 'dblogin.php';
  $appname = 'Twitter clone';

  /**
   * Connects to MySQL and creates an object to access it
   * @param host
   * @param username
   * @param password
   * @param database
   */
  global $connect;
  $connect = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  if ($connect->connect_error) die($connect->connect_error);

  /**
   * Receives a database response and converts it into an array
   * @param response: mysql response object
   */
  function turnQueryToArray($response) {
    $rows = $response->num_rows;
    $result = [];
    for ($i = 0; $i < $rows; $i++) {
      array_push($result, $response->fetch_array(MYSQLI_NUM));
    }
    return $result;
  }

  function turnQueryToReverseArray($response) {
    $rows = $response->num_rows;
    $result = [];
    for ($i = 0; $i < $rows; $i++) {
      array_unshift($result, $response->fetch_array(MYSQLI_NUM));
    }
    return $result;
  }

  function extractValuesFromNestedArray($array) {
    $newArray = [];
    foreach ($array as $value) {
      array_push($newArray, $value[0]);
    }
    return $newArray;
  }

  /**
   * Checks if a table already exists and if not create it
   * @param $name: String
   * @param $query: String
   */
  function createTable($db, $name, $query) {
    $stmt = $db->prepare("CREATE TABLE (name)((query) VALUES (?, ?);");
    $stmt->bind_param("ss", $name, $query);
    $stmt->execute();
  }

  function createNewUser($db, $username, $password) {
    $stmt = $db->prepare("INSERT INTO users(uname, pwd) VALUES(?, ?);");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
  }

  function checkUserAuth($db, $username, $password) {
    $stmt = $db->prepare("SELECT * FROM users WHERE uname=? AND pwd=?;");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $response = $stmt->get_result();
    return $response;
  }



  // FOLLOW TABLE FUNCTIONS
  /**
   * Add a follow relation into the followers table
   * @param db: Database connection object
   * @param follower: Number (user's id)
   * @param followed: Number (user's id)
   */
  function followUser($db, $follower, $followed) {
    $stmt = $db->prepare("INSERT INTO followers(follower, followed) VALUES(?, ?);");
    $stmt->bind_param("ii", $follower, $followed);
    $stmt->execute();
  }

  /**
   * Removes a follow relation from the followers table
   * @param db: Database connection object
   * @param follower: Number (user's id)
   * @param followed: Number (user's id)
   */
  function unfollowUser($db, $follower, $followed) {
    $stmt = $db->prepare("DELETE FROM followers WHERE follower=? AND followed=? ;");
    $stmt->bind_param("ii", $follower, $followed);
    $stmt->execute();
  }

  function getNumOfFollowers($db, $user) {
    $stmt = $db->prepare("SELECT * FROM followers WHERE followed=? ;");
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $response = $stmt->get_result();
    return $response->num_rows;
  }

  /**
   * Returns an array with the id's of the users the current user follows
   */
  function checkCurrentUserFollows($db, $user) {
    $stmt = $db->prepare("SELECT followed FROM followers WHERE follower=? ;");
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $response = $stmt->get_result();
    return turnQueryToArray($response);
  }

  function getUserFollowers($db, $user) {
    $stmt = $db->prepare("SELECT follower FROM followers WHERE followed=? ;");
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $response = $stmt->get_result();
    return turnQueryToArray($response);
  }

  function checkIfUserFollowsUser($db, $user1, $user2) {
    $stmt = $db->prepare("SELECT * FROM followers WHERE follower=? AND followed=? ;");
    $stmt->bind_param("ii", $user1, $user2);
    $stmt->execute();
    $response = $stmt->get_result();
    return $response->num_rows;
  }

  // LIKES TABLE FUNCTIONS

  /**
   *
   */
  function likeMessage($db, $user, $msg) {
    $stmt = $db->prepare("INSERT INTO likes(user, message) VALUES(?, ?);");
    $stmt->bind_param("is", $user, $msg);
    $stmt->execute();
    $response = $stmt->get_result();
    // $arrayResponse = turnQueryToArray($response);
    // $jsonResponse = json_encode($arrayResponse);
    echo $response;
  }

  function unlikeMessage($db, $user, $msg) {
    $stmt = $db->prepare("DELETE FROM likes WHERE user=? AND message=? ;");
    $stmt->bind_param("is", $user, $msg);
    $stmt->execute();
    $response = $stmt->get_result();
    echo $response;
  }

  function checkIfMessageHasLike($db, $user, $msg) {
    $stmt = $db->prepare("SELECT * FROM likes WHERE user=? AND message=? ;");
    $stmt->bind_param("is", $user, $msg);
    $stmt->execute();
    $response = $stmt->get_result();
    return $response->num_rows;
  }

  function getMessageLikes($db, $msg) {
    $stmt = $db->prepare("SELECT * FROM likes WHERE message=? ;");
    $stmt->bind_param("s", $msg);
    $stmt->execute();
    $response = $stmt->get_result();
    return $response->num_rows;
  }

  function getUserLikes($db, $user) {
    $stmt = $db->prepare("SELECT message FROM likes WHERE user=? ;");
    $stmt->bind_param("i", $user);
    $stmt->execute();
    $response = $stmt->get_result();
    $arrayResponse = turnQueryToArray($response);
    return extractValuesFromNestedArray($arrayResponse);
  }

  function turnLikesArrayToMessages($db, $likes) {
    $response = [];
    foreach($likes as $like) {
      $stmt = $db->prepare("SELECT * FROM messages WHERE id=?");
      $stmt->bind_param("i", $like);
      $stmt->execute();
      $content = $stmt->get_result();
      array_push($response, $content->fetch_array(MYSQLI_NUM));
    }
    return $response;
  }

  //  OTHER FUNCTIONS

  function redirect($url) {
    header('Location: ' . $url);
  }


?>



<?php

  require_once 'functions.php';


  function doLogin($uname, $pwd, $db) {
    $username = $uname;
    $password = $pwd;
    
    // set up prepared statement.
    $stmt = $db->prepare("SELECT * from users where uname=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $response = $stmt->get_result();
    $user = $response->fetch_array(MYSQLI_NUM);
    if ($user && password_verify($password, $user[1])) {
      return $user;
    }
    else {
      return -1;
    }
  }

  function registerNewUser($uname, $pwd, $db) {
    $username = $uname;
    $password = password_hash($pwd, PASSWORD_DEFAULT);

    // set up prepared statement.
    $stmt = $db->prepare("INSERT into users(uname, pwd) values(?, ?);");
    $stmt->bind_param("ss", $username, $password);
    return $stmt->execute();
  }

  ?>

 

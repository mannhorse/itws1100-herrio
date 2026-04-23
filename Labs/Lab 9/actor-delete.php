<?php
  mysqli_report(MYSQLI_REPORT_OFF);
  @$db = new mysqli('localhost', 'phpmyadmin', 'Oliverherrick11!', 'iit');

  if ($db->connect_error) {
    $connectErrors = array(
      'errors' => true,
      'errno' => mysqli_connect_errno(),
      'error' => mysqli_connect_error()
    );
    echo json_encode($connectErrors);
  } else {
    if (isset($_POST["id"])) {
      $actorId = (int) $_POST["id"];

      $query = "delete from actors where actorid = ?";
      $statement = $db->prepare($query);
      $statement->bind_param("i", $actorId);
      $statement->execute();

      $success = array('errors' => false, 'message' => 'Delete successful');
      echo json_encode($success);

      $statement->close();
      $db->close();
    }
  }
?>

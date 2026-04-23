<?php
  include('includes/db.inc.php');
  mysqli_report(MYSQLI_REPORT_OFF);
  @$db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

  if ($db->connect_error) {
    $connectErrors = array(
      'errors' => true,
      'errno' => mysqli_connect_errno(),
      'error' => mysqli_connect_error()
    );
    echo json_encode($connectErrors);
  } else {
    if (isset($_POST["id"])) {
      $movieId = (int) $_POST["id"];

      $query = "delete from movies where movieid = ?";
      $statement = $db->prepare($query);
      $statement->bind_param("i", $movieId);
      $statement->execute();

      $success = array('errors' => false, 'message' => 'Delete successful');
      echo json_encode($success);

      $statement->close();
      $db->close();
    }
  }
?>

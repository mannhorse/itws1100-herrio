<?php
include('includes/init.inc.php');
include('includes/functions.inc.php');
?>
<title>PHP &amp; MySQL - ITWS</title>

<?php include('includes/head.inc.php'); ?>

<h1>PHP &amp; MySQL</h1>

<?php include('includes/menubody.inc.php'); ?>

<h3>Movies &amp; Actors</h3>
<table id="relationsTable">
<?php
   $dbOk = false;

   @$db = new mysqli('localhost', 'root', 'root', 'iit');

   if ($db->connect_error) {
      echo '<div class="messages">Could not connect to the database. Error: ';
      echo $db->connect_errno . ' - ' . $db->connect_error . '</div>';
   } else {
      $dbOk = true;
   }

   if ($dbOk) {

      $query = 'SELECT movies.title, movies.year, actors.first_names, actors.last_name
                FROM movies, actors, movie_actors
                WHERE movie_actors.movieid = movies.movieid
                AND movie_actors.actorid = actors.actorid
                ORDER BY movies.title';

      $result = $db->query($query);
      $numRecords = $result->num_rows;

      echo '<tr><th>Movie:</th><th>Year:</th><th>Actor:</th></tr>';
      for ($i = 0; $i < $numRecords; $i++) {
         $record = $result->fetch_assoc();
         if ($i % 2 == 0) {
            echo "\n" . '<tr>';
         } else {
            echo "\n" . '<tr class="odd">';
         }
         echo '<td>' . htmlspecialchars($record['title']) . '</td>';
         echo '<td>' . htmlspecialchars($record['year']) . '</td>';
         echo '<td>' . htmlspecialchars($record['first_names']) . ' ' . htmlspecialchars($record['last_name']) . '</td>';
         echo '</tr>';
      }

      $result->free();
      $db->close();
   }
?>
</table>

<?php include('includes/foot.inc.php'); ?>

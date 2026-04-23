<?php

function buildMenu() {
  $menu = array(
    'index' => 'actors',
    'movies' => 'movies',
    'relations' => 'relations'
  );
  $menuOutput = '<ul id="menu">';
  foreach ($menu as $key => $value) {
    if (strpos($_SERVER['PHP_SELF'], "$key.php") !== false) {
      $selected = ' class="selected"';
    } else {
      $selected = '';
    }
    $menuOutput .= '<li' . $selected . '><a href="' . $key . '.php" title="' . $value . '">' . $value . '</a></li>';
  }
  $menuOutput .= '</ul>';
  return $menuOutput;
}

?>

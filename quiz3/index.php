<?php
include('includes/db.inc.php');
mysqli_report(MYSQLI_REPORT_OFF);
$db   = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
$dbOk = !$db->connect_error;

$questions = [
    [
        'q'       => 'What is the capital of California?',
        'choices' => ['Los Angeles', 'Sacramento', 'San Francisco', 'San Diego'],
        'correct' => 1,
    ],
    [
        'q'       => 'Which state is known as the "Lone Star State"?',
        'choices' => ['Alaska', 'Hawaii', 'Texas', 'Nevada'],
        'correct' => 2,
    ],
    [
        'q'       => 'What is the capital of Florida?',
        'choices' => ['Miami', 'Orlando', 'Jacksonville', 'Tallahassee'],
        'correct' => 3,
    ],
    [
        'q'       => 'Which is the largest US state by area?',
        'choices' => ['Texas', 'California', 'Montana', 'Alaska'],
        'correct' => 3,
    ],
    [
        'q'       => 'What is the capital of New York state?',
        'choices' => ['New York City', 'Buffalo', 'Albany', 'Syracuse'],
        'correct' => 2,
    ],
    [
        'q'       => 'What is the capital of Texas?',
        'choices' => ['Dallas', 'Houston', 'Austin', 'San Antonio'],
        'correct' => 2,
    ],
    [
        'q'       => 'How many US states are there?',
        'choices' => ['46', '48', '50', '52'],
        'correct' => 2,
    ],
    [
        'q'       => 'Which state was the last admitted to the union (1959)?',
        'choices' => ['New Mexico', 'Arizona', 'Alaska', 'Hawaii'],
        'correct' => 3,
    ],
    [
        'q'       => 'What is the smallest US state by area?',
        'choices' => ['Delaware', 'Connecticut', 'Rhode Island', 'New Jersey'],
        'correct' => 2,
    ],
    [
        'q'       => 'Which state has the longest coastline?',
        'choices' => ['Florida', 'California', 'Texas', 'Alaska'],
        'correct' => 3,
    ],
];

$havePost   = isset($_POST['submit']);
$playerName = '';
$score      = 0;

if ($havePost) {
    // VULNERABILITY — XSS (Cross-Site Scripting)
    // $playerName is never sanitized before being echoed into HTML.
    // Entering <script>alert('hacked')</script> as a name will execute
    // that script in every visitor's browser when the leaderboard loads.
    // Fix: echo htmlspecialchars($playerName, ENT_QUOTES, 'UTF-8') everywhere.
    $playerName = trim($_POST['player_name']);

    foreach ($questions as $i => $q) {
        if (isset($_POST['q' . $i]) && (int)$_POST['q' . $i] === $q['correct']) {
            $score++;
        }
    }

    if ($dbOk) {
        $stmt = $db->prepare('INSERT INTO quiz3_scores (player_name, score) VALUES (?, ?)');
        $stmt->bind_param('si', $playerName, $score);
        $stmt->execute();
        $stmt->close();
    }
}

// VULNERABILITY — SQL Injection
// $search is dropped straight into the query string with no escaping or
// prepared statement.  Try:  ' OR '1'='1   to dump every row in the table.
// Fix: use a prepared statement with bind_param() just like the INSERT above.
$searchResults = null;
$searchTerm    = '';
if (isset($_GET['search']) && $dbOk) {
    $searchTerm = $_GET['search'];
    $searchResults = $db->query(
        "SELECT player_name, score, submitted_at FROM quiz3_scores WHERE player_name = '$searchTerm'"
    );
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <title>US States Quiz</title>
  <link href="../css/style.css" rel="stylesheet" type="text/css"/>
  <style>
    .quiz-wrap        { max-width:720px; margin:40px auto; padding:0 20px; }
    .question         { margin-bottom:18px; border:1px solid #ddd; padding:14px 16px; border-radius:4px; }
    .question p       { font-weight:bold; margin-bottom:8px; }
    .question label   { display:block; margin-bottom:4px; cursor:pointer; }
    .messages         { background:#ffd; border:1px solid #ccc; padding:1em; margin:1em 0; }
    .messages h4      { margin:0 0 6px 0; }
    table             { width:100%; border-collapse:collapse; margin-top:8px; }
    th, td            { text-align:left; padding:6px 10px; border-bottom:1px solid #ddd; }
    th                { background:#f0f0f0; font-weight:bold; }
    .vuln-note        { background:#fff3cd; border-left:4px solid #ffc107; padding:10px 14px;
                        margin:10px 0; font-size:0.9em; border-radius:3px; }
    .vuln-note strong { display:block; margin-bottom:4px; }
    .fix-note         { background:#d4edda; border-left:4px solid #28a745; padding:10px 14px;
                        margin:4px 0 10px 0; font-size:0.9em; border-radius:3px; }
    input[type=submit], button[type=submit] {
                        background:#2079c7; color:#fff; border:none; padding:10px 26px;
                        cursor:pointer; font-size:1em; border-radius:4px; margin-top:8px; }
    input[type=submit]:hover, button[type=submit]:hover { background:#1a62a1; }
    input[type=text]  { padding:6px 8px; font-size:1em; border:1px solid #ccc; border-radius:3px; }
    code              { background:#eee; padding:1px 4px; border-radius:3px; font-size:0.95em; }
    h2                { margin-top:30px; }
  </style>
</head>
<body>
<div class="quiz-wrap">

  <h1>US States Quiz</h1>

  <?php if ($havePost): ?>
  <div class="messages">
    <!-- XSS: $playerName echoed with no htmlspecialchars() -->
    <h4>Results for: <?php echo $playerName; ?></h4>
    <p>Score: <strong><?php echo $score; ?> / <?php echo count($questions); ?></strong></p>
  </div>

  <div class="vuln-note">
    <strong>XSS — Cross-Site Scripting</strong>
    The name above is output with <code>echo $playerName</code> and no escaping.
    Try submitting <code>&lt;script&gt;alert('hacked')&lt;/script&gt;</code> as your name
    to see it execute in the browser.  The same name is also stored in the database
    and re-injected into the leaderboard for every future visitor (stored XSS).
  </div>
  <div class="fix-note">
    <strong>Fix:</strong> Use <code>echo htmlspecialchars($playerName, ENT_QUOTES, 'UTF-8')</code>
    everywhere user-supplied data is written into HTML.
  </div>
  <?php endif; ?>

  <h2>Take the Quiz</h2>
  <form method="post" action="index.php">
    <div class="question">
      <label for="player_name"><strong>Your Name:</strong></label>
      <input type="text" name="player_name" id="player_name" size="44"
             value="<?php echo htmlspecialchars($playerName, ENT_QUOTES, 'UTF-8'); ?>"/>
    </div>

    <?php foreach ($questions as $i => $q): ?>
    <div class="question">
      <p><?php echo ($i + 1) . '. ' . htmlspecialchars($q['q'], ENT_QUOTES, 'UTF-8'); ?></p>
      <?php foreach ($q['choices'] as $j => $choice): ?>
      <label>
        <input type="radio" name="q<?php echo $i; ?>" value="<?php echo $j; ?>"
               <?php if ($havePost && isset($_POST['q' . $i]) && (int)$_POST['q' . $i] === $j) echo 'checked'; ?>>
        <?php echo htmlspecialchars($choice, ENT_QUOTES, 'UTF-8'); ?>
      </label>
      <?php endforeach; ?>
    </div>
    <?php endforeach; ?>

    <input type="submit" name="submit" value="Submit Quiz"/>
  </form>

  <?php if ($dbOk): ?>
  <h2>Leaderboard</h2>

  <div class="vuln-note">
    <strong>Stored XSS — names are displayed without escaping</strong>
    Player names are stored as-is and echoed below with no <code>htmlspecialchars()</code>.
    A malicious name persists in the database and runs for every visitor.
  </div>

  <table>
    <tr><th>#</th><th>Name</th><th>Score</th><th>Submitted</th></tr>
    <?php
    $result = $db->query('SELECT player_name, score, submitted_at FROM quiz3_scores ORDER BY score DESC, submitted_at ASC LIMIT 10');
    if ($result) {
        $rank = 1;
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $rank++ . '</td>';
            // XSS: name stored and displayed without htmlspecialchars()
            echo '<td>' . $row['player_name'] . '</td>';
            echo '<td>' . (int)$row['score'] . ' / ' . count($questions) . '</td>';
            echo '<td>' . htmlspecialchars($row['submitted_at'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '</tr>';
        }
        $result->free();
    }
    ?>
  </table>

  <h2>Search Scores</h2>

  <div class="vuln-note">
    <strong>SQL Injection</strong>
    The search input is inserted directly into the SQL query with no escaping or
    prepared statement.  Try entering <code>' OR '1'='1</code> to return every row
    in the table regardless of the name entered.
  </div>
  <div class="fix-note">
    <strong>Fix:</strong> Replace the raw query string with a prepared statement:
    <code>$stmt = $db-&gt;prepare("SELECT ... WHERE player_name = ?");</code>
    then <code>$stmt-&gt;bind_param('s', $searchTerm);</code>
  </div>

  <form method="get" action="index.php">
    <input type="text" name="search" size="30"
           value="<?php echo htmlspecialchars($searchTerm, ENT_QUOTES, 'UTF-8'); ?>"
           placeholder="Enter a player name"/>
    <button type="submit">Search</button>
  </form>

  <?php if ($searchResults !== null): ?>
  <table style="margin-top:12px;">
    <tr><th>Name</th><th>Score</th><th>Submitted</th></tr>
    <?php
    if ($searchResults && $searchResults->num_rows > 0) {
        while ($row = $searchResults->fetch_assoc()) {
            echo '<tr>';
            // XSS: also unescaped here to show both vulnerabilities together
            echo '<td>' . $row['player_name'] . '</td>';
            echo '<td>' . (int)$row['score'] . ' / ' . count($questions) . '</td>';
            echo '<td>' . htmlspecialchars($row['submitted_at'], ENT_QUOTES, 'UTF-8') . '</td>';
            echo '</tr>';
        }
        $searchResults->free();
    } else {
        echo '<tr><td colspan="3">No results found.</td></tr>';
    }
    ?>
  </table>
  <?php endif; ?>

  <?php endif; ?>

</div><!-- /.quiz-wrap -->

<footer>Introduction to Information Technology &mdash; Rensselaer Polytechnic Institute</footer>
</body>
</html>
<?php if ($dbOk) $db->close(); ?>

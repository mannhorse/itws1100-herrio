# Break It Exercise — Quiz 3

---

## Vulnerability 1 — SQL Injection

### Vulnerable Code

The search feature was rewritten to concatenate `$_GET['search']` directly into the query string instead of using a prepared statement:

```php
$searchTerm = $_GET['search'];
$searchResults = $db->query(
    "SELECT player_name, score, submitted_at FROM quiz3_scores WHERE player_name = '$searchTerm'"
);
```

### Malicious Input

Typing the following into the search box:

```
' OR '1'='1
```

turns the query into:

```sql
SELECT player_name, score, submitted_at FROM quiz3_scores WHERE player_name = '' OR '1'='1'
```

### What Happens

The `OR '1'='1'` condition is always true, so the WHERE clause no longer filters anything. Every single row in `quiz3_scores` is returned regardless of what name was searched. A more destructive payload such as `'; DROP TABLE quiz3_scores; --` could delete the entire table, wiping all stored scores permanently.

### Safe Code and Why It Works

```php
$stmt = $db->prepare('SELECT player_name, score, submitted_at FROM quiz3_scores WHERE player_name = ?');
$stmt->bind_param('s', $searchTerm);
$stmt->execute();
$searchResults = $stmt->get_result();
$stmt->close();
```

The `?` placeholder tells MySQL to treat whatever is bound to it as a data value, never as SQL syntax. Even if the user types `' OR '1'='1`, MySQL receives it as a literal string to search for — it cannot break out of the value and alter the query structure.

---

## Vulnerability 2 — XSS (Cross-Site Scripting)

### Vulnerable Code

The player name was echoed back into the results and the leaderboard without any output escaping:

```php
// In the results box
echo $playerName;

// In the leaderboard loop
echo '<td>' . $row['player_name'] . '</td>';
```

### Malicious Input

Entering the following as a player name:

```
<script>alert('hacked')</script>
```

### What Happens

Because the name is written directly into the HTML, the browser interprets the `<script>` tag as real code and executes it. The `alert('hacked')` popup fires immediately for anyone who loads the page. Since the name is also stored in the database and displayed in the leaderboard, every future visitor triggers the script too — this is called stored XSS and it persists until the row is deleted from the database.

### Safe Code and Why It Works

```php
// In the results box
echo htmlspecialchars($playerName, ENT_QUOTES, 'UTF-8');

// In the leaderboard loop
echo '<td>' . htmlspecialchars($row['player_name'], ENT_QUOTES, 'UTF-8') . '</td>';
```

`htmlspecialchars()` converts characters like `<`, `>`, and `"` into their HTML entity equivalents (`&lt;`, `&gt;`, `&quot;`). The browser displays these as visible text instead of interpreting them as markup, so the script tag appears on screen as harmless text rather than running as code.

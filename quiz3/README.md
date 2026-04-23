# Quiz 3 — US States Quiz

Live site: http://herriorpi.eastus.cloudapp.azure.com/iit/quiz3/index.php

---

## 1. Database Schema

One table is used: `quiz3_scores`. It has four columns.

`id` is an `INT AUTO_INCREMENT PRIMARY KEY`. MySQL assigns this number automatically for each new row so every submission has a unique ID without any extra code.

`player_name` is a `VARCHAR(255) NOT NULL`. VARCHAR was chosen over CHAR because it only uses as much space as the name actually needs. 255 is the standard max length for a name field and NOT NULL means a blank name can never be saved.

`score` is an `INT NOT NULL`. Scores are always whole numbers so INT was the right fit — no decimals needed.

`submitted_at` is a `TIMESTAMP DEFAULT CURRENT_TIMESTAMP`. This automatically records the exact date and time the row was inserted without any extra PHP code to set it.

---

## 2. PHP Write Path

The user fills out the form and clicks Submit. The browser sends a POST request to `index.php`.

`isset($_POST['submit'])` checks if the submit button was in the POST data. If it was, `$havePost` is set to true and the write path runs.

`$playerName = trim($_POST['player_name'])` reads the name the user typed and removes any extra whitespace.

The foreach loop goes through each question and checks if the posted radio button index matches the `correct` value in the `$questions` array. Every match increments `$score`.

`$db->prepare('INSERT INTO quiz3_scores (player_name, score) VALUES (?, ?)')` creates a prepared statement. The `?` placeholders keep the query structure separate from the data, which prevents SQL injection.

`$stmt->bind_param('si', $playerName, $score)` binds the real values to those placeholders. The `'si'` tells MySQL the first value is a string and the second is an integer.

`$stmt->execute()` runs the INSERT and writes the row to the database. The `submitted_at` timestamp fills in automatically. `$stmt->close()` frees the statement from memory.

---

## 3. PHP Read Path

When the page loads, the leaderboard runs a SELECT query to pull the top 10 scores.

`$db->query('SELECT player_name, score, submitted_at FROM quiz3_scores ORDER BY score DESC, submitted_at ASC LIMIT 10')` sends the query to MySQL and returns a result object. `ORDER BY score DESC` puts the highest scores first and `submitted_at ASC` breaks ties by earliest submission. `LIMIT 10` caps it at ten rows.

`while ($row = $result->fetch_assoc())` pulls the next row from the result set on each loop iteration as an associative array where the keys are the column names. The loop keeps going until there are no rows left.

Inside the loop, `echo` statements build one HTML table row per iteration using `$row['player_name']`, `$row['score']`, and `$row['submitted_at']`. Each value is wrapped in `htmlspecialchars()` to prevent XSS. `$result->free()` releases the result from memory when the loop finishes.

---

## 4. Client-Side JavaScript

`validateQuiz()` runs in the browser when the user clicks Submit, before anything is sent to the server. It is attached to the form with `onsubmit="return validateQuiz(this);"`.

The first check looks at `form.player_name.value.trim()`. If the name field is empty it fires an alert, moves focus to the name field, and returns false — which cancels the POST entirely.

The for loop goes through each question and uses `querySelectorAll` to look for a checked radio button. If any question has no answer selected it fires an alert naming that question and returns false. The total number of questions comes from `<?php echo count($questions); ?>` so it stays accurate if questions are ever added or removed.

If both checks pass, the function returns true and the form submits normally to PHP. The JavaScript acts as a front-end gatekeeper — PHP would still handle a bad submission, but the JS catches it first and gives the user immediate feedback without a server round trip.

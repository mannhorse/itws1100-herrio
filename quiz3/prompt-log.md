PHP Quiz Project: Prompts and AI Actions

## Prompt 1
Prompt:
"Build me a PHP quiz page that connects to my MySQL database. It should have a form where users enter their name and answer multiple choice questions about US states. When they submit, calculate their score and save the name and score to a database table. Use the same style and structure as my Lab 9 code."

What it returned:
Generated a complete `index.php` that opens a MySQL connection using `db.inc.php`, defines a `$questions` array to hold question data, detects form submission with `isset($_POST['submit'])`, loops through each posted answer to calculate a score, and writes the result to the database using a prepared statement with `bind_param`. The page layout and CSS reference matched the Lab 9 structure I was already using.

Kept: The overall structure — the `$havePost` pattern, the `bind_param` INSERT, and the `db.inc.php` connection all matched what I already knew from Lab 9.
Changed: Swapped out the placeholder questions for my own content in the next prompt.
Threw away: The Lab 9 navigation menu — the quiz is a single page so a nav bar was not needed.


## Prompt 2
Prompt:
"Use these specific questions for the quiz:
1. What is the capital of California? — Sacramento
2. Which state is the Lone Star State? — Texas
3. What is the capital of Florida? — Tallahassee
4. Largest US state by area? — Alaska
5. Capital of New York state? — Albany
6. Capital of Texas? — Austin
7. How many US states are there? — 50
8. Last state admitted in 1959? — Hawaii
9. Smallest state by area? — Rhode Island
10. Which state has the longest coastline? — Alaska
Make each one multiple choice with 4 options. Put the correct answer in a different position each time, not always the same slot."

What it returned:
Replaced the placeholder questions with all 10 entries, each structured as a PHP associative array with a `q` key for the question text, a `choices` array of four options, and a `correct` key storing the index of the right answer. The correct answer was placed in a different array position for each question so it was not always option A or always the last choice, making the quiz harder to guess through pattern recognition.

Kept: All 10 questions exactly as given — the array structure was clean and easy to loop over.
Changed: Nothing — the output matched the input precisely.
Threw away: Nothing.


## Prompt 3
Prompt:
"Add a leaderboard below the quiz form that pulls the top 10 scores from the database and displays them in a table with rank, name, score, and date submitted. Order by score descending, then by earliest submission date to break ties."

What it returned:
Added a `SELECT player_name, score, submitted_at FROM quiz3_scores ORDER BY score DESC, submitted_at ASC LIMIT 10` query after the form, then used a `while ($row = $result->fetch_assoc())` loop to build a table row for each result. A `$rank` counter incremented inside the loop to display placement numbers, and the score was formatted as "X / 10" by combining the stored score with `count($questions)`.

Kept: The entire leaderboard block — the ordering logic and rank counter were exactly right.
Changed: Adjusted the score display to show "X / 10" instead of just the raw number for clarity.
Threw away: Nothing.


## Prompt 4
Prompt:
"Add an XSS vulnerability to the quiz. Echo the player name back in the results without using htmlspecialchars() so that if someone types a script tag as their name it actually runs. Also echo the name from the database in the leaderboard without escaping it. Add a note explaining what the vulnerability is and how to fix it."

What it returned:
Changed the results section to output `echo $playerName` with no sanitization, and changed the leaderboard name cell to `echo '<td>' . $row['player_name'] . '</td>'` so names retrieved from the database are also injected raw into the HTML. Added a yellow `.vuln-note` box explaining that unsanitized output allows `<script>` tags to execute in the browser, and a green `.fix-note` box showing that wrapping every output in `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` converts dangerous characters into harmless HTML entities.

Kept: The concept of the vulnerability — it became the basis for the XSS section in `break-it.md`.
Changed: Removed the unsanitized echoes from the live code and added `htmlspecialchars()` back everywhere once the vulnerability was documented.
Threw away: The on-page warning boxes — they belonged in `break-it.md`, not the live site.


## Prompt 5
Prompt:
"Add a SQL injection vulnerability. Put a search box under the leaderboard that lets users search by player name. Build the query by concatenating the input directly into the SQL string without a prepared statement. Show an example payload that exploits it and explain the fix."

What it returned:
Added a GET form with a text input that passes `$_GET['search']` directly into `$db->query("SELECT ... WHERE player_name = '$searchTerm'")` with no escaping or parameterization. The results display in a table below the search box. A `.vuln-note` box demonstrated that entering `' OR '1'='1` as the search term breaks out of the string literal and turns the WHERE clause into a condition that is always true, returning every row in the table. The `.fix-note` box showed the prepared statement fix using `$db->prepare()` and `bind_param`.

Kept: The search feature itself and the `' OR '1'='1` example — both were moved into `break-it.md` as the SQL injection demonstration.
Changed: Replaced the raw string concatenation with a prepared statement in the live `index.php` so it no longer fails technical requirement #3.
Threw away: The on-page warning boxes — same reason as Prompt 4.


## Prompt 6
Prompt:
"Add JavaScript form validation that runs before the form is submitted. It should block the submission and show an alert if the name field is empty, and also block it if any of the 10 questions hasn't been answered. Don't use jQuery, just plain JavaScript."

What it returned:
Added a `validateQuiz(form)` function inside a `<script>` tag in the `<head>` that first checks `form.player_name.value.trim() === ''` and returns `false` with an alert if the name is blank. It then runs a `for` loop from 0 to the total question count, using `form.querySelectorAll('input[name="q' + i + '"]:checked').length === 0` to detect any unanswered question and returns `false` with a specific alert naming which question was skipped. The total question count was pulled dynamically from PHP using `<?php echo count($questions); ?>` rather than hardcoding 10. The function was attached to the form via `onsubmit="return validateQuiz(this);"`.

Kept: The entire function — the `querySelectorAll` loop approach was cleaner than checking each question individually.
Changed: Made sure the question count used `<?php echo count($questions); ?>` instead of a hardcoded 10.
Threw away: An earlier version that used `document.getElementById` for each question separately — it was repetitive and would not scale.


## Prompt 7
Prompt:
"Add a button on my homepage (index.html) in the hero section that links to the quiz. Put it next to the existing View My Labs button."

What it returned:
Added `<a href="quiz3/index.php" class="btn" style="margin-left:10px;">Take the Quiz</a>` immediately after the existing labs button inside the `.hero` section of `index.html`. Using the existing `btn` class meant the new button automatically inherited the same blue background, white text, padding, and border-radius as the labs button without needing any extra CSS.

Kept: The placement and the `btn` class — it matched the existing button perfectly with no extra CSS needed.
Changed: Nothing.
Threw away: A suggestion to also add a quiz card in the Featured Projects section — one link was enough.


## Prompt 8
Prompt:
"The vulnerability explanation boxes for XSS only show up after the form is submitted. Move the XSS explanation boxes so they are always visible on the page, not just after submitting."

What it returned:
Moved the `.vuln-note` and `.fix-note` blocks for XSS outside of the `if ($havePost)` conditional block so they render on every page load regardless of whether the form has been submitted. The `$playerName` echo and results message stayed inside `$havePost` since those depend on a submission, but the explanations no longer do.

Kept: The idea of always showing the explanation — this later became part of the `break-it.md` format.
Changed: The boxes were eventually removed from `index.php` entirely and moved to `break-it.md` where they belong.
Threw away: Nothing at this stage.


## Prompt 9
Prompt:
"The form tag is broken — it's missing its closing angle bracket after the onsubmit attribute. Fix it."

What it returned:
Corrected the malformed opening tag from `<form method="post" action="index.php" onsubmit="return validateQuiz(this);"` to `<form method="post" action="index.php" onsubmit="return validateQuiz(this);">` by adding the missing `>`. Without that character the browser could not parse where the tag ended, which caused the form and everything inside it to not render correctly.

Kept: The fix.
Changed: Nothing.
Threw away: Nothing — it was a one-character correction.


## Prompt 10
Prompt:
"Style the quiz page so it fits in with the rest of my site. The questions should each be in their own bordered box, the submit button should match the blue color used on the rest of the site, and the vulnerability notes should stand out visually with a yellow background and a left border."

What it returned:
Added an inline `<style>` block targeting `.question` with `border:1px solid #ddd`, `padding:14px 16px`, and `border-radius:4px` to give each question its own card-like box. The submit button received `background:#2079c7` to match the exact blue used across the rest of the site. The `.vuln-note` class was styled with `background:#fff3cd` and `border-left:4px solid #ffc107` for a yellow warning appearance, and `.fix-note` used `background:#d4edda` and `border-left:4px solid #28a745` for green, creating a clear visual distinction between the problem and its solution.

Kept: All of the styling — the bordered question boxes and blue button matched the site well.
Changed: Nothing.
Threw away: The `.vuln-note` and `.fix-note` CSS once those boxes were moved out of `index.php`.

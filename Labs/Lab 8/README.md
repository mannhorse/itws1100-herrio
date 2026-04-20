Lab 8 - JavaScript, JSON, and AJAX
Description:
This lab automatically builds a projects page using JSON and jQuery AJAX. When lab8.html loads, lab8.js reads lab8.json and
creates the project menu.

Files used:

lab8.html - The projects page
lab8.json - JSON file containing the project menu items
lab8.js - JavaScript that reads the JSON and builds the menu

How It Works:

The page loads jQuery. Once the document is ready, lab8.js makes an AJAX GET request to lab8.json. It goes  through each item and builds the lab cards. The cards are added into the projectMenu div. Adding a fade in makes it look better rather than it just appearing

Adding New Projects
To add a new project, add a new object to the "menuItem" array in
lab8.json with the lab, title, description, and link fields.
Links

GitHub: https://github.com/mannhorse/itws1100-herrio.git
Azure: http://herriorpi.eastus.cloudapp.azure.com/iit/index.html
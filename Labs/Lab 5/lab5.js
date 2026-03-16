/* Lab 5 JavaScript File 
   Place variables and functions in this file */

function validate(formObj) {
   // it will be a series of if statements

   if (formObj.firstName.value == "") {
      alert("You must enter a first name");
      formObj.firstName.focus();
      return false;
   }

   if (formObj.lastName.value == "") {
      alert("You must enter a last name");
      formObj.lastName.focus();
      return false;
   }

   if (formObj.title.value == "") {
      alert("You must enter a title");
      formObj.title.focus();
      return false;
   }

   if (formObj.org.value == "") {
      alert("You must enter an organization");
      formObj.org.focus();
      return false;
   }

   if (formObj.pseudonym.value == "") {
      alert("You must enter a nickname");
      formObj.pseudonym.focus();
      return false;
   }

   // Check the textarea for being empty or having the default placeholder text
   if (formObj.comments.value == "" || formObj.comments.value == "Please enter your comments") {
      alert("You must enter your comments");
      formObj.comments.focus();
      return false;
   }

   // If the form is successfully submitted, show a success message
   alert("Form successfully submitted!");
   return true;
}

// Clears the value when the mouse cursor is placed in it
function clearComments(commentField) {
   if (commentField.value == "Please enter your comments") {
      commentField.value = "";
   }
}

// Replaces text if the user clicks out without entering anything
function restoreComments(commentField) {
   if (commentField.value == "") {
      commentField.value = "Please enter your comments";
   }
}

// Creates an alert displaying the values of the first name and nickname
function showNickname() {
   var firstName = document.getElementById("firstName").value;
   var lastName = document.getElementById("lastName").value;
   var nickname = document.getElementById("pseudonym").value;
   
   alert(firstName + " " + lastName + " is " + nickname);
}
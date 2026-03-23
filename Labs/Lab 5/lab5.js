/* Lab 5 JavaScript File 
   Place variables and functions in this file */

function validate(formObj) {
   // it will be a series of if statements

   var missing = "";

   if (formObj.firstName.value == "") {
      missing = missing + "First Name\n";
   }

   if (formObj.lastName.value == "") {
      missing = missing + "Last Name\n";
   }

   if (formObj.title.value == "") {
      missing = missing + "Title\n";
   }

   if (formObj.org.value == "") {
      missing = missing + "Organization\n";
   }

   if (formObj.pseudonym.value == "") {
      missing = missing + "Nickname\n";
   }

   if (formObj.comments.value == "" || formObj.comments.value == "Please enter your comments") {
      missing = missing + "Comments\n";
   }

   if (missing != "") {
      alert("Please fill in the following fields:\n" + missing);
      return false;
   }

   alert("Form successfully submitted!");
   return true;
}

function clearComments(commentField) {
   if (commentField.value == "Please enter your comments") {
      commentField.value = "";
   }
}

function restoreComments(commentField) {
   if (commentField.value == "") {
      commentField.value = "Please enter your comments";
   }
}

function showNickname() {
   var firstName = document.getElementById("firstName").value;
   var lastName = document.getElementById("lastName").value;
   var nickname = document.getElementById("pseudonym").value;

   alert(firstName + " " + lastName + " is " + nickname);
}
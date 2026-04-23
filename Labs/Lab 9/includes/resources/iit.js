function validate(formObj) {

  if (formObj.firstNames.value == "") {
    alert("Please enter a first name");
    formObj.firstNames.focus();
    return false;
  }

  if (formObj.lastName.value == "") {
    alert("Please enter a last name");
    formObj.lastName.focus();
    return false;
  }

  if (formObj.dob.value == "") {
    alert("Please enter a date of birth");
    formObj.dob.focus();
    return false;
  }

  return true;
}

function validateMovie(formObj) {

  if (formObj.title.value == "") {
    alert("Please enter a title");
    formObj.title.focus();
    return false;
  }

  if (formObj.year.value == "") {
    alert("Please enter a year");
    formObj.year.focus();
    return false;
  }

  return true;
}


$(document).ready(function() {

  $("#firstNames").focus();
  $("#title").focus();

  $(".deleteActor").click(function() {
    if(confirm("Remove actor? (This action cannot be undone.)")) {

      var curId = $(this).closest("tr").attr("id");
      var actorId = curId.substr(curId.indexOf("-")+1);
      var postData = "id=" + actorId;

      $.ajax({
        type: "post",
        url: "actor-delete.php",
        dataType: "json",
        data: postData,
        success: function(responseData, status){
          if (responseData.errors) {
            alert(responseData.errno + " " + responseData.error);
          } else {
            $("#" + curId).closest("tr").remove();
            $(".messages").hide();
            $("#jsMessages").html("<h4>Actor deleted</h4>").show();

            $("#actorTable tr").each(function(i){
              if (i % 2 == 0) {
                $(this).addClass("odd");
              } else {
                $(this).removeClass("odd");
              }
            });
          }
        },
        error: function(msg) {
          alert(msg.status + " " + msg.statusText);
        }
      });

    }
  });

  $(".deleteMovie").click(function() {
    if(confirm("Remove movie? (This action cannot be undone.)")) {

      var curId = $(this).closest("tr").attr("id");
      var movieId = curId.substr(curId.indexOf("-")+1);
      var postData = "id=" + movieId;

      $.ajax({
        type: "post",
        url: "movie-delete.php",
        dataType: "json",
        data: postData,
        success: function(responseData, status){
          if (responseData.errors) {
            alert(responseData.errno + " " + responseData.error);
          } else {
            $("#" + curId).closest("tr").remove();
            $(".messages").hide();
            $("#jsMessages").html("<h4>Movie deleted</h4>").show();

            $("#movieTable tr").each(function(i){
              if (i % 2 == 0) {
                $(this).addClass("odd");
              } else {
                $(this).removeClass("odd");
              }
            });
          }
        },
        error: function(msg) {
          alert(msg.status + " " + msg.statusText);
        }
      });

    }
  });

});

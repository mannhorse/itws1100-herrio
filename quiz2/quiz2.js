// Quiz 2
// Put your javascript here in a document.ready function



alert("The page is about to load!");

$(document).ready(function () {

  var defaultTitle = "ITWS 1100 - Quiz 2";
  var myTitle = "Oliver Herrick - Quiz 2";
  document.title = defaultTitle;

  $("#gobutton").click(function () {
    if (document.title === defaultTitle) {
      document.title = myTitle;
    } else {
      document.title = defaultTitle;
    }
  });


  $("#lastname").on("mouseenter", function () {
    $(this).addClass("makeItPurple");
  });

  $("#lastname").on("mouseleave", function () {
    $(this).removeClass("makeItPurple");
  });

});
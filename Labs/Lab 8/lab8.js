$(document).ready(function() {

   $.ajax({
      type: "GET",
      url: "lab8.json",
      dataType: "json",
      success: function(data) {
         var output = "";

         $.each(data.menuItem, function(i, item) {
            output += '<article class="lab-card">';
            output += '<h3>' + item.lab + ': ' + item.title + '</h3>';
            output += '<p>' + item.description + '</p>';
            output += '<a href="' + item.link + '">View ' + item.lab + ' &rarr;</a>';
            output += '</article>';
         });

         $('#projectMenu').html(output);
      },
      error: function(msg) {
         alert("Error loading projects: " + msg.status + " " + msg.statusText);
      }
   });

   $("#projectMenu").hide().fadeIn(1000);

});
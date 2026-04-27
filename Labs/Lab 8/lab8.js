$(document).ready(function() {

   $.ajax({
      type: "GET",
      url: "lab8.json",
      dataType: "json",
      success: function(data) {
         var output = "";

         $.each(data.menuItem, function(i, item) {
            var lockIcon = item.secure ? ' &#x1F512;' : '';
            output += '<article class="lab-card">';
            output += '<h3>' + item.lab + ': ' + item.title + lockIcon + '</h3>';
            output += '<p>' + item.description + '</p>';
            output += '<a href="' + item.link + '"';
            if (item.secure) {
               output += ' title="Password required"';
            }
            output += '>View ' + item.lab + ' &rarr;</a>';
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

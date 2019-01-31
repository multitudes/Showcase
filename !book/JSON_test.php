<?php
// utilities
//require_once "service_util.php";

// start the session if not already started
session_start();

// load PDO class for DB connection and the others too 
require_once "../class/autoloader.php";

// initialise DB if needed
require_once "db_init.php";

echo "JSON TEST";

$title = "Add Tracks to Playlist";

///// header section finished, I can use include
include("../templ/head.tpl");  
?>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
    <div id="mytab"></div>
          

<script type="text/javascript">

/*function test_json() {
  window.console && console.log('Requesting JSON'); 
  $.getJSON("session_playlist.php", function(data){    
  var items = [];
  window.console && console.log('got JSON');  
  $.each( data, function( key, val ) {
    items.push( "<li id='" + key + "'>" + val + "</li>" );
  });
  $( "<ul/>", {
    "class": "my-new-list",
    html: items.join( "" )
  }).appendTo( "body" );
});
  }
  */  

// Do this *after* the table tag is rendered
function test_json2() {
    window.console && console.log('Requesting JSON');
    $.getJSON('http://localhost:8890/php/session_playlist.php', function(data) {
    $("#mytab").empty();
    window.console && console.log('got JSON');  
    //window.console && console.log(data);      
    found = false;
    for (var i = 0; i < data.length; i++) {
        row = data[i];
        found = true;
        window.console && console.log('Row: '+i+' '+row.id);
        $("#mytab").append("<tr><td>"+row.title+'</td><td>'
            + row.rating+'</td><td>'
            + row.id);
    }
    if ( ! found ) {
        $("#mytab").append("<tr><td>No entries found</td></tr>\n");
    }
});
}
  
    
    
// Make sure JSON requests are not cached
$(document).ready(function() {
  $.ajaxSetup({ cache: false });
  //test
test_json2();      

});      
</script>
</body>

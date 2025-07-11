<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) { 
$name = htmlspecialchars($_POST['name']); 
echo "Hello, $name! AJAX response received."; 
exit; 
} 
?> 
<!DOCTYPE html> 
<html> 
<head> 
<title>AJAX</title> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head> 
<body> 
<h2>Enter Your Name</h2> 
<input type="text" id="nameInput" placeholder="Type your name"> 
<button id="submitBtn">Submit</button> 
<p id="responseArea"></p> 
<script> 
$(document).ready(function() { 
$('#submitBtn').click(function() { 
        var name = $('#nameInput').val(); 
 
        $.ajax({ 
            url: '', // same file 
            type: 'POST', 
            data: { name: name }, 
            success: function(response) { 
                $('#responseArea').html(response); 
            }, 
            error: function() { 
                $('#responseArea').html('An error occurred.'); 
            } 
        }); 
    }); 
}); 
</script> 
 
</body> 
</html>
<?php 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check'])) { 
$conn = new mysqli("localhost", "root", "", "college"); 
if ($conn->connect_error) { 
die("Database connection failed: " . $conn->connect_error); 
} 
$input = trim($_POST['check']); 
$input = $conn->real_escape_string($input); 
$sql = "SELECT * FROM students WHERE name = '$input' OR roll = '$input'"; 
$result = $conn->query($sql); 
if ($result && $result->num_rows > 0) { 
echo "<span style='color: green;'>Student found in database.</span>"; 
} else { 
echo "<span style='color: red;'>No student found with that name or roll in 
database.</span>"; 
} 
$conn->close(); 
exit; 
} 
?> 
<!DOCTYPE html> 
<html> 
<head> 
<title>Check Student Record in Database(AJAX)</title> 
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
</head> 
<body> 
<h2>Check if Student Exists in Database</h2> 
<input type="text" id="checkInput" placeholder="Enter name or roll" /> 
<button id="checkBtn">Check</button> 
<div id="checkResult" style="margin-top: 15px;"></div> 
<script> 
$(document).ready(function() { 
$('#checkBtn').click(function() { 
var input = $('#checkInput').val().trim(); 
if (input === '') { 
$('#checkResult').html("<span style='color: orange;'>Please enter 
something.</span>"); 
return; 
} 
$.ajax({ 
type: "POST", 
url: "", // same file 
data: { check: input }, 
success: function(response) { 
$('#checkResult').html(response); 
}, 
error: function() { 
$('#checkResult').html("<span style='color: red;'>Error occurred while 
checking.</span>"); 
} 
}); 
}); 
}); 
</script> 
</body> 
</html>
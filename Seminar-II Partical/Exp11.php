<?php 
$host = "localhost"; 
$user = "root"; 
$password = ""; 
$dbname = "college"; 
$conn = new mysqli($host, $user, $password, $dbname); 
if ($conn->connect_error) { 
die("Connection failed: " . $conn->connect_error); 
} 
$sort_field = isset($_GET['field']) ? $_GET['field'] : 'name'; 
$sort_order = isset($_GET['order']) ? $_GET['order'] : 'ASC'; 
$valid_fields = ['name', 'roll']; 
$valid_order = ['ASC', 'DESC']; 
if (!in_array($sort_field, $valid_fields)) $sort_field = 'name'; 
if (!in_array($sort_order, $valid_order)) $sort_order = 'ASC'; 
$sql = "SELECT * FROM students ORDER BY $sort_field $sort_order"; 
$result = $conn->query($sql); 
?> 
<!DOCTYPE html> 
<html> 
<head> 
<title>Sort Students</title> 
</head> 
<body> 
<h2>Sort Student Records</h2> 
<form method="get" action=""> 
<label>Sort by: 
<select name="field"> 
<option value="name" <?php if ($sort_field == 'name') echo 'selected'; 
?>>Name</option> 
<option value="roll" <?php if ($sort_field == 'roll') echo 'selected'; 
?>>Roll</option> 
</select> 
</label> 
<label>Order: 
<select name="order"> 
<option value="ASC" <?php if ($sort_order == 'ASC') echo 'selected'; 
?>>Ascending</option> 
<option value="DESC" <?php if ($sort_order == 'DESC') echo 'selected'; 
?>>Descending</option> 
</select> 
</label> 
<button type="submit">Sort</button> 
</form> 
<table border="1" cellpadding="10" style="margin-top: 20px;"> 
<tr> 
<th>ID</th> 
<th>Name</th> 
<th>Roll</th> 
</tr> 
<?php 
if ($result->num_rows > 0) { 
while ($row = $result->fetch_assoc()) { 
echo "<tr> 
<td>{$row['id']}</td> 
<td>{$row['name']}</td> 
<td>{$row['roll']}</td> 
</tr>"; 
} 
} else { 
echo "<tr><td colspan='3'>No records found.</td></tr>"; 
} 
?> 
</table> 
</body> 
</html> 
<?php 
$conn->close(); 
?>
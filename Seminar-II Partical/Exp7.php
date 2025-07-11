<?php 
date_default_timezone_set("asia/kolkata");  
$serverTime = date("H:i:s"); 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="UTF-8"> 
<title>Digital Clock</title> 
<style> 
body { 
background: linear-gradient(135deg, #2c3e50, #3498db); 
color: #ecf0f1; 
display: flex; 
justify-content: center; 
align-items: center; 
height: 100vh; 
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
margin: 0; 
} 
.clock { 
background: rgba(0, 0, 0, 0.4); 
padding: 40px 60px; 
border-radius: 15px; 
box-shadow: 0 0 20px rgba(0,0,0,0.3); 
font-size: 4rem; 
letter-spacing: 5px; 
} 
</style> 
</head> 
<body> 
<div class="clock" id="clock"><?php echo $serverTime; ?></div> 
<script> 
let serverTime = new Date("<?php echo date('Y-m-d H:i:s'); ?>"); 
function updateClock() { 
serverTime.setSeconds(serverTime.getSeconds() + 1); 
const timeStr = serverTime.toLocaleTimeString('en-GB', { hour12: false }); 
document.getElementById('clock').textContent = timeStr; 
} 
setInterval(updateClock, 1000); 
</script> 
</body> 
</html>
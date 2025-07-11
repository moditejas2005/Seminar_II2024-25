<?php 
$counterFile = 'counter.txt'; 
if (!file_exists($counterFile)) { 
file_put_contents($counterFile, 0); 
} 
$visitorCount = (int)file_get_contents($counterFile); 
$visitorCount++; 
file_put_contents($counterFile, $visitorCount); 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
<meta charset="UTF-8"> 
<title>Visitor Counter</title> 
<style> 
body { 
margin: 0; 
background: linear-gradient(135deg, #74ebd5, #9face6); 
font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
display: flex; 
align-items: center; 
justify-content: center; 
height: 100vh; 
} 
.counter-box { 
background: rgba(255, 255, 255, 0.15); 
padding: 40px; 
border-radius: 15px; 
backdrop-filter: blur(10px); 
text-align: center; 
box-shadow: 0 0 20px rgba(0,0,0,0.2); 
color: white; 
animation: fadeIn 1s ease-out; 
} 
h1 { 
font-size: 3rem; 
margin-bottom: 10px; 
} 
.number { 
font-size: 4rem; 
color: #ffeb3b; 
font-weight: bold; 
animation: pulse 1s infinite alternate; 
} 
@keyframes fadeIn { 
from {opacity: 0; transform: scale(0.9);} 
to {opacity: 1; transform: scale(1);} 
} 
@keyframes pulse { 
from { transform: scale(1); } 
to { transform: scale(1.1); } 
} 
</style> 
</head> 
<body> 
<div class="counter-box"> 
<h1>Visitor Counter</h1> 
<div class="number"><?php echo $visitorCount; ?></div> 
<p>people have visited this page.</p> 
</div> 
</body> 
</html>
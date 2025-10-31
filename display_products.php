<?php
include 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title>All Products</title>
  <style>
    body{font-family:Arial; margin:40px; background:#f9f9f9;}
    h2{color:#333;}
    .product{border:1px solid #ccc; border-radius:10px; padding:15px; margin-bottom:15px; width:350px; background:#fff; box-shadow:0 2px 5px rgba(0,0,0,0.1);}
    img{max-width:100%; border-radius:8px;}
  </style>
</head>
<body>
<h2>💍 All Products</h2>
<?php
$sql="SELECT * FROM gold_items ORDER BY id DESC";
$result=$conn->query($sql);
if($result->num_rows>0){
  while($row=$result->fetch_assoc()){
    echo "<div class='product'>
            <h3>{$row['name']}</h3>
            <p><b>Price:</b> ₹{$row['price']}</p>
            <p><b>Tags:</b> {$row['tags']}</p>
            <img src='{$row['image_small']}' alt='Product'>
          </div>";
  }
}else echo "<p>No products found.</p>";
$conn->close();
?>
</body>
</html>

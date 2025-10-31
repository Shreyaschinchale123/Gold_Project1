<?php
include 'db.php';
if(isset($_GET['query'])){
  $search=$conn->real_escape_string(trim($_GET['query']));
  $sql="SELECT * FROM gold_items WHERE name LIKE '%$search%' OR tags LIKE '%$search%' OR price LIKE '%$search%'";
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
  }else echo "<p class='no-result'>No products found for '<b>$search</b>'</p>";
}
$conn->close();
?>

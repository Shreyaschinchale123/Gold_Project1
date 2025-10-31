<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {

    $name = $_POST['name'];
    $price = $_POST['price'];
    $tags = $_POST['tags'];
    $file = $_FILES['image'];

    if ($file['error'] == 0) {

        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

        $filename = time().'_'.basename($file['name']);
        $target_path = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target_path)) {

            $image_info = getimagesize($target_path);
            $width = $image_info[0]; $height = $image_info[1];
            $mime = $image_info['mime'];

            switch($mime){
                case 'image/jpeg': $src=imagecreatefromjpeg($target_path); break;
                case 'image/png': $src=imagecreatefrompng($target_path); break;
                case 'image/gif': $src=imagecreatefromgif($target_path); break;
                default: die("Unsupported image type.");
            }

            $sizes = ['small'=>[150,150],'medium'=>[400,400],'large'=>[800,800]];
            $resized_paths=[];

            foreach($sizes as $label=>$dim){
                list($w,$h)=$dim;
                $dst=imagecreatetruecolor($w,$h);
                if($mime=='image/png'||$mime=='image/gif'){
                    imagecolortransparent($dst,imagecolorallocatealpha($dst,0,0,0,127));
                    imagealphablending($dst,false);
                    imagesavealpha($dst,true);
                }
                imagecopyresampled($dst,$src,0,0,0,0,$w,$h,$width,$height);
                $resized_name=$upload_dir.$label.'_'.$filename;
                switch($mime){
                    case 'image/jpeg': imagejpeg($dst,$resized_name); break;
                    case 'image/png': imagepng($dst,$resized_name); break;
                    case 'image/gif': imagegif($dst,$resized_name); break;
                }
                imagedestroy($dst);
                $resized_paths[$label]=$resized_name;
            }
            imagedestroy($src);

            $stmt=$conn->prepare("INSERT INTO gold_items (name,price,tags,image_small,image_medium,image_large) VALUES (?,?,?,?,?,?)");
            $stmt->bind_param("sissss",$name,$price,$tags,$resized_paths['small'],$resized_paths['medium'],$resized_paths['large']);
            if($stmt->execute()) echo "<h3 style='color:green;'> Product uploaded successfully!</h3>";
            else echo "<h3 style='color:red;'>DB Error: ".$stmt->error."</h3>";
            $stmt->close();

        } else echo "<h3 style='color:red;'>Failed to move uploaded file.</h3>";
    } else echo "<h3 style='color:red;'>Error uploading file.</h3>";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Upload Product</title>
  <style>
    body{font-family:Arial; margin:40px;}
    form{border:1px solid #ccc; padding:20px; width:320px; border-radius:10px;}
    input, button{width:100%; margin-bottom:15px; padding:8px; box-sizing:border-box;}
    button{background:#4CAF50; color:white; border:none; cursor:pointer;}
    button:hover{background:#45a049;}
  </style>
</head>
<body>
<h2>Upload Product</h2>
<form action="" method="post" enctype="multipart/form-data">
  <label>Product Name:</label>
  <input type="text" name="name" placeholder="e.g., Gold Ring" required>
  <label>Price:</label>
  <input type="number" name="price" placeholder="e.g., 15000" required>
  <label>Tags (comma separated):</label>
  <input type="text" name="tags" placeholder="e.g., gold, ring, jewelry" required>
  <label>Upload Image:</label>
  <input type="file" name="image" accept="image/*" required>
  <button type="submit">Upload Product</button>
</form>
</body>
</html>


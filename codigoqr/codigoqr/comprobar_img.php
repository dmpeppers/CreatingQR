<?php
 $imagePath = 'personas/id1.jpg';

 echo "Path: " . realpath($imagePath); 

 if (file_exists($imagePath)) {
    echo "Existe";
 }
else{
    echo "A tomar por culo";
}
?>
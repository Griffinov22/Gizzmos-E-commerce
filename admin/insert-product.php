<?php

$prodName = $_POST['product-name'];
$prodDesc = $_POST['product-desc'];
$prodPrice = $_POST['product-price'];

if (!empty($prodName) || !empty($prodDesc) || !empty($prodPrice)) {

    $hasImage = !empty($_FILES['productImage']['name']);
    $imageName = null;
    $imageIdentifier = null;

    $filesystemSuccess = false;
    $imageInsertSuccess = false;

    if ($hasImage){
        include_once "../logic/Guid.php";

        // test
        $test_file = '/var/www/GizzmosImages/test.txt';
        if (file_put_contents($test_file, 'Test') === false) {
            echo 'Failed to write to the target directory.';
        } else {
            echo 'Successfully wrote to the target directory.';
        }
        //end-test
        
        $imageName = $_FILES['productImage']['name'];
        $imageIdentifier = NewGuid();
        $imageExt = explode(".", $imageName)[1];
        $path = "/var/www/GizzmosImages/$imageIdentifier.$imageExt";
        $filesystemSuccess = move_uploaded_file($_FILES['productImage']['tmp_name'], $path);

        if ($filesystemSuccess) {
            try {

                $imgQuery = $db->prepare("INSERT INTO Images (ImageId, Name, Path) VALUES (?,?,?)");
                $imageInsertSuccess = $imgQuery->execute([$imageIdentifier, $imageName, $path]);
            } catch (Exception $e) {
                throw new ErrorException("Couldn't insert image into database. Error: " . $e->getMessage());
            }
        } else {
            $errorMessage = "error uploading image to database.";
        }
    }

    try {
        $prodQuery = $db->prepare("INSERT INTO Products (Name, Description, Price, ImageId) VALUES (?,?,?,?)");
        
        $doInsertImage = $filesystemSuccess && $imageInsertSuccess;
        
        $prodQuery->execute([$prodName, $prodDesc, $prodPrice, $doInsertImage ? $imageIdentifier : null]);
        $successMessage = "Product created successfully";

    } catch (Exception $e) {
        throw new ErrorException("Couldn't insert product into database. Error: " . $e->getMessage());
    }

    

} else {
    $errorMessage = "all fields are required";
}
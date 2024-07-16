<?php
session_start();
include "../conn/openDb.php";

if (!$_SESSION['loggedIn'] || !$_SESSION['isAdmin']) {
    header("Location: ../index.php");
    exit();
}

$productErrorMessage = '';
$productSuccessMessage = '';
$popupSucessMessage = '';

if (!empty($_POST)) {
    //CREATE PRODUCT
    if ($_POST['product-name']) {
        include_once "insert-product.php";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../styles.css">
    <title>Gizzmos - admin panel</title>
</head>

<body>
    <?php include "../components/header.php"; ?>
    <main>
        <h2 class="main-header">Admin Panel</h2>

        <div class="admin-grid">
            <!-- Product Form -->
            <div class="product__wrapper">
                <form class="product__form" method="POST" action="" enctype="multipart/form-data">
                    <fieldset class="product__set">
                        <legend>Add/Delete Products</legend>
                        <div>
                            <label>Name:</label>
                            <input type="text" id="product-name" name="product-name" name="product-name"
                                value="<?= $productName ?? "" ?>" required />
                        </div>
                        <div>
                            <label>Description:</label>
                            <textarea name="product-desc" rows="3" required><?= $productDescription ?? '' ?></textarea>
                        </div>
                        <div>
                            <label>Price:</label>
                            <div class="product__currency-wrapper">
                                <p>$</p>
                                <input type="number" name="product-price" min="0" value="<?= $productPrice ?? '' ?>"
                                    required />
                            </div>
                        </div>
                        <div>
                            <label>Image:</label>
                            <input type="file" accept="image/*" name="productImage" />
                        </div>

                        <?php if (!empty($productErrorMessage)) : ?>
                        <div>
                            <p style="color:red;"><?= $productErrorMessage ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($productSuccessMessage)) : ?>
                        <div>
                            <p style="color:green;"><?= $productErrorMessage ?></p>
                        </div>
                        <?php endif; ?>

                        <div>
                            <input type="submit" value="submit" />
                        </div>
                    </fieldset>
                </form>
                <div class="product__tray">
                    <?php
                    $products = $db->query("SELECT ProductId,Name FROM Products")->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($products as $product) :
                    ?>

                    <button class="product__tray-item" data-productid="<?= $product['ProductId'] ?>">
                        <?= $product['Name'] ?>
                    </button>

                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Admin Form -->
            <div class="users__form">
                <button class="users__user" data-customerid="1234">Griffin Overmyer</button>
            </div>

        </div>
    </main>

    <script type="text/javascript">
    document.querySelector("#product-name").focus();

    const users = document.querySelectorAll(".users__user");
    users.forEach(el => {
        el.addEventListener("click", (e) => {
            const customerId = e.target.dataset.customerid;
            const parsedId = parseInt(customerId, 10);

            if (!isNaN(parsedId)) {

                const httpBody = new FormData();
                httpBody.append("customerId", parsedId);

                fetch(window.location.href, {
                    method: "POST",
                    body: httpBody
                });
            }

        });
    });

    const products = document.querySelectorAll(".product__tray-item");
    products.forEach((el) => {
        el.addEventListener("click", async (e) => {

            const prodId = e.target.dataset.productid;
            const parsedId = parseInt(prodId, 10);

            if (!isNaN(parsedId)) {
                const httpBody = new FormData();
                httpBody.append("productId", parsedId);

                const del =
                    `${window.location.protocol}//${window.location.host}/admin/deleteProduct.php`;

                const response = await fetch(del, {
                    method: "POST",
                    body: httpBody
                });

                if (!response.ok) {
                    // error
                }

                const result = await response.json();
                console.log(result);
            }

        })
    })
    </script>
</body>

</html>
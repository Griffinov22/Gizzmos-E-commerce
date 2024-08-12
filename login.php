<?php
require_once "./models/product.php";
session_start();

$errorMessage = '';

// attempts login
if (!empty($_POST)) {

    include "./conn/openDb.php";
    $_SESSION['username'] = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($_SESSION['username']) && !empty($password)) {
        try {
            $query = $db->prepare("SELECT Users.Firstname, Users.Lastname, Users.Password, Users.IsAdmin, Users.UserId, Carts.Products FROM Users JOIN Carts ON Users.UserId = Carts.UserId WHERE Username = ?");
            $query->execute([$_SESSION["username"]]);
            $res = $query->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $res["Password"])) {
                $_SESSION['loggedIn'] = true;
                $_SESSION['firstname'] = htmlspecialchars(trim($res['Firstname']));
                $_SESSION['lastname'] = htmlspecialchars(trim($res['Lastname']));
                $_SESSION['isAdmin'] = (bool)$res['IsAdmin'];
                $_SESSION['userId'] = htmlspecialchars(trim($res['UserId']));
                $_SESSION['products'] = [];

                $decodedProducts = json_decode($res['Products']);
                foreach($decodedProducts as $prod) {
                    $prodClass = unserialize($prod);
                    array_push($_SESSION['products'], $prodClass);
                }
                
                //redirect to home screen
                header('Location:index.php');
                exit();
            } else {
                $errorMessage = "Incorrect password";
            }
        } catch (Exception $e) {
            $errorMessage = "error connecting to database. Try again later";
        }
    } else {
        $errorMessage = "All fields are required";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="styles.css">
    <title>Gizzmos - sign up</title>
</head>

<body>
    <?php include "./components/header.php"; ?>
    <main>
        <h2 class="main-header">Sign Up to See all The new Gizzmos!</h2>
        <form class="login__form" method="POST" action="login.php">
            <fieldset class="login__set">
                <legend>Sign Up</legend>
                <div>
                    <label>Username:</label>
                    <input type="text" id="username" name="username" name="lastname" value="<?= $_SESSION["username"] ?? "" ?>" />
                </div>
                <div>
                    <label>Password:</label>
                    <input type="password" name="password" name="lastname" value="<?= $_SESSION["password"] ?? "" ?>" />
                </div>
                <?php if (!empty($errorMessage)) : ?>
                    <div>
                        <p style="color:red;"><?= $errorMessage ?></p>
                    </div>
                <?php endif; ?>
                <div>
                    <input type="submit" value="submit" />
                </div>
            </fieldset>
        </form>
    </main>

    <script type="text/javascript">
        document.querySelector("#username").focus();
    </script>
</body>

</html>
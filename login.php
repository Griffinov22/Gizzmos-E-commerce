<?php
session_start();

$errorMessage = '';

// attempts login
if (!empty($_POST)) {
    // add security
    include "./conn/openDb.php";
    $_SESSION['username'] = $_POST['username'];
    $password = $_POST['password'];

    if (!empty($_SESSION['username']) && !empty($password)) {
        try {
            $query = $db->prepare("SELECT Firstname, Lastname, Password, IsAdmin, UserId FROM Users WHERE Username = ?");
            $query->execute([$_SESSION["username"]]);
            $res = $query->fetch(PDO::FETCH_ASSOC);

            if (password_verify($password, $res["Password"])) {
                $_SESSION['loggedIn'] = true;
                $_SESSION['firstname'] = $res['Firstname'];
                $_SESSION['lastname'] = $res['Lastname'];
                $_SESSION['isAdmin'] = (bool)$res['IsAdmin'];
                $_SESSION['userId'] = $res['UserId'];
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
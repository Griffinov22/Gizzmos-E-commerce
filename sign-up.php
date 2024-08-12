<?php
session_start();

$errorMessage = '';

// attempts login
if (!empty($_POST)) {
    // add security
    include "./conn/openDb.php";
    $_SESSION['firstname'] = htmlspecialchars(trim($_POST['firstname']));
    $_SESSION['lastname'] = htmlspecialchars(trim($_POST['lastname']));
    $_SESSION['username'] = htmlspecialchars(trim($_POST['username']));
    // users are not admin by default. Admins manually turned on.
    $_SESSION['isAdmin'] = false;
    $password = htmlspecialchars(trim($_POST['password']));

    if (!empty($_SESSION['firstname']) && !empty($_SESSION['lastname']) && !empty($_SESSION['username']) && !empty($password)) {
        try {
            // this creates a cart record with userId foreign key
            $query = $db->prepare("INSERT INTO Users (Firstname, Lastname, Username, Password) VALUES (?,?,?,?)");
            $resInsert = $query->execute([$_SESSION['firstname'], $_SESSION['lastname'], $_SESSION['username'], password_hash($password, PASSWORD_DEFAULT)]);

            //get userId
            $idQuery = $db->prepare("SELECT Users.UserId, Carts.Products FROM Users JOIN Carts ON Users.UserId = Carts.UserId WHERE Username = ?");
            
            $idQuery->execute([$_SESSION['username']]);
            $res = $idQuery->fetch(PDO::FETCH_ASSOC);

            $_SESSION['loggedIn'] = true;
            $_SESSION['userId'] = $res['UserId'];
            // should be an empty array
            $_SESSION['products'] = json_decode($res['Products']);
            //redirect to home screen
            header('Location:index.php');
            exit();
            
        } catch (Exception $e) {
            $errCode = $e->getCode();
            $errorMessage = $errCode == "23000" ? "Username {$_SESSION['username']} is already taken" : "error connecting to database";
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
        <form class="signup__form" method="POST" action="sign-up.php">
            <fieldset class="signup__set">
                <legend>Sign Up</legend>
                <div>
                    <label>First Name:</label>
                    <input type="text" id="firstname" name="firstname" value="<?= $_SESSION["firstname"] ?? "" ?>" />
                </div>
                <div>
                    <label>Last Name:</label>
                    <input type="text" name="lastname" value="<?= $_SESSION["lastname"] ?? "" ?>" />
                </div>
                <div>
                    <label>Username:</label>
                    <input type="text" name="username" name="lastname" value="<?= $_SESSION["username"] ?? "" ?>" />
                </div>
                <div>
                    <label>Password:</label>
                    <input type="text" name="password" name="lastname" value="<?= $_SESSION["password"] ?? "" ?>" />
                </div>
                <div>
                    <input type="submit" value="submit" />
                </div>
                <?php if (!empty($errorMessage)) : ?>
                    <div>
                        <p style="color:red;"><?= $errorMessage ?></p>
                    </div>
                <?php endif; ?>
            </fieldset>
        </form>
    </main>

    <script type="text/javascript">
        document.querySelector("#firstname").focus();
    </script>
</body>

</html>
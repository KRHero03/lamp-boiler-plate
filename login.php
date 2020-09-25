<?php
require('./vendor/autoload.php');
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$conn = new mysqli($_ENV["MYSQL_HOST"], $_ENV["MYSQL_USERNAME"], $_ENV["MYSQL_PASSWORD"], $_ENV["MYSQL_DB"]);
session_start();
if (isset($_COOKIE["id"]) and sizeof($_COOKIE["id"] != 0)) {
    header("Location:./home.php");
    return;
}
$response = 0;
if (!strcmp($_SERVER["REQUEST_METHOD"], "POST")) {
    if (!isset($_POST["username"]) || !isset($_POST["password"])) {
        $response = -1;
    } else {
        $username = $_POST["username"];
        $password = $_POST["password"];
        if (!preg_match('/^[a-zA-Z0-9]{2,}$/', $username)) {
            $response = -1;
        }
        $password = md5($password);
        $query = "SELECT id,password FROM registration WHERE username='$username';";
        $res = $conn->query($query);
        if ($res && $res->num_rows!=0) {
            while ($row = $res->fetch_assoc()) {
                if (strcmp($password, $row["password"])) {
                    $response = -3;
                } else {
                    setcookie("id", $row["id"], time() + 60 * 60 * 24, "/");
                    header("Location:./home.php");
                }
            }
        } else {
            $response = -2;
        }
    }
}
?>

<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>
<style>
    .container {
        margin-top: 20px;
        align-items: center;
    }

    form {
        align-items: center;
    }
</style>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="./login.php">Assignment 6</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link waves-effect waves-light" href="./login.php">Login
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="./register.php">Register User</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container" style="text-align: center">
        <h3>Hey there!</h3>
        <h6>Login using Username and Password.</h6>
    </div>
    <div class="container">
        <?php
        if ($response != 0) {
            echo '  <div style="margin-top: 10px" class="alert alert-danger alert-dismissible fade show" role="alert">';

            if ($response == -1) {
                echo '<strong>Invalid Username / Password!</strong> Please enter valid Username / Password.';
            } else if ($response == -2) {
                echo '<strong>Invalid Username!</strong> Username does not exist.';
            } else if ($response == -3) {
                echo '<strong>Invalid Password!</strong> Username and Password does not match!';
            }
            echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            ';
        }

        ?>
    </div>
    <div class="container">
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="usernameLabel">Username</label>
                <input type="text" class="form-control" name="username" id="username" aria-describedby="username" placeholder="Enter Username..." required>
                <small id="usernameHelp" class="form-text text-muted">Enter your Username.</small>
            </div>
            <div class="form-group">
                <label for="passwordLabel">Password</label>
                <input type="password" class="form-control" name="password" id="password" aria-describedby="password" placeholder="Enter Password..." required>
                <small id="passwordHelp" class="form-text text-muted">Enter your Password.</small>
            </div>
            <div style="display: flex; justify-content: space-between">
                <button type="submit" class="btn btn-primary">Login</button>
                <button class="btn btn-primary"><a style="text-decoration: none; color: inherit" href="./register.php">Register</a></button>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>
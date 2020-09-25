<html>
<?php
    require('./vendor/autoload.php');
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();  
    $conn = new mysqli($_ENV["MYSQL_HOST"], $_ENV["MYSQL_USERNAME"], $_ENV["MYSQL_PASSWORD"],$_ENV["MYSQL_DB"]);
    session_start();    
    if (!isset($_COOKIE["id"])) {
        header("Location:./login.php");
        return;
    }
    $id = $_COOKIE["id"];
    $query = "SELECT * FROM registration WHERE id='$id';";
    $res = $conn->query($query);
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $fullName = $row["fullname"];
            $username = $row["username"];
            $password = $row["password"];
            $address = $row["address"];
            $admno = $row["admno"];
            $branch = $row["branch"];
            $cgpa = $row["cgpa"];
        }
    } else {
        header('Location:./login.php');
        return;
    }
    if(!strcmp($_SERVER["REQUEST_METHOD"],"POST")){
        $id = $_COOKIE["id"];
        $query = "DELETE FROM registration WHERE id='$id';";
        $res = $conn->query($query);
        setcookie("id","",time()-60*60);
        header('Location:./login.php');

    }
    $id = $_COOKIE["id"];
    $query = "SELECT fullname FROM registration WHERE id='$id';";
    $res = $conn->query($query);
    $fullName = "";
    if($res){
        while($row = $res ->fetch_assoc()){
            $fullName = $row["fullname"];
        }
    }else{
        header('Location:./login.php');
        return;
    }
?>
<head>
    <title>Home | <?php echo $fullName;?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>
<style>
    .container {
        margin-top: 20px;
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
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="./home.php">Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light" href="./profile.php">Profile</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link waves-effect waves-light" href="./delete.php">Delete Account</a>
                    <span class="sr-only">(current)</span>
                </li>
                <li class="nav-item">
                    <form style="margin: 0px;padding: 0px;" action="./home.php" method="post">
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container" style="text-align: center">
        <h2 class="display-4">Delete your User Account</h2>
        <h6 class="display-4" style="font-size: 2rem;">Don't like us storing your Private Information? You can choose to delete your User Account.</h6>
    
        <h6 class="display-4" style="font-size: 1rem;">Warning: Your User Account and it's information will be removed from our servers completely!</h6>
    </div>
    <div class="container" style="display: flex; justify-content: center"> 
        <form action="delete.php" method="post">            
        <button type="submit" class="btn btn-primary">Delete your User Account</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>
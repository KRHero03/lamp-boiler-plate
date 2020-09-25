<html>
<?php
    require('./vendor/autoload.php');
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();  
    $conn = new mysqli($_ENV["MYSQL_HOST"], $_ENV["MYSQL_USERNAME"], $_ENV["MYSQL_PASSWORD"],$_ENV["MYSQL_DB"]);
    session_start();
    if (isset($_COOKIE["id"]) and sizeof($_COOKIE["id"]!=0)) {
        header("Location:./home.php");
        return;
    }
    $response = 0;
    if(!strcmp($_SERVER["REQUEST_METHOD"],"POST")){
        if(!isset($_POST["username"]) || !isset($_POST["password"]) || !isset($_POST["fullName"]) || !isset($_POST["cgpa"]) || !isset($_POST["branch"])
        || !isset($_POST["address"]) || !isset($_POST["admissionNo"])
        ){
            $response = -1;
        }else{
            $username = $_POST["username"];
            $password = $_POST["password"];
            $address = $_POST["address"];
            $admno = $_POST["admissionNo"];
            $fullName = $_POST["fullName"];
            $branch = $_POST["branch"];
            $cgpa = floatval($_POST["cgpa"]);
            if (!preg_match('/^[a-zA-Z ]{2,}$/', $fullName)) {
                //echo $fullName;
                $response = -1;
            }
            if (!preg_match('/^[a-zA-Z0-9]{2,}$/', $username)) {
                //echo $username;
                $response = -1;
            }
            if (!preg_match('/^\S*(?=\S{9,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $password)) {
                //echo $password;
                $response = -1;
            }
            if($response!=-1){
                $password = md5($password);
                $id = md5(time());
                $query = "SELECT username FROM registration WHERE username='$username';";
                $res = $conn->query($query);
                if($res && $res->num_rows!=0){
                    $response = -2;
                }else{
                    $query = "INSERT INTO registration(id,username,password,fullname,admno,address,branch,cgpa) VALUES('$id','$username','$password','$fullName','$admno','$address','$branch',$cgpa);";
                    $res = $conn->query($query);
                    if($res) $response = 1;
                    else $response = -3;
                }
            }
        }
    }
    
?>
<head>
    <title>Registration Form</title>
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
                    <a class="nav-link waves-effect waves-light" href="./login.php">Login
                    </a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link waves-effect waves-light" href="./register.php">Register User
                        <span class="sr-only">(current)</span></a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container" style="text-align: center">
        <h3> Registration Form</h3>
    </div>
    <div class="container">
        <?php
        if($response!=0){
            echo '  <div style="margin-top: 10px" class="alert';
            if($response >0) echo ' alert-success ';
            else  echo ' alert-danger ';
            echo ' alert-dismissible fade show" role="alert">';
             
            if($response==-1){
                echo '<strong>Invalid Details!</strong> Please enter valid Details that follow the guidelines stated below the fields.';
            }else if($response==-2){
                echo '<strong>Username Already Taken!</strong> Please use other Username.';           
            }else if($response==-3){
                echo '<strong>Failed to Register User!</strong> Please try again.';           
            }else if($response==1){
                echo '<strong>User Registered Successfully!</strong> Please Login via the Login Page.';   
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
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="admissionNoLabel">Admission Number</label>
                <input type="text" class="form-control" name="admissionNo" id="admissionNo" aria-describedby="name" placeholder="Enter Admission Number" required>
                <small id="admissionNoHelp" class="form-text text-muted">Please enter your Admission Number(Eg. UXXXXXXX)</small>
            </div>
            <div class="form-group">
                <label for="fullNameLabel">Full Name</label>
                <input type="text" class="form-control" name="fullName" id="fullName" aria-describedby="name" placeholder="Enter Full Name..." required>
                <small id="fullNameHelp" class="form-text text-muted">Please enter your Full name (First Name Middle Name Last Name)</small>
            </div>
            <div class="form-group">
                <label for="usernameLabel">Username</label>
                <input type="text" class="form-control" name="username" id="username" aria-describedby="username" placeholder="Enter Desired Username..." required>
                <small id="usernameHelp" class="form-text text-muted">Please enter your desired Username (Must only contain Alphanumerical Characters)</small>
            </div>
            <div class="form-group">
                <label for="passwordLabel">Password</label>
                <input type="password" class="form-control" name="password" id="password" aria-describedby="password" placeholder="Enter Password..." required>
                <small id="passwordHelp" class="form-text text-muted">Enter your Password (Must have length more than 9,at least one uppercase letter, lowercase letter, number and special character)</small>
            </div>
            <div class="form-group">
                <label for="branchLabel">Branch</label>
                <input type="text" class="form-control" name="branch" id="branch" aria-describedby="branch" placeholder="Enter Branch..." required>
                <small id="branchHelp" class="form-text text-muted">Please enter your Pursued Engineering Field</small>
            </div>
            <div class="form-group">
                <label for="cgpaLabel">Current CGPA</label>
                <input type="number" step="any" name="cgpa" class="form-control" id="cgpa" aria-describedby="cgpa" required >
                <small id="cgpaHelp" class="form-text text-muted">Please enter your Current CGPA</small>
            </div>
            <div class="form-group">
                <label for="addressLabel">Address</label>
                <input type="text" class="form-control" name="address" id="address" aria-describedby="address" placeholder="Enter your Address..." required>
                <small id="addressHelp" class="form-text text-muted">Please enter your Home Address</small>
            </div>
            <button type="submit" class="btn btn-primary">Register Account</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>

</html>
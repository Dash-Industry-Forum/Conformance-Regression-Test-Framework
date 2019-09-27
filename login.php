<?php
session_start();

// If user is logged in, redirect to home page
if ($_SESSION['loggedIn'] == true){
    header('Location:index.php');
}

// Initializing the flag
$password_match = true;

if (isset($_POST['email']) && isset($_POST['password']))
{
    // Connect to database
    require_once 'ConnectToDb.php'; 
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Search database for given email
        $db_user = $db->test_Users->findOne(['email'=>$email]);

        $db_email = $db_user['email'];
        $db_password = $db_user['password'];

        // If user input and stored data is same, then redirect to home page
        if (($db_email == $email) && ($db_password == $password)) {
            $_SESSION['loggedIn'] = true;
            $password_match = true;

            header('Location:index.php');
        }
        else{
            $_SESSION['loggedIn'] = false;

            // This flag will be used in the PHP script below the HTML code
            $password_match = false;
        }
    }
    catch(MongoDB\Driver\Exception\Exception $catchedException) {
        logException(get_class($catchedException)." : ".$catchedException->getMessage());
        unset($_POST['email']);
        unset($_POST['password']);
    }
}
?>


<html>

<head>
<title>Log In - MPEG-DASH Conformace Testing</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>


<body>
    <div id="div_login_form">             
        <form method="post" action="">
          
            <div id='div_email'>
                <label for="input_email" class="labels"><b>Email Address</b> *</label>
                <br>
                <input required type="email" name="email" id ="input_email" placeholder="myemail@domain.com" autocomplete="off">
                    
                    <!-- In case format and hover text is required, add the below lines as attributes to the above input tag -->
                    <!-- pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" -->
                    <!-- title="myemail@domain.com" -->
            </div>

            <br>
            
            <div id='div_password'>
                <label for="input_password" class="labels"><b>Password </b>*</label>
                <br>
                <input required type="password" name="password" id="input_password" placeholder="********" autocomplete="off">

                    <!-- In case format and hover text is required, add the below lines as attributes to the above input tag -->
                    <!-- pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" -->
                    <!-- title="At least: One numeric character; One uppercase alphabetical letter; One lowercase alphabetical letter; Eight or more characters" -->
            </div>

            <br>

            <div id='div_login_button'>
                <button id="button_login">Login</button>
            </div>
            
            <!-- <p class="forgot"><a href="forgot_password.php">Forgot Password?</a></p> -->

              
        </form>
        
    </div>

</body>
</html>

<?php
// Runs only if user entered details don't match with any entry in the database
if($password_match == false){
    echo "<script type='text/javascript'>

    // Insert paragraph before the login button
    document.getElementById('div_login_button').insertAdjacentHTML('beforebegin','<p>Invalid username or password.</p>');
    document.getElementById('div_login_button').previousElementSibling.style.color = 'red'; 
    
    </script>";
}
?>
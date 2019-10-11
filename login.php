<?php
session_start();

// Collections that will be used throughout the entire process
// $_SESSION['users'] = 'test_Users';
$_SESSION['test_vectors'] = 'test_testVectors';

// If user is logged in, redirect to home page
if ($_SESSION['loggedIn'] == true){
    header('Location:index.php');
}

// Initializing the flag
$password_incorrect = false;

if (isset($_POST['email']) && isset($_POST['password']))
{
    // Connect to database
    require_once 'ConnectToDb.php'; 
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Search database for given email
        $db_collection = $db->selectCollection($_SESSION['users']);
        $db_user = $db_collection->findOne(['email'=>$email]);

        $db_email = $db_user['email'];
        $db_password = $db_user['password'];    // the hashed password already stored in database   

        // If user input and stored data is same, then redirect to home page
        if (($db_email == $email) && (password_verify($password, $db_password))) {
            $_SESSION['loggedIn'] = true;
            $password_incorrect = false;

            header('Location:index.php');
        }
        else{
            $_SESSION['loggedIn'] = false;

            // This flag will be used in the PHP script below the HTML code
            $password_incorrect = true;
        }
    }
    catch(MongoDB\Driver\Exception\Exception $catchedException) {
        unset($_POST['email']);
        unset($_POST['password']);

        logException(get_class($catchedException)." : ".$catchedException->getMessage());
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
                <label for="input_email" class="labels"><b>Username or Email</b> *</label>
                <br>
                <input required type="text" name="email" id ="input_email" placeholder="Username or Email" autocomplete="off">
                    
            </div>

            <br>
            
            <div id='div_password'>
                <label for="input_password" class="labels"><b>Password </b>*</label>
                <br>
                <input required type="password" name="password" id="input_password" placeholder="********" autocomplete="off">

            </div>

            <br>

            <div id='div_login_button'>
                <button id="button_login">Login</button>
            </div>
                        
        </form>
        
    </div>

</body>
</html>

<?php
// Runs only if user entered details don't match with any entry in the database
if($password_incorrect == true){
    echo "<script type='text/javascript'>

    // Insert paragraph before the login button
    document.getElementById('div_login_button').insertAdjacentHTML('beforebegin','<p>Invalid username or password.</p>');
    document.getElementById('div_login_button').previousElementSibling.style.color = 'red'; 
    
    </script>";
}
?>
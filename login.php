<?php
session_start();

// Collections that will be used throughout the entire process
// $_SESSION['users'] = 'test_Users';
$_SESSION['test_vectors'] = 'test_testVectors';
$_SESSION['users'] = 'test_Users';

// If user is logged in, redirect to home page
if ($_SESSION['loggedIn'] == true){
    header('Location:index.php');
}

// // Initializing the flag
// $password_incorrect = false;

if (isset($_POST['email']) && isset($_POST['password']))
{
    $_SESSION['email'] = $_POST['email'];
    $_SESSION['password'] = $_POST['password'];
    
    // Connect to database
    require_once 'ConnectToDb.php'; 
    
    $_SESSION['loggedIn'] = true;
    // $password_incorrect = false;

    header('Location:index.php');
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
// // Runs only if user entered details don't match with any entry in the database
// if($password_incorrect == true){
//     echo "<script type='text/javascript'>

//     // Insert paragraph before the login button
//     document.getElementById('div_login_button').insertAdjacentHTML('beforebegin','<p>Invalid username or password.</p>');
//     document.getElementById('div_login_button').previousElementSibling.style.color = 'red'; 
    
//     </script>";
// }
?>
<?php
    include("includes/config.php");
    include("includes/classes/Account.php");
    include("includes/classes/Constants.php");

    $account = new Account($con);

    include("includes/handlers/register-handler.php");
    include("includes/handlers/login-handler.php");

    if (isset($_POST['loginButton'])) {
        $username = $_POST['loginUsername'];
        $password = $_POST['loginPassword'];

        // Verify login credentials
        $wasSuccessful = $account->login($username, $password);

        if ($wasSuccessful) {
            // Login tracking logic
            $ipAddress = $_SERVER['REMOTE_ADDR']; // Capture the user's IP address
            $page = "login"; // The event/page type for tracking

            // Insert login event into the tracking table
            $query = $con->prepare("INSERT INTO traffic (page, date, ip_address) VALUES (?, NOW(), ?)");
            $query->bind_param("ss", $page, $ipAddress);
            $query->execute();
            exit();
        }
    }


    function getInputValue($name) {
        if (isset($_POST[$name])) {
            echo $_POST[$name];
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to RYYTHWAVE</title>
    <link rel="icon" type="image/x-icon" href="public/assets/images/logo.png">
    <link rel="stylesheet" href="public/assets/css/login.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/register.js"></script>



</head>
<body>

<?php
if (isset($_POST['registerButton'])) {
    echo '<script>
            $(document).ready(function() {
                $("#loginForm").hide();
                $("#registerForm").show();
            });
          </script>';
} else {
    echo '<script>
            $(document).ready(function() {
                $("#loginForm").show();
                $("#registerForm").hide();
            });
          </script>';
}
?>


<video autoplay muted loop id="video-bg">
    <source src="public/assets/images/bg_video3_V1.mp4" type="video/mp4">
    Your browser does not support the video tag.
</video>

<div class="container">
    <div class="welcome-section">
        <h1>Welcome to RYYTHWAVE</h1>
        <p>"Where words fail, music speaks." - Hans Christian Andersen ‎ ‎ ‎ ‎ ‎ </p>
    </div>
    <div class="login-section">
        <h2>User Login</h2>

		
        <!-- Login Form -->
        <form id="loginForm" action="register.php" method="POST">
            <div class="input-group">
                <div class="error-message"><?php echo $account->getError(Constants::$loginFailed); ?> </div>
                <input id="loginUsername" name="loginUsername" type="text" placeholder="Eg. username" value="<?php getInputValue('loginUsername') ?>" required>
                <img draggable="false" src="public/assets/icons/people-fill.svg">
            </div>
            <div class="input-group">
                <input id="loginPassword" name="loginPassword" type="password" placeholder="Your Password" required>
                <img draggable="false" src="public/assets/icons/lock-fill.svg">
            </div>
            <div class="options">
			<div class="checkbox-wrapper-30">
                        <span class="checkbox">
                            <input type="checkbox" />
                            <svg>
                                <use xlink:href="#checkbox-30" class="checkbox"></use>
                            </svg>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none">
                            <symbol id="checkbox-30" viewBox="0 0 22 22">
                                <path
                                fill="none"
                                stroke="#8f44fd"
                                d="M5.5,11.3L9,14.8L20.2,3.3l0,0c-0.5-1-1.5-1.8-2.7-1.8h-13c-1.7,0-3,1.3-3,3v13c0,1.7,1.3,3,3,3h13 c1.7,0,3-1.3,3-3v-13c0-0.4-0.1-0.8-0.3-1.2"
                            />
                            </symbol>
                        </svg>
                        <label>Remember</label>
                    </div>
                <a href="forgetpass.html">Forgot password?</a>
            </div>
            <button type="submit" name="loginButton">Login</button>
            <div class="hasAccountText">
                <span id="hideLogin" id="a" >Signup here.</span>
            </div>
        </form>

        <!-- Registration Form -->
        <form id="registerForm" action="register.php" method="POST">
            <div class="input-group">
                <?php echo $account->getError(Constants::$usernameCharacters); ?>
                <?php echo $account->getError(Constants::$usernameTaken); ?>
                <label for="username">Username</label>
                <input id="username" name="username" type="text" placeholder="e.g. bartSimpson" value="<?php getInputValue('username') ?>" required>
            </div>
            <div class="input-group">
                <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                <label for="firstName">First name</label>
                <input id="firstName" name="firstName" type="text" placeholder="e.g. Bart" value="<?php getInputValue('firstName') ?>" required>
            </div>
            <div class="input-group">
                <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                <label for="lastName">Last name</label>
                <input id="lastName" name="lastName" type="text" placeholder="e.g. Simpson" value="<?php getInputValue('lastName') ?>" required>
            </div>
            <div class="input-group">
                <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
                <?php echo $account->getError(Constants::$emailInvalid); ?>
                <?php echo $account->getError(Constants::$emailTaken); ?>
                <label for="email">Email</label>
                <input id="email" name="email" type="email" placeholder="e.g. bart@gmail.com" value="<?php getInputValue('email') ?>" required>
            </div>
            <div class="input-group">
                <label for="email2">Confirm email</label>
                <input id="email2" name="email2" type="email" placeholder="e.g. bart@gmail.com" value="<?php getInputValue('email2') ?>" required>
            </div>
            <div class="input-group">
                <?php echo $account->getError(Constants::$passwordsDoNoMatch); ?>
                <?php echo $account->getError(Constants::$passwordNotAlphanumeric); ?>
                <?php echo $account->getError(Constants::$passwordCharacters); ?>
                <label for="password">Password</label>
                <input id="password" name="password" type="password" placeholder="Your password" required>
            </div>
            <div class="input-group">
                <label for="password2">Confirm password</label>
                <input id="password2" name="password2" type="password" placeholder="Your password" required>
            </div>
            <button type="submit" name="registerButton">Create Account</button>
            <div class="hasAccountText">
                <span id="hideRegister" id="a">Already have an account? Log in here.</span>
            </div>
        </form>
    </div>
    <div class="admin-button">
        <a href="adminlogin.php">
            <button type="button">Admin</button>
        </a>
    </div>
</div>
<div class="copy">
    <img src="public/assets/icons/toppng.com-copyright-symbol-png-white-copyright-logo-in-white-2000x2000.png">
    <h6>copyright owned by Helix</h6>
</div>

</body>
</html>

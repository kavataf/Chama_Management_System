<?php
/* Login */
if (isset($_POST['Login'])) {
    $user_email = trim($_POST['user_email']);
    $user_password = trim($_POST['user_password']);

    // prepare
    $stmt = $mysqli->prepare("SELECT * FROM users WHERE user_email = ?");
    $stmt->bind_param("s", $user_email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        // Verify the password
        if (password_verify($user_password, $row['user_password'])) {
            // Bind session variables
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['user_email'] = $row['user_email'];
            $_SESSION['user_access_level'] = $row['user_access_level'];
            $_SESSION['success'] = 'Login successful';

            // Generate and send auth code
             $auth_code = rand(100000, 999999);
             $_SESSION['auth_code'] = $auth_code;
             $_SESSION['auth_code_created_at'] = time(); 

             // Send code via email
             require_once('../mailers/send-auth-code.php');
 
            // Redirect to auth-code page
             header("Location: auth-code.php");
             exit;
        } else {
            $err = "Invalid login credentials";
        }
    } else {
        $err = "Invalid login credentials";
    }
}

/* sign up */
if(isset($_POST['signup'])){
   $user_name = trim($_POST['user_name']);
   $user_email = trim($_POST['user_email']);
   $user_password = trim($_POST['user_password']);
   $confirm_password = trim($_POST['confirm_password']);

// validation
if(empty($user_name) || empty($user_email) || empty($user_password) || empty($confirm_password)){
    $err = "All fields required";
} elseif(!filter_var($user_email, FILTER_VALIDATE_EMAIL)){
    $err = "Wrong email format";
} elseif($user_password !== $confirm_password){
    $err = "passwords should match";
} else{
    // hash password
    $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
    // prepare statement
    $stmt = $mysqli -> prepare("INSERT INTO users (user_name, user_email, user_password) VALUES (?, ?, ?)");
    $stmt -> bind_param('sss', $user_name, $user_email, $user_password);
    if($stmt -> execute()){
        $success = "successful signup, please login";
        header("location: login.php");
        exit;
    } else {
        $err = "Something went wrong, please try again";
    }
}
}
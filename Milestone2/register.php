<?php require(__DIR__ . "../partials/nav.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <style>
                body {
            background-color: lightblue;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif, sans-serif;
            font-size: 18px;
            line-height: 1.5;
            padding: 20px;
        }
        h1 {
            color: #333;
            font-size: 32px;
            margin-bottom: 20px;
            text-align: center;
            text-shadow: 1px 1px #ccc;
        }
        form {
            background-color: #fff;
            border: 1px solid #ccc;
            padding: 20px;
            max-width: 500px;
            margin: 0 auto;
            border-radius: 10px;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-size: 20px;
        }
        input[type="email"],
        input[type="password"] {
            border: 1px solid #ccc;
            font-size: 18px;
            padding: 10px;
            width: 99%;
            margin-bottom: 20px;
        }
        input[type="submit"] {
            background-color: #007bff;
            border: none;
            color: #fff;
            font-size: 20px;
            padding: 10px 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0062cc;
        }
        .error {
            color: #ff0000;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .success {
            color: #008000;
            margin-bottom: 20px;
        }
    </style>
    </style>
</head>
<body>
<h1>Register your account</h1>
    <form onsubmit="return validate(this)" method="POST">
        <div>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required />
        </div>
        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required />
        </div>
        <div>
            <label for="pw">Password</label>
            <input type="password" id="pw" name="password" required minlength="8" />
        </div>
        <div>
            <label for="confirm">Confirm Password</label>
            <input type="password" id="confirm" name="confirm" required minlength="8" />
        </div>
        <button type="submit">Register</button>
        
    </form>
    <script>
        function validate(form) {
            // Check if password and confirm password match
            if (form.password.value != form.confirm.value) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        }
    </script>
<?php
//TODO 2: add PHP Code
if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["confirm"])) {
    $email = se($_POST, "email", "", false);
    $password = se($_POST, "password", "", false);
    $confirm = se(
        $_POST,
        "confirm",
        "",
        false
    );
    //TODO 3
    $hasError = false;
    if (empty($email)) {
        echo "Email must not be empty";
        $hasError = true;
    }
    //sanitize
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    //validate
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address ";
        $hasError = true;
    }
    if (empty($password)) {
        echo " password must not be empty";
        $hasError = true;
    }
    if (empty($confirm)) {
        echo " Confirm password must not be empty";
        $hasError = true;
    }
    if (strlen($password) < 8) {
        echo " Password too short";
        $hasError = true;
    }
    if (
        strlen($password) > 0 && $password !== $confirm
    ) {
        echo "Passwords must match";
        $hasError = true;
    }
    if (!$hasError) {
        echo "Welcome, $email";
        //TODO 4
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Users (email, password) VALUES(:email, :password)");
        try {
            $stmt->execute([":email" => $email, ":password" => $hash]);
            echo " Successfully registered!";
        } catch (Exception $e) {
            echo "There was a problem registering";
            "<pre>" . var_export($e, true) . "</pre>";
        }
    }
    
}
?>
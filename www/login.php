<?php

$is_invalid = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $mysqli = require __DIR__ . "/database.php";
    
    $sql = sprintf("SELECT * FROM user
                    WHERE email = '%s'",
                   $mysqli->real_escape_string($_POST["email"]));
    
    $result = $mysqli->query($sql);
    
    $user = $result->fetch_assoc();
    
    if ($user) {
        
        if (password_verify($_POST["password"], $user["password_hash"])) {
            
            $header = [
                'alg' => 'HS256',
                'typ' => 'JWT'
            ];

            $header = json_encode($header);

            $header = base64_encode($header);

            $duracao = time() + (60 * 60);
            $payload = [
                //'iss' => 'localhost',
                //'aud' => 'localhost',
                'exp' => $duracao,
                'id' => $user['id'],
                'nome' => $user['name'], // ??
                'email' => $user ['email'] // ??
            ];

            $payload = json_encode($payload);

            $payload = base64_encode($payload);

            ///
            $chave = "GFACCPM1L4NM4T4K3NDR1CKYE";

            $signature = hash_hmac('sha256', "$header.$payload", $chave, true);

            $signature = base64_encode($signature);

            /* echo "Token: $header.$payload.$signature <br>";*/


            setcookie('token', "$header.$payload.$signature", (time() + (60*60)));

            ///
            session_start();
            
            session_regenerate_id();
            
            $_SESSION["user_id"] = $user["id"];
            
            header("Location: index.php");

            exit;
        }
    }
    
    $is_invalid = true;
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        #recuperar-pass {
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
            padding: 5px 10px;
            background-color: #16537e;
            border: 1px solid #16537e;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    
    <h1>Login</h1>
    
    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>
    
    <form method="post">
        <label for="email">email</label>
        <input type="email" name="email" id="email"
               value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
        
        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        
        <button type="submit">Log in</button>
        <button id="recuperar-pass" formaction="recuperarpass.php">Recuperar palavra-passe</button>
    </form>
    
</body>
</html>









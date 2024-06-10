<!DOCTYPE html>
<html>
<head>
    <title>Recuperar palavra-passe</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        #recuperar-pass {
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
            padding: 5px 10px;
            background-color: #4CAF50;
            border: 1px solid #4CAF50;
            border-radius: 4px;
            color: white;
            cursor: pointer;
        }
    </style>
</head>
<body>
    
    <h1>Recuperar palavra-passe</h1>

    <?php
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
    //var_dump($dados);

    if (!empty($dados['SendRecupPass'])) {

        /* var_dump($dados); */

        $mysqli = require __DIR__ . "/database.php";
    
        $sql = sprintf("SELECT * FROM user
                        WHERE email = '%s'",
                       $mysqli->real_escape_string($_POST["email"]));
        
        $result = $mysqli->query($sql);
        
        $user = $result->fetch_assoc();
        //var_dump($user);
        if ($user) {
            $chave_recup_pass = password_hash($user['id'], PASSWORD_DEFAULT);
            $id = $user['id'];
        
            $stmt = $mysqli->prepare("UPDATE user SET recovery_password = ? WHERE id = ? LIMIT 1");
            $stmt->bind_param("si", $chave_recup_pass, $id);
            $stmt->execute();
        
            if ($stmt->affected_rows > 0) {
                echo "<a href='http://localhost:81/atualizarpass.php?chave=$chave_recup_pass'>Clique aqui</a>";
            } else {
                $_SESSION["msg"] = "<p style='color:red;'>Tente outra vez!</p>";
            }
        }
        else {
            $_SESSION["msg"] = "<p style='color:red;'>O utilizador não existe!</p>";
        }

        if(isset($_SESSION["msg"])){
            echo  $_SESSION["msg"];
            unset( $_SESSION["msg"]);
        }
    }
    ?>

    <form method="post" action="">
    <?php
    $user = "";
    if(isset($dados['email'])){echo $dados['email']; }
    ?>
        <br>
        <label for="email">Insira o email:</label>
        <input type="text" name="email" placeholder="Escrever o email"
               value="<?php echo $user; ?> ">
        
        <input type="submit" value="Recuperar" name="SendRecupPass">
    </form>

    <br>
    Lembrou-se da Password? <a href="index.php">Fazer login</a>
    
</body>
</html>
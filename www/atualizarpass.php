<!DOCTYPE html>
<html>
<head>
    <title>Atualizar palavra-passe</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <style>
        #atualizar-pass {
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
    
    <h1>Atualizar palavra-passe</h1>

    <?php
    $mysqli = require __DIR__ . "/database.php";
    $chave = filter_input(INPUT_GET, 'chave', FILTER_DEFAULT);
    // ok var_dump($chave);
    if(!empty($chave)){
      
        $sql = "SELECT id FROM user WHERE recovery_password = '$chave' LIMIT 1";
        $result = $mysqli->query($sql);
        $result_id = $result->fetch_assoc();
        // ok var_dump($result_id);

        if(is_null($result_id)) {
            $_SESSION["msg"] = "<p style='color:red;'>Link Inválido, solicite um novo link para atualizar a password!</p>";
            header('Location: recuperarpass.php');
            //die();
        }
        
        if(!empty($result_id)){
            // sucesso!
            
            $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            // ok var_dump($dados);
            if (!empty($dados['atualizarpass'])) {
                $novapass = password_hash($dados['password'], PASSWORD_DEFAULT);
                $id = $result_id['id'];
                $recuperarpass = 'NULL';
                $stmt = $mysqli->prepare("UPDATE user SET password_hash = ?, recovery_password = $recuperarpass WHERE id = ? LIMIT 1");
                $stmt->bind_param("si", $novapass, $id);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    $_SESSION["msg"] = "<p style='color:green;'>Password alterada com sucesso!</p>";
                    //header("Location: index.php");
                } else {
                    echo "<p style='color:red;'>Tente outra vez!</p>";
                }
            } 
        }
    }else{
        $_SESSION["msg"] = "<p style='color:red;'>Link Inválido(2)!</p>";
        //header("Location: recuperarpass.php");
    }

    if(isset($_SESSION["msg"])){
        echo $_SESSION["msg"];
        unset( $_SESSION["msg"]);
    }
?>
    
    <form method="post" action="">
        <?php
        $user = "";
        if (isset($dados['password'])) {
            $user = $dados['password'];
        } ?>
        <label for="password">Insira a nova palavra-passe:</label>
        <input type="password" name="password" value="<?php echo $user; ?>"> <br>
        
        <button type="submit" id="atualizar-pass" value="Atualizar" name="atualizarpass">Atualizar palavra-passe</button>
    </form>

    <br>
    Lembrou-se da Password? <a href="index.php">Fazer login</a>
    
</body>
</html>
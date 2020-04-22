<?php require('class/autoload.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Usuário</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form method="post" class="login-user">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Senha:</label>
        <input type="password" name="password" id="password" required>

        <input type="submit" value="Enviar">
    </form>

    <?php
    if( isset($_POST['email']) && isset($_POST['password']) ):
        $user=new User();
        $canLogin=$user->login($_POST['email'],$_POST['password']);
        if ($canLogin ===True): header("Location:public/");
        else:
    ?>
        <center>
            <p>Email ou Senha incorretos por favor verique e tente novamente</p>
        </center>
    <?php
        endif;
    endif;
    ?>


</body>
</html>

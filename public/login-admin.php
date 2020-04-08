<?php 
    require('class/autoload.php');
    $admin = new Admin();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login de Admin</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        form.login-user{
            background:Blue;
        }
    </style>
</head>
<body>
    <form action="" method="post" class="login-user">
        
        <label for="email">Email:</label>
        <input type="email" name="email" id="email">

        <label for="password">Senha:</label>
        <input type="password" name="password" id="password">

        <input type="submit" value="enviar">
    </form>
    <?php 
        if ( (isset($_POST['email'])) && (isset($_POST['password'])) ):
        
            $canLogin=$admin->login($_POST['email'],$_POST['password']);
            if ($canLogin):
                header("Location:dashboard-admin");
            else:
    ?>
            <p>Email ou senha Incorretos, por favor verique e tente novamente</p>
    <?php

            endif;
        endif;
    ?>
</body>
</html>
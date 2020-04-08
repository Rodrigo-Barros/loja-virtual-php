<?php require('class/autoload.php'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <form action="" method="post" class="register-user" >

        <label for="nome">Nome:</label>
        <input type="text" placeholder="Digite seu nome" id="nome" required="true" name="nome">
    
        <label for="email">Email:</label>
        <input type="email" placeholder="Digite seu email" id="email" required="true" name="email">

        <label for="senha">Senha:</label>
        <input type="password" placeholder="Digite sua senha" id="senha" required="true" name="password">  

        <input type="submit" value="enviar">
    </form>

    <?php 
    if( isset($_POST['email']) && isset($_POST['password']) ):
        $user=new User();
        $emailNotExists=$user->createUser($_POST['email'],$_POST['password'],$_POST['nome']);
        
        if ($emailNotExists ===True): header("Location:dash-user.php");
        else:
    ?>
        <center>
        <p>Email Já cadastrado na base de dados, tente outro por favor</p>
       </center>
    <?php
        endif;
    endif;
    ?>
</body>
</html>
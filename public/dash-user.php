<?php

use function PHPSTORM_META\type;

require('class/autoload.php');
    session_start();
    if( isset($_SESSION['userInfo'])==False || $_SESSION['userInfo']['userType']!=='user') {
        header("Location:public/");
    }
    $user = new User;
    $pedidos = $user->listarPedidos($_SESSION['userInfo']['id']);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Controle do Usúario</title>
    <link rel="stylesheet" href="css/dash-user.css">
</head>
<body>
    <nav id="top-menu">
        <ul id="top-menu-left">
            <li><a href="/ecommerce" title="Leva a página incial">Ecommerce</a></li>
            <li><a href="#pedidos" title="Acompanhamento do adamento dos Produtos">Pedidos</a></li>
            <li><a href="/ecommerce/Carrinho">Carrinho</a></li>
        </ul>

        <ul id="top-menu-right">
            <li><a href="#">
            <?= $_SESSION['userInfo']['nome'] ?? 'Teste' ;?>
            </a></li>
            <ul>
                <li>
                    <a href="#editar" title="Permite modificar dados da sua conta como senha entre outras opções">editar</a>
                </li>
                <li><a href="logout.php" title="Encerra sua sessão">sair</a></li>
            </ul>    
        </ul>
    </nav>
    
    <div id="paginas">
        <div id="pedidos">
            <h1>Pedidos</h1>
            <table>
                <thead>
                    <th>ID do pedido</th>
                    <th>Status</th>
                    <th>Ações</th>
                </thead>
                <tbody>
                    <!-- <tr>
                        <td>1</td>
                        <td>1 televisão, 2 smarth phones</td>
                        <td>799,99</td>
                        <td><a href="#">Mais Detalhes</a></td>
                    </tr> -->
                    <?php 
                    foreach ($pedidos as $pedido):
                        
                        ?>  
                            <tr>
                                <td ><?=$pedido['id']?></td>
                                <td ><?=($pedido['status_pedido'])==1? "Finalizado": "Processando" ?></td>
                                <td><a href="pedido/<?=$pedido['id']?>">Mais Detalhes</a></td>
                            </tr>

                        <?php
                        
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
        <div id="editar">
            <h1>Dados</h1>
            <?php
                $userInfo = new User();
                $field = $userInfo->getUserInfo();
            ?>
            <form action="" method="post" class="dados">
                <label for="nome">Nome Completo:</label>
                <input id="nome" type="text" name="nome" value="<?=$field->nome?>">

                <label for="email">Email:</label>
                <input id="email" type="email" name="email" value="<?=$field->email?>">

                <label for="current-pass">Senha:</label>
                <input name="current-pass" id="current-pass" type="password">
                
                <label for="estado">Estado:</label>
                <select name="estado" id="estado" required name="estado">
                    <option value="">Escolha seu Estado</option>
                    <option value="SP">São Paulo</option>
                    <option value="RJ">Rio de Janeiro</option>
                    <option value="MG">Minas Gerais</option>
                </select>

                <label for="cidade">Cidade:</label>
                <input id="cidade" type="text" required name="cidade" value="<?=$field->cidade?>">

                <label for="birth">Nascimento:</label>
                <input id="birth" type="date" name="nascimento" value="<?=$field->nascimento?>">

                <label for="phone">telefone:</label>
                <input id="phone" type="text" name="telefone" value="<?=$field->telefone?>">

                <label for="bairro">Bairro:</label>
                <input id="bairro" type="text" required name="bairro" value="<?=$field->bairro?>">

                <label for="logradouro">Rua ou Avenida:</label>
                <input id="logradouro" type="text" required name="logradouro" value="<?=$field->endereco?>">

                <label for="cep">Cep:</label>
                <input id="cep" type="text"  maxlength="8" required name="cep" value="<?=$field->cep?>">

                <input type="submit" value="Atualizar">

            </form>
        </div>
    </div>
</body>
</html>
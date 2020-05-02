<?php
    require 'class/autoload.php';
    session_start();
    if( isset($_SESSION['userInfo'])==False || $_SESSION['userInfo']['userType']!=='user') {
        header("Location:/ecommerce/public/logout.php");
    }
    $pedidos = Database::sql("SELECT Pedidos.id, Produtos.nome as produto, Produtos.preco,
    Pedidos.meio_pagamento, Pedidos.idPagamento,itemPedido.quantidade, Usuarios.nome as user,
    Usuarios.endereco, Usuarios.estado, Usuarios.cidade, Usuarios.cep, Usuarios.bairro
    FROM Pedidos
        INNER JOIN itemPedido
        ON itemPedido.idPedido = Pedidos.id
        INNER JOIN Produtos
        ON itemPedido.idProduto = Produtos.id
        INNER JOIN Usuarios
        ON Usuarios.id = Pedidos.usuario_id
    WHERE Pedidos.usuario_id = :userId AND Pedidos.id = :pedidoId");
    $pedidos->bindParam(':userId',$_SESSION['userInfo']['id']);
    $pedidos->bindParam('pedidoId',$_GET['pedidoId']);
    $pedidos->execute();
    $total = 0;
?>

<html>
    <?php
        require 'head.php';
    ?>
    <head>
        <link rel="stylesheet" href="css/dash-user.css">
    </head>
    <style>
        table{
            width: 100%;
            margin-left: 0;
        }

        h1{
            margin-top: 70px;
        }

        table, tr,td,th{
            border:1px solid black;
            border-collapse: collapse;
            text-align:center;
        }

    </style>
    <body>
        <?php require 'header-user.php' ?>


        <h1>Detalhes do Pedido: <?=$_GET['pedidoId']?></h1>
        <table>
            <thead>
                <th>Produto</th>
                <th>Preco</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
            </thead>
            <tbody>
            <?php
                foreach($pedidos as $pedido):
                    $total += $pedido['quantidade'] * $pedido['preco'];
            ?>
            <tr>
                <td><?=$pedido['produto']?></td>
                <td><?=number_format($pedido['preco'],2,',','.')?></td>
                <td><?=$pedido['quantidade']?></td>
                <td>R$ <?=number_format($pedido['quantidade'] * $pedido['preco'],2,',','.')?></td>
            </tr>

            <?php
                endforeach;
                $pedidos->execute();
                $pedidoInfo = $pedidos->fetchObject();
            ?>



            <tr>
                <td colspan="3" style="text-align:right;border:unset">Total:</td>
                <td style="border-left:unset"> R$ <?=number_format($total,2,',','.')?></td>
            </tr>
            </tbody>
        </table>

        <div class="user-info">
          <p>Destinatario: <?=$pedidoInfo->user?></p>
          <p>Estado: <?=$pedidoInfo->estado?></p>
          <p>Cidade: <?=$pedidoInfo->cidade?></p>
          <p>Bairro: <?=$pedidoInfo->bairro?></p>
          <p>Endereco: <?=$pedidoInfo->endereco?></p>
          <p>CEP: <?=$pedidoInfo->cep?></p>
          <p>Cidade: <?=$pedidoInfo->cidade?></p>
          <p> Método de Pagamento: <?=$pedidoInfo->meio_pagamento?></p>
          <?php if ($pedidoInfo->idPagamento !== 'default'): ?>
            <?php $accessToken = 'TEST-8864676676772087-041722-7ef8cc5db28f3f3fce77e4b05395c34e-194214343'; ?>
          <p>Indentificação do Pagamento: <?=$pedidoInfo->idPagamento?></p>
          <a href="https://api.mercadopago.com/v1/payments/<?=$pedidoInfo->idPagamento?>?access_token=<?=$accessToken?>">Detalhes do pagamento</a>
          <?php endif; ?>
        </div>
    </body>
</html>

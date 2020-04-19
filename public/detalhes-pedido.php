<?php 
    require 'class/autoload.php';
    session_start();
    if( isset($_SESSION['userInfo'])==False || $_SESSION['userInfo']['userType']!=='user') {
        header("Location:/ecommerce/public/logout.php");
    }
    $pedidos = Database::sql("SELECT Pedidos.id, Produtos.nome, Produtos.preco, itemPedido.quantidade
    FROM Pedidos
        INNER JOIN itemPedido
        ON itemPedido.idPedido = Pedidos.id
        INNER JOIN Produtos
        ON itemPedido.idProduto = Produtos.id
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
                <td><?=$pedido['nome']?></td>
                <td><?=number_format($pedido['preco'],2,',','.')?></td>
                <td><?=$pedido['quantidade']?></td>
                <td>R$ <?=number_format($pedido['quantidade'] * $pedido['preco'],2,',','.')?></td>
            </tr>
                
            <?php 
                endforeach;
            ?>

            <tr>
                <td colspan="3" style="text-align:right;border:unset">Total:</td>
                <td style="border-left:unset"> R$ <?=number_format($total,2,',','.')?></td>
            </tr>
            </tbody>
        </table>
    </body>
</html>
<?php 
    require 'class/autoload.php';
    $store = new Store();
    session_start(); 
    if (isset($_SESSION['userInfo'])==False || $_SESSION['userInfo']['userType']=='admin'){
        header('Location: public');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php require 'head.php'; ?>
<body>
    <?php require 'header.php'; ?>
    
    <main>
        <table id="carrinho">
            <thead>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço unitário</th>
                <th>Preço Total</th>
                <th>Ação</th>
            </thead>
            <tbody>
                <!-- <tr>
                    <td>PC master Racer</td>
                    <td><input type="number" max="10" min="1" ></td>
                    <td>R$ 5.000,00</td>
                    <td>R$ 10.000,00</td>
                    <td><a class="remove-btn" href="#">Remover</a></td>
                </tr> -->

                <?php 
                    if(isset($_GET['delete_produto'])){
                        // echo $_GET['delete_produto'];
                        $del_sts=$store->removeFromCart($_GET['delete_produto'],$_SESSION['userInfo']['id']);
                        if($del_sts):
                    ?>
                        <p class="produto-removido">Produto Removido do Carrinho</p>
                        <form action="">
                    <?php
                        endif;
                    }
                    $produtos=$store->selectItemsFromCart($_SESSION['userInfo']['id']);
                    $total = 0;
                    foreach ($produtos as $produto):
                        $total += $produto['preco'] * $produto['quantidade'];
                        
                ?>
                    <tr>
                    <td><img src="uploads/<?=json_decode($produto['imagens'])[0]?>" alt="Imagem <?=$produto['nome'];?>"><p><?=$produto['nome']?></p></td>
                    <td><input type="number" max="<?=$produto['estoque']?>" min="1" value="<?=$produto['quantidade'];?>"></td>
                    <td>R$ <?=number_format($produto['preco'], 2,',','.')?></td>
                    <td>R$ <?=number_format($produto['preco']*$produto['quantidade'], 2,',','.')?></td>
                    <td><a class="remove-btn" href="Carrinho?delete_produto=<?=$produto['id'];?>">Remover</a></td>
                </tr>
                    
                <?php
                    endforeach;
                    
                ?>

                <!-- Não alterar essa linha -->
                <tr><td colspan="4">Total: R$ <?=number_format($total, 2,',','.')?></td></tr>
            </tbody>
        </table>
        <p class="produto-removido d-none compra-finalizada" >Compra Finalizada</p>
        <a class="finish-order" href="javascript:void(0)">Finalizar Pedido</a>
    
        <div class="modal">
            <div class="payments">
                <a href="javascript:void(0)" onclick="finalizarPedido('default')"><img src="imagens/dafault-payment.png" alt="Método de pagamento Padrão"></a>
                <a href=""><img src="imagens/mercado-pago.png" alt="Mercado Pago"></a>
                <a href=""><img src="imagens/pagseguro.png" alt="Pagseguro"></a>
            </div>
        </div>

    </main>

    <footer></footer>

    <script>
        var userId=<?=$_SESSION['userInfo']['id']?>
    </script>
    <script src="js/script.js"></script>
</body>
</html>
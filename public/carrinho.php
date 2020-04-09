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
                    $produtos=$store->selectItemsFromCart($_SESSION['userInfo']['id']);
                    // foreach ($produtos as $produto){
                    //     echo "<pre>";
                    //     var_dump($produto);
                    //     echo "</pre>";
                    // }
                    foreach ($produtos as $produto):
                ?>
                    <tr>
                    <td><img src="uploads/<?=json_decode($produto['imagens'])[0]?>" alt="teste"><p><?=$produto['nome']?></p></td>
                    <td><input type="number" max="<?=$produto['estoque']?>" min="1" value="<?=$produto['quantidade'];?>"></td>
                    <td>R$ <?=$produto['preco']?></td>
                    <td>R$ <?=$produto['preco']*$produto['quantidade']?></td>
                    <td><a class="remove-btn" href="#">Remover</a></td>
                </tr>

                <?php
                    endforeach;
                ?>

                <!-- Não alterar essa linha -->
                <tr><td colspan="4">Total: R$ 10.000,00</td></tr>
            </tbody>
        </table>
        <a class="finish-order" href="#">Finalizar Pedido</a>

    </main>

    <footer></footer>
</body>
</html>
<?php
    require ('class/autoload.php');
    session_start();
    $store = new Store();
    $produto=$store->showProduct($_GET['product_id']);
    $imagens = json_decode($produto->imagens);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?=$produto->nome?></title>
        <base href="../">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <?php require 'header.php'; ?>

        <main>
            <div id="produto">

                <h1><?= $produto->nome ?></h1>
                <img src="uploads/<?=$imagens[0]?>" height="400" alt="Imagem <?=$produto->nome?>">
                <p><?=$produto->descricao?></p>
                
                <center>
                    <h2 class="clearfix">Preço:<?=$produto->preco?></h2>
                    <h3 class="clearfix">Estoque: <?=$produto->estoque?></h3>
                    <div id="counter">
                        <label for="quantidade">Quantidade:</label>
                        <input type="number" id="quantidade" min="1">
                        <button>Comprar</button>
                    </div>
                </center>

                <div id="galeria" class="clearfix">
                    <?php foreach ($imagens as $imagem): ?>
                        <img src="uploads/<?=$imagem?>" onclick="updateProductImage(this)" alt="">
                    <?php endforeach;?>
                </div>

            </div>

            <div id="let-comment" class="clearfix">
                <textarea name="" id="" cols="30" rows="5" disabled="true"></textarea>
                <button disabled="true">Comentar</button>
            </div>

            <div id="comentarios">
                <div class="comment-section">
                    <p class="comment">
                    <img src="https://cdn2.iconfinder.com/data/icons/font-awesome/1792/user-512.png" alt="">
                        user_x says:
                        <span class="comment-content">Teste Um dois Três</span>
                    </p>
                </div>

                <div class="comment-section clearfix">
                    <p class="comment">
                    <img src="https://cdn2.iconfinder.com/data/icons/font-awesome/1792/user-512.png" alt="">
                        user_x says:
                        <span class="comment-content">Teste 3,2,1</span>
                    </p>
                   
                </div>
                <div class="comment-section clearfix">
                    <p class="comment">
                    <img src="https://cdn2.iconfinder.com/data/icons/font-awesome/1792/user-512.png" alt="">
                        user_x says:
                        <span class="comment-content">Teste</span>
                    </p>
                   
                </div>
                <!-- <div class="comment-section">
                    
                    <p class="comment">
                        <img src="" alt="">
                    </p>
                </div> -->
            </div>
        </main>

        <footer>
            
        </footer>
        <script src="js/script.js"></script>
    </body>
</html>
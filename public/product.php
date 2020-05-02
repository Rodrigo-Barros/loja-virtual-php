<?php
require ('class/autoload.php');
    session_start();
    $store = new Store();
    $produto=$store->showProduct($_GET['product_id']);
    $user = new User();
    $userCanComment = 'disabled';
    if(isset($_SESSION['userInfo']) && $_SESSION['userInfo']['userType']=='user'){
      if($user->checkIfUserOrderedBeforeComment($_SESSION['userInfo']['id'], $produto->id))
        $userCanComment = '';
    }
    $showStars = ($userCanComment == 'disabled') ? 'style=display:none' : '';
    $imagens = json_decode($produto->imagens);
    $comments = Database::sql("SELECT comentario, Usuarios.nome from Avaliacoes
	       INNER JOIN Usuarios
	       ON Usuarios.id = Avaliacoes.idUsuario
      WHERE Avaliacoes.idProduto = :idProduto");
    $comments->bindParam(':idProduto',$produto->id);
    $comments->execute();
    $nota = Database::sql("SELECT AVG(nota) as nota FROM Avaliacoes WHERE idProduto = :idProduto");
    $nota->bindParam(':idProduto',$produto->id);
    $nota->execute();
    $nota = $nota->fetch(PDO::FETCH_OBJ);
?>

<html>
    <?php require 'head.php'; ?>
    <body>
        <?php require 'header.php'; ?>

        <main>
            <div id="produto">

                <h1><?= $produto->nome ?></h1>
                <h4 style="display:block; width:90%;margin-left:15px">nota: <?=$nota->nota?></h4>
                <img src="uploads/<?=$imagens[0]?>" height="400" alt="Imagem <?=$produto->nome?>">
                <p><?=$produto->descricao?></p>
                <center>

                    <h2 class="clearfix">Preço:<?=$produto->preco?></h2>
                    <h3 class="clearfix">Estoque: <?=$produto->estoque?></h3>
                    <div id="counter">
                        <label for="quantidade">Quantidade:</label>
                        <input type="number" id="quantidade" min="1" max="<?=$produto->estoque?>" value="0" name="quantidade">
                        <button onclick="addToCart(<?=$produto->id?>)" <?php echo (isset($_SESSION['userInfo'])==False || $_SESSION['userInfo']['userType']=='admin') ? "disabled" : "" ; ?>>Comprar</button>

                    </div>

                    <?php
                            if (isset($_SESSION['userInfo'])==False):
                        ?>
                            <h4 class="clearfix" style="text-align:center">é necessário se logar antes de poder comprar algum produto</h4>
                        <?php
                            endif;
                        ?>

                    <?php
                        if($_GET['quantidade']!==''){
                            $store->addToCart($produto,$_SESSION['userInfo']['id']);
                    ?>
                        <p id="success-add-to-cart" class="clearfix">Produto Adicionado ao Carrinho com sucesso</p>
                    <?php
                            // header('Location:public/Carrinho');
                        }
                    ?>
                </center>

                <div id="galeria" class="clearfix">
                    <?php foreach ($imagens as $imagem): ?>
                        <img src="uploads/<?=$imagem?>" onclick="updateProductImage(this)" alt="">
                    <?php endforeach;?>
                </div>

            </div>

            <form id="let-comment" class="clearfix">
                <textarea name="comment" id="" cols="30" rows="5" required <?= $userCanComment?>></textarea>
                <button <?= $userCanComment?> >
                  Comentar
                </button>
                <input <?=$userCanComment?> type="range" name="note" min="1" max="5" step="0.5" value="3">
                <p id="note-updater" <?=$showStars?> >3 <span>estrelas</span></p>
                <input type="hidden" name="userId" value="<?=$_SESSION['userInfo']['id']?>">
                <input type="hidden" name="productId" value="<?=$produto->id?>">
            </form>

            <div id="comentarios">
                <?php foreach ($comments as $comment): ?>
                  <div class="comment-section">
                      <p class="comment">
                      <img src="https://cdn2.iconfinder.com/data/icons/font-awesome/1792/user-512.png" alt="">
                          <?=$comment['nome']?> disse:
                          <span class="comment-content"><?=$comment['comentario']?></span>
                      </p>
                  </div>
                <?php endforeach; ?>
                <!--

                <div class="comment-section">
                    <p class="comment">
                    <img src="https://cdn2.iconfinder.com/data/icons/font-awesome/1792/user-512.png" alt="">
                        user_x says:
                        <span class="comment-content">Teste Um dois Três</span>
                    </p>
                </div>

              -->

            </div>
        </main>

        <footer>

        </footer>
        <script src="js/script.js"></script>
        <script type="text/javascript">
          const input = document.querySelector('input[type="range"]');
          input.onchange = function(){
            document.querySelector('#note-updater').textContent = (input.value != 1) ? input.value + " estrelas" : input.value + " estrela";
          }


        </script>
    </body>
</html>

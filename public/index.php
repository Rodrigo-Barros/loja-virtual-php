<?php 
require('class/autoload.php');
$store = new Store();

session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<?php require 'head.php'; ?>
<body>
    <?php require 'header.php' ?>
    <main>
        
        <!-- conteúdo do site -->
         <h1>Destaques:</h1>
         <div class="banner">
         <?php
            $store->showRandomProducts();
        ?>
            <!-- <div class="destaque">
                <a href="">
                    <img src="uploads/5e89d7b33daea" alt="">
                    <p>Clique para saber mais</p>
                </a>
            </div> -->

        </div> 
				
        <div class="vitrine">
        <?php
            
            $store->showProducts();
        ?>
            <!-- <figure class="produto">
                <a href="produto/id">
                    <h3>Título</h3>
                    <img src="uploads/5e89d7b33daea" alt="imagem do produto">
                    <figcaption>descrição</figcaption>
                    <figcaption>mais detalhes</figcaption>
                </a>
            </figure>

            <figure class="produto">
                <a href="#">
                    <h3>Título</h3>
                    <img src="uploads/5e89d7b33daea" alt="imagem do produto">
                    <figcaption>descrição</figcaption>
                    <figcaption>mais detalhes</figcaption>
                </a>
            </figure>

            <figure class="produto">
                <a href="#">
                    <h3>Título</h3>
                    <img src="uploads/5e89d7b33daea" alt="imagem do produto">
                    <figcaption>descrição</figcaption>
                    <figcaption>mais detalhes</figcaption>
                </a>
            </figure>

            <figure class="produto">
                <a href="#">
                    <h3>Título</h3>
                    <img src="" alt="imagem do produto">
                    <figcaption>descrição</figcaption>
                    <figcaption>mais detalhes</figcaption>
                </a>
            </figure>

            <figure class="produto">
                <a href="#">
                    <h3>Título</h3>
                    <img src="" alt="imagem do produto">
                    <figcaption>descrição</figcaption>
                    <figcaption>mais detalhes</figcaption>
                </a>
            </figure>

            <figure class="produto">
                <a href="#">
                    <h3>Título</h3>
                    <img src="" alt="imagem do produto">
                    <figcaption>descrição</figcaption>
                    <figcaption>mais detalhes</figcaption>
                </a>
            </figure> -->
            
            
        </div>
    </main>
    <footer>
        <!-- rodapé informações e outras coisas -->
    </footer>
</body>
</html>

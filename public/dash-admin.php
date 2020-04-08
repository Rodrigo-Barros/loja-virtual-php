<?php 

session_start();
require("class/autoload.php");
// var_dump($_SESSION);

if ( isset($_SESSION['userInfo'])==False || $_SESSION['userInfo']['userType']=='user' )
{
    header('Location: dashboard');
    exit();
}

$admin = new Admin();
$cats=Database::sql("SELECT * FROM Categorias"); 
$cats->execute();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de controle - Administrador</title>
    <link rel="stylesheet" href="css/vars.css">
    <link rel="stylesheet" href="css/dash-admin.css">
</head>
<body>
    <nav id="top-menu">
        <ul id="top-menu-left">
            <li><a href="/ecommerce" title="Leva a página incial">Ecommerce</a></li>
            <li><a href="#produtos" title="Gerenciamento de produtos">Produtos</a></li>
            <li><a href="#pedidos" title="Acompanhamento do adamento dos Produtos">Pedidos</a></li>
            <li>
                <a href="#usuarios" title="Gerenciamento de Administradores">
                    Usuários
                </a>
            </li>
        </ul>

        <ul id="top-menu-right">
            <li><a href="#">configurações</a></li>
            <ul>
                <li>
                    <a href="#editar" title="Permite modificar dados da sua conta como senha entre outras opções">editar</a>
                </li>
                <li><a href="logout.php" title="Encerra sua sessão">sair</a></li>
            </ul>    
        </ul>
    </nav>

    <div id="paginas">
        <div id="produtos">
            <h1>Categorias:</h1>
            <form action="" method="post">
                <label for="cat_nome">Categoria:</label>
                <input type="text" id="cat_nome" name="cat_nome" placeholder="Digite o nome da Categoria">
                <input type="submit" value="Cadastrar">
            </form>

            <?php
                if ( isset($_POST['cat_nome']) ){
                    $admin->createCategory($_POST['cat_nome']);
                    unset($_POST['cat_nome']);
                }
            ?>

            <h1>Produtos</h1>
            <form action="" method="post" enctype="multipart/form-data">
                <label for="prod_cat_id">Categoria:</label>
                <select name="prod_cat_id" id="prod_cat_id">
                    <option value="">Escolha um Categoria</option>
                    <?php  ?>
                    <?php foreach( $cats as $row): ?>
                        <option value="<?=$row['id'];?>"><?=$row['nome'];?></option>
                    <?php endforeach; ?>
                </select>
                
                <label for="prod_nome">Produto:</label>
                <input type="text" id="prod_nome" name="prod_nome"  placeholder="Nome do Produto">

                <label for="prod_preco">Preço:</label>
                <input type="text" id="prod_preco" name="prod_preco" placeholder="digite o preço do produto">

                <label for="prod_estoque" title="Quantidade de itens no estoque">Estoque:</label>
                <input type="number" id="prod_estoque" name="prod_estoque"  >

                <label for="prod_desc">Descrição:</label>
                <textarea name="prod_desc" id="prod_desc" cols="30" rows="10" placeholder="Descrição"></textarea>

                <label for="prod_img">Imagens:</label>
                <input type="file" id="prod_img" name="prod_img[]" multiple="true" >

                <input type="submit" value="Cadastrar">
            </form>

            <?php
                if (isset($_POST['prod_cat_id']) && isset($_POST['prod_nome'])
                 && isset($_POST['prod_preco']) && isset($_POST['prod_estoque']) && isset($_POST['prod_desc'])){
                    $admin->createProduct(
                        $_POST['prod_cat_id'],
                        $_POST['prod_nome'],
                        $_POST['prod_preco'],
                        $_POST['prod_estoque'],
                        $_POST['prod_desc']
                    );
                    unset($_POST['prod_cat_id']);
                    unset($_POST['prod_nome']);
                    unset($_POST['prod_preco']);
                    unset($_POST['prod_estoque']);
                    unset($_POST['prod_desc']);
                }
            ?>

        </div>
        <div id="pedidos">
            <h1>Pedidos</h1>
        </div>
        <div id="usuarios">
            <h1>Usuários</h1>
            <table>
            <thead>
                <th>Usuario</th>
                <th>Email</th>
            </thead>
            <tbody>
                
                <?php foreach (Database::sqlFor("SELECT * FROM Usuarios") as $row) :?>
                    <tr>
                        <td><?= $row['nome']?></td>
                        <td><?= $row['email']?></td>
                    </tr>    
                <?php endforeach; ?>
            </tbody>    
            </table>
        </div>
        <div id="editar">
            <h1>Editar</h1>
        </div>
    </div>
    <footer></footer>
</body>
</html>
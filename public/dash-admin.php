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
    <title>DashBoard - Admin</title>
    <link rel="stylesheet" href="css/dash-admin.css">
</head>
<body>
    <div class="d-grid">
        <nav>
            <h1>Dashboard</h1>
            <ul class="menu">
                <li><a href="public" title="Vai para a loja">Ecommerce</a></li>
                <li><a href="#" onclick="new Categorias('categorias',this)">Categorias</a></li>
                <li><a href="#" onclick="new Produtos('produtos', this)">Produtos</a></li>
                <li><a href="#" onclick="new Administradores('administradores', this)">Administradores</a></li>
                <li><a href="#" onclick="new Pedidos('pedidos',this)">Pedidos</a></li>
                <li><a href="public/logout.php">Sair</a></li>
            </ul>
        </nav>
        <aside>
            <div class="paginas">
                <div class="categorias pagina" data-name="categorias">
                    <h1>Categorias</h1>
                    <table>
                        <thead>
                            <th>id</th>
                            <th>categoria</th>
                            <th>ações</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Móveis</td>
                                <td>
                                    <button class="button button__primary" onclick="editarCategoria(1,'Nome')">editar</button>
                                    <button class="button button__danger">excluir</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button class="button button__create scale-up">Criar Nova Categoria</button>
                </div>

                <div class="produtos pagina" data-name="produtos">
                    <h1>Produtos</h1>
                    <table>
                        <thead>
                            <th>id</th>
                            <th>Produto</th>
                            <th>Categoria</th>
                            <th>Preço</th>
                            <th>quantidade</th>
                            <th>Ações</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Mesa</td>
                                <td>Móveis</td>
                                <td>R$ 300,00</td>
                                <td>100</td>
                                <td>
                                    <button class="button button__primary" onclick="editarProduto(1,'Nome')">editar</button>
                                    <button class="button button__danger" onclick="deletarProduto(1)" >excluir</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button class="button button__create scale-up">Criar Produto</button>
                </div>

                <div class="administradores pagina" data-name="administradores">
                    <h1>Administradores</h1>
                    <table>
                        <thead>
                            <th>id</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Ações</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Admin</td>
                                <td>admin@email.com</td>
                                <td>
                                    <button class="button button__primary" onclick="editarProduto(1,'Nome')">editar</button>
                                    <button class="button button__danger" onclick="deletarProduto(1)">excluir</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <button class="button button__create scale-up">Criar Novo Admin</button>
                </div>

                <div class="pedidos pagina" data-name="pedidos">
                    <h1>Pedidos</h1>
                    <table>
                        <thead>
                            <th>id</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Finalizado</td>
                                <td><a href="#" onclick="//getPedidoInfo('pedidoIdo')">mais detalhes</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
              <div class="detalhes-pedido pagina" data-name="detalhes">
                  <h1>Detalhes do Pedido: 27</h1>
                      <table>
                          <thead>
                              <th>Produto</th>
                              <th>Preço</th>
                              <th>Quantidade</th>
                              <th>SubTotal</th>
                          </thead>
                          <tbody>
                              <tr>
                                  <td>Xioami mi max</td>
                                  <td>R$ 1000,00</td>
                                  <td>2</td>
                                  <td><a href="#" onclick="//getPedidoInfo('pedidoIdo')">R$ 1000,00</a></td>
                              </tr>

                              <tr>
                                  <td colspan="3">Total:</td>
                                  <td>R$ 2000,00</td>
                              </tr>
                          </tbody>
                      </table>

                  <div class="display-pedido-info">
                      
                  </div>

              </div>
            </div>
            
            <div class="forms">
              <!-- formulários de criação -->
              <form id="criar-categoria" onsubmit="return Categorias.create()">
                <h1>Criar categoria</h1>
                <label for="categoria-nome">Categoria:</label>
                <input type="text" name="create-category" id="categoria-nome"/>
                <input type="submit" value="Cadastrar Categoria" />
              </form>
              <form id="criar-produto" enctype="multipart/form-data" onsubmit="return Produtos.create()">
                <h1>Criar Produto</h1>
                <input type="hidden" name="create-product" value="true">
                <label for="produto">Produto:</label>
                <input type="text" name="produto" id="produto">
                <label for="produto-categoria">Categoria:</label>
                <select name="produto-categoria" id="produto-categoria" onclick="Produtos.updateSelect()">
                  <option value="default">Escolha Uma Opção</option>
                </select>
                <label for="produto-preco">preco</label>
                <input type="text" name="produto-preco" id="produto-preco">
                <label for="produto-quantidade">Quantidade:</label>
                <input type="number" name="produto-quantidade" id="produto-quantidade" min="1">
                <label for="produto-fotos">Fotos:</label>
                <input type="file" name="produto-fotos[]" id="produto-fotos" multiple>
                <input type="text" name="produto-descricao" id="">
                <input type="submit" value="Cadastrar Produtos">
              </form>

              <form action="" id="criar-administrador" onsubmit="return Administradores.create()">
                <h1>Criar Administrador</h1>
                <input type="hidden" name="create-admin" value="true">
                <label for="administrador-nome">Nome:</label>
                <input type="text" name="administrador-nome" id="administrador-nome" required>
                <label for="administrador-email">Email:</label>
                <input type="email" name="administrador-email" id="administrador-email" required>
                <label for="administrador-senha">Senha:</label>
                <input type="password" name="administrador-senha" id="administrador-senha" required>
                <input type="submit" value="Cadastrar Administrador">
              </form>
            </div>
        </aside>
			<script src="js/admin.js"></script>
    </div>
</body>
</html>

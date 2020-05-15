<?php

require 'autoload.php';

class Api
{
    private $request;
    private $args;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];
        $this->args = $_REQUEST;
				header('content-type: application/json');
    }

    public function requestHandler()
    {
        $this->debug(false);

        switch ($this->request) {
            case 'GET':
                $this->getFunctionsHandler(new Method);
                break;

            case 'POST':
              $this->postFunctionsHandler(new Method);
              break;

            default:
                # code...
                echo "Método não implementado na API";
                break;
        }
    }

    // implem
    public function getFunctionsHandler(Method $method)
    {

      forEach ($this->args as $arg=>$key){
        switch($arg){
              case 'finalizar_pedido':
                  $method->finishOrder($_GET['finalizar_pedido'], $_GET['userId'], $_GET['productInfo']);
                  return;
              case 'categorias':
                  $method->getCategorias();
                  return;
              case 'produtos':
                  $method->getProdutos();
                return;
              case 'administradores':
                $method->getAdmins();
                return;
              case 'pedidos':
                $method->getPedidos();
                return;
              case 'pedido':
                $method->getPedido($key);
                return;

              default:
                  echo json_encode(["Detalhes do Erro"=>'rota não implementada']);
                return;
          }
      }
    }

    public function postFunctionsHandler(Method $method)
    {
      if(count($this->args) == 0){
        echo 'rota não implementada';
        return;
      }
      forEach ($this->args as $arg=>$value){
          switch($arg){
              case 'post-comment':
                  $method->letComment($_POST['userId'], $_POST['productId'], $_POST['comment'], $_POST['note']);
                  return;
              case 'delete-product':
                $method->deleteProduct(intval($value));
                return;
              case 'delete-categorie':
                $method->deleteCategorie(intval($value));
                return;
              case 'delete-admin':
                $method->deleteAdmin(intval($value));
                return;
              case 'create-category':
                $method->createCategory($value);
                return;
              case 'create-product':
                $method->createProduct($value);
                return;
              case 'create-admin':
                $admin = new Admin();
                $admin->createAdmin($_POST['administrador-nome'], $_POST['administrador-email'], $_POST['administrador-senha']);
                return;
              case 'edit-category':
                $admin = new Admin();
                $admin->editCategory($_POST['categoria-id'], $_POST['editar-categoria-nome']);
                return;
              case 'edit-product':
                $admin = new Admin();
                $admin->editProduct($_POST['product-id'],
                  $_POST['editar-produto-categoria'],
                  $_POST['editar-produto-nome'],
                  $_POST['editar-produto-preco'],
                  $_POST['editar-produto-quantidade'],
                  $_POST['editar-produto-descricao']
                );
                return;
              case 'edit-admin':
                $method->editAdmin($_POST['id'],$_POST['nome'],$_POST['email'],$_POST['senha']);
                return;
              default:
                  echo 'rota não implementada';
                  return;
          }

      }
    }

    public function debug($enabled=true)
    {
        if($enabled):
    ?>
        <p>Tipo do request:<?=$this->request?></p>
        <p>Argumentos do request:</p>
        <pre><?=print_r($this->args)?></pre>
    <?php
        endif;
    }

}

class Method
{
    public function finishOrder($type, $userId, $productInfo)
    {

        $produtos = json_decode($productInfo,$assoc=True);


        // Converter o json para PHP
        $produtos = json_decode($productInfo,$assoc=True);

        // Inserir Pedido na tabela correspondente
        $idPagamento='default';
        if($type == 'mercadoPago'){
          $accessToken = 'TEST-8864676676772087-041722-7ef8cc5db28f3f3fce77e4b05395c34e-194214343';
          $publicKey = 'TEST-4c9ca35f-f253-41bb-8b63-e056cea62dcd';
          $MPResponse = $this->curlRequest("https://api.mercadopago.com/v1/payments?access_token=$accessToken", "POST" , [
            "transaction_amount" => floatval($_GET['total']),
            "token" => $_GET['token'],
            "description" => "Teste de pagamento pela API",
            "installments" => 1, // parcelas do pagamento
            "payment_method_id" => $_GET['payment_method_id'],
            "payer" => [
              "email" => 'teste@email.com'
            ]
          ]);
          $response =  json_decode($MPResponse);
          // echo $response;
          $idPagamento = $response->id;
        }
        $meioDePagamento = ($type == 'mercadoPago') ? 'mercadoPago' : 'default';
        $Pedidos = Database::sql("INSERT INTO Pedidos (usuario_id,status_pedido, meio_pagamento,idPagamento) VALUES (:userId,1,:meioPagamento,:idPagamento)");
        $Pedidos->bindParam(':userId', $userId);
        $Pedidos->bindParam(':meioPagamento', $meioDePagamento);
        $Pedidos->bindParam(':idPagamento',$idPagamento);
        $Pedidos->execute();

        // Insere os pedidos na Tabelas Item Pedidos e Atualiza o estoque
        $pedidoId=Database::sql("SELECT id FROM Ecommerce.Pedidos ORDER BY id desc LIMIT 1");
        $pedidoId->execute();
        $pedidoId=$pedidoId->fetchObject();
        foreach ($produtos as $produto){
            $tableProdutos = Database::sql("INSERT INTO itemPedido (idPedido, idProduto, quantidade, preco)
                VALUES (:idPedido, :idProduto, :quantidade, :preco)");
            $tableProdutos->bindParam(':idPedido', $pedidoId->id);
            $tableProdutos->bindParam(':idProduto', $produto['produto_id']);
            $tableProdutos->bindParam(':quantidade', $produto['quantidade']);
            $tableProdutos->bindParam(':preco', $produto['preco_unitario']);
            if ($tableProdutos->execute()){
                echo "Pedidos adicionados na tabela itemPedido \n";
            }else{
                var_dump( $tableProdutos->errorInfo() );
            }

            $update = Database::sql("UPDATE Produtos SET estoque=estoque - :quantidade WHERE id = :idProduto");
            $update->bindParam(":quantidade",$produto['quantidade']);
            $update->bindParam(":idProduto", $produto['produto_id']);
            $update->execute();
        }

        // Atualizar o carrinho
        $carrinho = Database::sql("DELETE FROM Carrinho WHERE usuario_id=:userId");
        $carrinho->bindParam(':userId',$userId);
        $carrinho->execute();



    }

		public function getCategorias(){
			$cats = Database::sql("SELECT * FROM Categorias");
			$cats->execute();
			echo json_encode($cats->fetchAll(PDO::FETCH_OBJ));
		}

		public function getProdutos()
		{
      $produtos = Database::sql("SELECT Produtos.nome,Produtos.preco, Produtos.estoque,Produtos.id,
        Categorias.nome as categoria, Produtos.idCategoria as idCategoria from Produtos
				INNER JOIN Categorias 
				ON Produtos.idCategoria = Categorias.id");
			$produtos->execute();
			echo json_encode($produtos->fetchAll(PDO::FETCH_OBJ));
		}

		public function getAdmins()
		{
			$admins = Database::sql("SELECT id,nome,email FROM Administradores");
			$admins->execute();
			echo json_encode($admins->fetchAll(PDO::FETCH_OBJ));
		}

		public function getPedidos()
		{
			$pedidos=Database::sql('SELECT id,status_pedido FROM Pedidos');
			$pedidos->execute();
			echo json_encode( $pedidos->fetchAll(PDO::FETCH_OBJ) );
		}

		public function getPedido($pedidoId)
		{
			$pedido = Database::sql('
				SELECT 
					Pedidos.id as pedido_id, Usuarios.nome, Usuarios.email, Usuarios.estado,
					Usuarios.cidade, Usuarios.bairro, Usuarios.endereco, Usuarios.cep,
					Pedidos.meio_pagamento, itemPedido.preco, Produtos.nome as produto, 
					itemPedido.idPedido, itemPedido.quantidade, Pedidos.idPagamento
				FROM itemPedido
					INNER JOIN Produtos
					ON Produtos.id = itemPedido.idProduto
					INNER JOIN Pedidos
					ON itemPedido.idPedido = Pedidos.id
					INNER JOIN Usuarios
					ON Usuarios.id = Pedidos.usuario_id
				WHERE Pedidos.id = :pedidoId');
			$pedido->bindParam("pedidoId", $pedidoId);
			$pedido->execute();
			echo json_encode( $pedido->fetchAll(PDO::FETCH_OBJ) );
		}
		
    public function deleteProduct($productId)
    {
      $sql=Database::sql("DELETE FROM Produtos WHERE id = :productId");
      $sql->bindParam('productId', $productId);
      if($sql->execute()){
        echo json_encode(["response"=>"conteudo deletado com sucesso"]);
        http_response_code(200);
      }else{
        echo json_encode([
          "response"=>"não foi possível deletar o produto solicitado", 
          "detalhes do erro" => $sql->errorInfo()
        ]);
        http_response_code(600);
      }
    }

    public function deleteCategorie(int $categoriaId)
    {
      $sql = Database::sql("DELETE FROM Categorias WHERE id = :categoriaId");
      $sql->bindParam('categoriaId',$categoriaId);
      if($sql->execute()){
        echo "A categoria foi excuída com sucesso";
        http_response_code(200);
      }else{
        echo json_encode([
          "response"=>"Não foi Possível delete a categoria informada",
          "detalhes do erro"=>$sql->errorInfo()
        ]);
        http_response_code(600);
      }
    }

    public function createCategory($category)
    {
      $sql = Database::sql("INSERT INTO Categorias (nome) VALUES (:categoriaId)");
      $sql->bindParam('categoriaId',$category);
      $sql2 = Database::sql("SELECT * FROM Categorias ORDER BY id DESC LIMIT 1");

      if( $sql->execute() ){
        $sql2->execute();
        echo json_encode([
          "id"=>$sql2->fetch(PDO::FETCH_OBJ)->id,
          "nome"=>$category
        ]);
      }else{
        echo json_encode([
          "response"=>"Não foi possível cadastrar a sua categoria",
          "detalhes do erro"=> $sql->errorInfo()
        ]);
      }

    }

    public function createProduct(){
      $admin = new Admin();
      $statusInsertedOnDB=$admin->createProduct($_POST['produto-categoria'],
        $_POST['produto'],
        $_POST['produto-preco'],
        $_POST['produto-quantidade'],
        $_POST['produto-descricao'],
        'produto-fotos'
      );
      if ( $statusInsertedOnDB->dbSucessfull ){
        echo json_encode([
          $statusInsertedOnDB->produtoData
        ]);
        http_response_code(200);
      }else{
        echo json_encode([
          "response"=>"Não foi possível cadastrar o produto no banco de dados",
          "detalhes do erro"=>$statusInsertedOnDB->errorInfo
        ]);
        http_response_code(600);
      }
    }

    public function deleteAdmin(int $adminId)
    {
      $sql = Database::sql('DELETE FROM Administradores WHERE id = :adminId');
      $sql->bindParam('adminId', $adminId);
      if($sql->execute()){
        echo "Administrador excluido com sucesso";
        http_response_code(200);
      }else{
        echo json_encode([
          "response"=>"Não foi possível excluir o administrador espcificado",
          "detalhes do erro"=>$sql->errorInfo()
        ]);
        http_response_code(600);
      }
    }

    public function editAdmin($id, $nome, $email, $senha){
      $senha = ($senha == '') ? 'none'  : password_hash($senha,PASSWORD_BCRYPT);
      $query = Database::sql("UPDATE Administradores SET email=:email, nome=:nome, senha=IF(:senha = 'none',senha ,:senha) WHERE id =:id");
      $query->bindParam(':nome',$nome);
      $query->bindParam(':email',$email);
      $query->bindParam(':senha',$senha);
      $query->bindParam(':id', $id);
      if ($query->execute()) {
        echo json_encode([
          "nome"=>$nome,
          "email"=>$email
        ]);
        http_response_code(200);
      }else{
        echo json_encode([
          "response"=>"Não foi possível atualizar o administrador",
          "detalhes do erro"=> $query->errorInfo()
        ]);
        http_response_code(500);
      }
      
    }

    public function curlRequest(string $url, string $method, array $params){
      $cr = curl_init();
      curl_setopt_array($cr, [
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => json_encode($params),
        CURLOPT_RETURNTRANSFER => TRUE
      ]);

      $response = curl_exec($cr);

      if (!$response) echo "Erro: " . curl_error($cr);
      curl_close($cr);
      return $response;
    }

    public function letComment($userId,$productId,$comment,$note)
    {
      $query= Database::sql("INSERT INTO Avaliacoes (idUsuario, idProduto, comentario, nota)
        VALUES (:userId, :productId, :comment, :note)");
      $query->bindParam(':userId',$userId);
      $query->bindParam(':productId',$productId);
      $query->bindParam(':comment',$comment);
      $query->bindParam(':note',$note);
      if ($query->execute()==False){
        $query->errorInfo();
      }
      else{
        session_start();
        $response = json_encode([
          "userName" => $_SESSION['userInfo']['nome'],
          "comment" => $comment
        ]);
        echo $response;
      }
    }

}


$api = new Api();
$api->requestHandler();

?>

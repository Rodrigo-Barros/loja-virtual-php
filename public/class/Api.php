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

                default:
                    echo 'rota não implementada';
                    return;
            }
        }
    }

    public function postFunctionsHandler(Method $method)
    {
      forEach ($this->args as $arg=>$key){
          switch($arg){
              case 'post-comment':
                  $method->letComment($_POST['userId'], $_POST['productId'], $_POST['comment'], $_POST['note']);
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
        $Pedidos = Database::sql("INSERT INTO Pedidos (usuario_id,status_pedido) VALUES (:userId,1)");
        $Pedidos->bindParam(':userId', $userId);
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

        if($type == 'mercadoPago'){
          $accessToken = 'TEST-8864676676772087-041722-7ef8cc5db28f3f3fce77e4b05395c34e-194214343';
          $publicKey = 'TEST-4c9ca35f-f253-41bb-8b63-e056cea62dcd';
          var_dump($this->curlRequest("https://api.mercadopago.com/v1/payments?access_token=$accessToken", "POST" , [
            "transaction_amount" => floatval($_GET['total']),
            "token" => $_GET['token'],
            "description" => "Teste de pagamento pela API",
            "installments" => 1, // parcelas do pagamento
            "payment_method_id" => $_GET['payment_method_id'],
            "payer" => [
              "email" => 'test@test.com'
            ]
          ]));
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

<?php

require 'autoload.php';

class Api
{
    private $request;
    private $args;

    public function __construct()
    {
        $this->request = $_SERVER['REQUEST_METHOD'];
        $this->args = $_GET;
    }

    public function requestHandler()
    {
        $this->debug(false);

        switch ($this->request) {
            case 'GET':
                $this->getFunctionsHandler(new Method);
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
                    break;


                default:
                    continue;
                    break;
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
                echo "Pedidos adicionados na tabela itemPedido";
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

}


$api = new Api();
$api->requestHandler();

?>
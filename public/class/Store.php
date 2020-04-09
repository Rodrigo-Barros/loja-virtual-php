<?php

require('autoload.php');

class Store
{
    //Get the products from Database and show them for users
    public function showProducts() {
        foreach (Database::sqlFor("SELECT * FROM Produtos WHERE estoque > 0") as $row):
            $row['imagens'] = json_decode($row['imagens']);
    ?>
        <figure class="produto">
            <a href="produto/<?=$row['id']?>">
                <h3><?=$row['nome']?></h3>
                <img src="uploads/<?=$row['imagens'][0];?>" alt="imagem do produto <?=$row['nome']?>">
                <figcaption><?= $row['descricao']?></figcaption>
                <p>R$ <?=$row['preco']?></p>
                <figcaption>mais detalhes</figcaption>
            </a>
        </figure>
    <?php
        endforeach;
    }

    public function showRandomProducts() {
        foreach (Database::sqlFor("SELECT * FROM Produtos WHERE estoque > 0 ORDER BY RAND() LIMIT 3 ") as $row):
            $row['imagens']= json_decode($row['imagens']);
        ?>
            <div class="destaque">
                <a href="produto/<?=$row['id']?>">
                    <img src="uploads/<?=$row['imagens'][0]?>" alt="">
                    <p>Clique para saber mais</p>
                </a>
            </div>
        <?php

        endforeach;
    }

    // View Product with detailed info about it
    public function showProduct($id)
    {
        $query=Database::sql("SELECT * FROM Produtos WHERE ID= :id");
        $query->bindParam(":id",$id);
        if ($query->execute())
        {
            return $query->fetchObject();
        }
        return False;
    }

    public function addToCart($produto,$userId){
        $quantidade = $_GET['quantidade'];
        $query = Database::sql("INSERT INTO Carrinho (usuario_id, produto_id, quantidade) VALUES(:userId, :produtoId, :quantidade)");
        $query->bindParam(":userId",$userId);
        $query->bindParam(":produtoId",$produto->id);
        $query->bindParam(":quantidade",$quantidade);
        $query->execute();
        var_dump($query->errorInfo());
    }

    public function selectItemsFromCart($userId) : object{
        $query = Database::sql("SELECT Produtos.nome, Produtos.preco, Produtos.estoque, Produtos.imagens,
            Carrinho.quantidade
        FROM Carrinho
	        INNER JOIN Produtos
            ON Carrinho.produto_id = Produtos.id
        WHERE Carrinho.usuario_id = :userId");
        $query->bindParam(":userId",$userId);
        $query->execute();
        return $query;
    }

    /**
     * Select Payment Type to finish Order
     * 
     * @param paymentType string
     * 
     * Receive a payment type could be
     * default -> generates a order without intermediares
     * pagseguro | mercadoPago -> uses a Api to finish the payment
     * 
     * @return bool
     * 
     * return a boolean with the status of operation
     * return always true to default payment type.
     */
    public function payment(string $paymentType="default"){

    }

    //For Rating system
    public function setProductNote($user_id,$product_id,$note){

    }

    public function letComment($user_id,$product_id,$comment){

    }

    public function checkIfUserOrderedBeforeComment($user_id,$product_id): bool {
        return True;
    }

}
?>
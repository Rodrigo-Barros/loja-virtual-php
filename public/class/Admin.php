<?php
require('autoload.php');
class Admin{

    public function login($email,$senha): bool{
        $query=Database::sql("SELECT email,senha FROM Administradores WHERE email = :email");
        $query->bindParam(":email",$email);
        $query->execute();

        $userInfo = $query->fetch();
        
        $cryptPass = $userInfo['senha'];
        if (password_verify($senha,$cryptPass))
        {
            unset($userInfo['senha'],$cryptPass);
            session_start();
            $_SESSION['userInfo'] = $userInfo;
            $_SESSION['userInfo']['userType']='admin';
            return True;
        }

        return False;
    }

    public function createAdmin($nome,$email,$senha)
    {
        $senha = password_hash($senha,PASSWORD_BCRYPT);
        $query=Database::sql("INSERT INTO Administradores (nome,email,senha) Values (:nome,:email,:senha)");
        $query->bindParam(":nome",$nome);
        $query->bindParam(":email",$email);
        $query->bindParam(":senha",$senha);
        $adminId = Database::sql("SELECT id FROM Administradores ORDER BY id DESC LIMIT 1");
        if( $query->execute() ){
          $adminId->execute();
          echo json_encode([
            "id"=>$adminId->fetch(PDO::FETCH_OBJ)->id,
            "nome" => $nome,
            "email" => $email
          ]);
          http_response_code(200);
        }else{
          echo json_encode([
            "response"=>"Houve uma erro ao tentar criar o administrador",
            "detalhes dos erro" => $query->errorInfo()
          ]);
          http_response_code(600);
        }
    }
    
    public function createCategory($nome)
    {
        $query=Database::sql("INSERT INTO Categorias (nome) Values(:nome)");
        $query->bindParam(":nome",$nome);
        $query->execute();
    }

    public function editCategory($id_cat,$nome)
    {
        $query=Database::sql("UPDATE Categorias SET nome=:nome WHERE id=:id_cat");
        $query->bindParam(":nome",$nome);
        $query->bindParam(":id_cat",$id_cat);
        $query->execute();
    }

    public function deleteCategory($id_cat)
    {
        $query=Database::sql("DELETE FROM Categorias WHERE id=:id_cat");
        $query->bindParam(":id_cat",$id_cat);
        $query->execute();
    }

    public function createProduct($cat_id,$produto,$preco,$estoque,$desc, $produto_img) : object
    {   
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/ecommerce/public/uploads/';
        $imagens = [];
        foreach ($_FILES[$produto_img]['tmp_name'] as $key=>$value){
            $uploadFile = $uploadDir . uniqid();
            array_push($imagens,basename($uploadFile));
            move_uploaded_file($value,$uploadFile);
        }
        $imagens = json_encode($imagens);

        $query=Database::sql("INSERT INTO Produtos (idCategoria,nome,preco,estoque,descricao,imagens) 
            Values (:cat_id,:produto,:preco,:estoque,:descricao,:img)");
        $query->bindParam(":cat_id",$cat_id);
        $query->bindParam(":produto",$produto);
        $query->bindParam(":preco",$preco);
        $query->bindParam(":estoque",$estoque);
        $query->bindParam(":descricao",$desc);
        $query->bindParam(":img",$imagens);
        $returnData = new stdClass();
        $returnData->dbSucessfull=$query->execute();
        $returnData->errorInfo=$query->errorInfo();

        $query2 = Database::sql("SELECT Produtos.id, Produtos.idCategoria, Produtos.nome,Categorias.nome as categoria, Produtos.preco,
        Produtos.estoque as quantidade
          FROM Produtos 
          INNER JOIN Categorias
          ON Produtos.idCategoria = Categorias.id
        ORDER BY id DESC LIMIT 1");
        $query2->execute();
        $productInsertedInfo = $query2->fetch(PDO::FETCH_OBJ);

        $returnData->produtoData = $productInsertedInfo;

        return $returnData;

    }

    public function editProduct($id_prod,$id_cat,$produto,$preco,$estoque,$desc){
        $query=Database::sql("UPDATE Produtos 
            SET idCategoria=:id_cat, 
                nome=:produto, 
                preco=:preco,
                estoque=:estoque, 
                descricao=:descricao
            WHERE id = :id_prod");
        
        $query->bindParam("id_cat",$id_cat);
        $query->bindParam(":produto", $produto);
        $query->bindParam(":preco",$preco);
        $query->bindParam(":estoque",$estoque);
        $query->bindParam(":descricao",$desc);
        $query->bindParam(":id_prod",$id_prod);
        $query_sts = $query->execute();
    }

    public function deleteProduct($id_prod)
    {
        $query=Database::sql("DELETE FROM Produtos WHERE id=:id_prod");
        $query->bindParam(":id_prod",$id_prod);
        $query->execute();
    }

}
?>

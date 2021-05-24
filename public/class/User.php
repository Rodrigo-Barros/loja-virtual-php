<?php

require ('autoload.php');
ini_set('display_errors',1);
class User
{
    // Retorn true in case of sucess of query
    public function createUser($email,$senha,$nome) : bool
    {
        $senha = password_hash($senha,PASSWORD_BCRYPT);
        //$query = Database::sql("INSERT INTO Usuarios (email,senha,nome) Values (:email,:senha,:nome)");
        $conn = Database::connect();
        $query = $conn->prepare("INSERT INTO Usuarios (email,senha,nome) Values (:email,:senha,:nome)");
        $query->bindParam(":email",$email);
        $query->bindParam(":senha",$senha);
        $query->bindParam(":nome",$nome);
        $query_status = $query->execute();
        session_start();
        $_SESSION['userInfo']['email'] = $email;
        $_SESSION['userInfo']['nome'] = $nome;
        $_SESSION['userInfo']['userType'] = 'user';
        $_SESSION['userInfo']['id'] = $conn->lastInsertId();
        return $query_status;
    }

    public function login($email,$senha) : bool
    {
        $query = Database::sql("SELECT nome,email,senha,id FROM Usuarios WHERE email=:email");
        $query->bindParam(':email',$email);
        $query->execute();
        foreach ($query as $row){
            for($i=0; $i<count($row);$i++){
                unset($row[$i]);
            }
            $cryptPass = $row['senha'];
            $userInfo = $row;
        }
        if (password_verify($senha,$cryptPass))
        {
            unset($userInfo['senha'],$cryptPass);
            session_start();
            $_SESSION['userInfo'] = $userInfo;
            $_SESSION['userInfo']['userType'] = 'user';
            return True;
        }
        return False;
    }



    // To fill user fields

    public function getUserInfo() : object
    {
        // session_start();
        if (isset($_SESSION['userInfo']['email'])){
            $email = $_SESSION['userInfo']['email'];
            $query = Database::sql("SELECT nome,email,bairro,cidade,nascimento,estado,telefone,
              cep,endereco,nascimento
              FROM Usuarios WHERE email = :email");
            $query->bindParam(":email",$_SESSION['userInfo']['email']);
            $query->execute();
            return $query->fetchObject();
        }else{
            return '{}';
        }


    }

    public function listarPedidos($userId)
    {
        $pedidos = Database::sql("SELECT id, status_pedido FROM Pedidos WHERE Pedidos.usuario_id = :userId");
        $pedidos->bindParam(':userId', $userId);
        $pedidos->execute();
        return $pedidos;
    }



    public function checkIfUserOrderedBeforeComment($userId=12,$productId=7) : bool
    // itemPedido.id itemPedido.idProduto itemPedido.idPedido
    // Pedidos.id, Pedidos.usuario_id
    {
        $produto = Database::sql("SELECT DISTINCT itemPedido.idProduto FROM Pedidos
	         INNER JOIN Usuarios
	          ON Usuarios.id = Pedidos.usuario_id
            INNER JOIN itemPedido
           WHERE Usuarios.id = :userId AND itemPedido.idProduto = :productId");


        $produto->bindParam(':userId', $userId);
        $produto->bindParam(':productId',$productId);
        $query_sts=$produto->execute();
        if($query_sts==False){
          var_dump($produto->errorInfo());

          $query_sts == False;
        }elseif ($produto->rowCount() == 0) {
          $query_sts = False;
        }
        elseif ($query_sts && $produto->rowCount() > 0) {
          $query_sts = True;
        }

        // echo "<pre>";
        // print_r($produto->fetchAll(PDO::FETCH_ASSOC));
        // echo "<pre>";
        return $query_sts;

    }
}

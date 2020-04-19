<?php

require ('autoload.php');
class User 
{
    // Retorn true in case of sucess of query
    public function createUser($email,$senha,$nome) : bool
    {
        $senha = password_hash($senha,PASSWORD_BCRYPT);
        $query = Database::sql("INSERT INTO Usuarios (email,senha,nome) Values (:email,:senha,:nome)");
        $query->bindParam(":email",$email);
        $query->bindParam(":senha",$senha);
        $query->bindParam(":nome",$nome);
        session_start();
        $_SESSION['userInfo']['email'] = $email;
        $_SESSION['userInfo']['nome'] = $nome;
        $_SESSION['userInfo']['userType'] = 'normal';
        return $query->execute(); 
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

    // Used to update all user info from user dashboard
    // section
    public function updateInfo()
    {
        
    }

    // To fill user fields
    
    public function getUserInfo() : object
    {
        // session_start();
        if (isset($_SESSION['userInfo']['email'])){
            $email = $_SESSION['userInfo']['email'];
            $query = Database::sql("SELECT nome,email,bairro,cidade,nascimento,estado,telefone,cep,endereco
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

    //For Rating system
    public function setProductNote($userId,$productId,$note)
    {

    }

    public function letComment($userId,$productId,$comment)
    {

    }

    public function checkIfUserOrderedBeforeComment($userId,$productId): bool 
    {
        return True;
    }
}
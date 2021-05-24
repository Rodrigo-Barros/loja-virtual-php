<?php
  require 'class/autoload.php';
  session_start();

  if(isset($_SESSION['userInfo']) == False || $_SESSION['userInfo']['userType'] != 'user'){
    header("Location:$siteRoot");
  }
  
  $senha = $_POST['current-pass'];
  $senha_encriptada = password_hash($senha, PASSWORD_BCRYPT);

  $stm = "UPDATE Usuarios SET nome=:nome, endereco=:endereco, email=:email,
  bairro=:bairro, cidade=:cidade, nascimento=:nasc, estado=:estado, telefone=:tel, cep=:cep";
  if ( $senha != "" ){
    $stm .= ", senha=:senha";
  }
  $stm .= " WHERE id = :id";

  $query = Database::sql($stm);
  $query->bindParam(':id', $_SESSION['userInfo']['id']);
  $query->bindParam(':nome', $_POST['nome']);
  $query->bindParam(':endereco', $_POST['logradouro']);
  $query->bindParam(':email',  $_POST['email']);
  $query->bindParam(':bairro', $_POST['bairro']);
  $query->bindParam(':cidade', $_POST['cidade']);
  $query->bindParam(':nasc', $_POST['nascimento']);
  $query->bindParam(':estado', $_POST['estado']);
  $query->bindParam(':tel', $_POST['telefone']);
  $query->bindParam(':cep', $_POST['cep']);
  
  // Somente atualiza a senha se ele estiver definida
  var_dump($senha);
  if ( $senha != "" ) {
    $query->bindParam(':senha', $senha_encriptada);
  }

  // Esconde o aviso do PDO de campos faltando quando o usuário não redefine sua senha
  $transaction = $query->execute();
  var_dump($transaction);
  if($transaction){
    echo "Dados Atualizados com sucesso";
  }


?>

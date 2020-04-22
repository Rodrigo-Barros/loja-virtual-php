<?php
  require 'class/autoload.php';
  session_start();

  if(isset($_SESSION['userInfo']) == False || $_SESSION['userInfo']['userType'] != 'user'){
    header("Location:$siteRoot");
  }

  $query = Database::sql('UPDATE Usuarios SET nome=:nome, endereco=:endereco, email=:email,
  bairro=:bairro, cidade=:cidade, nascimento=:nasc, estado=:estado, telefone=:tel, cep=:cep,
  senha=:senha WHERE id = :id');
  $senha = password_hash($_POST['current-pass'], PASSWORD_BCRYPT);
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
  $query->bindParam(':senha', $senha);


  if($query->execute()){
    echo "Dados Atualizados com sucesso";
  }


?>

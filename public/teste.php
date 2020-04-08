<?php 
if(isset($_SESSION)){
    echo "sessao não iniciada";
    var_dump($_SESSION);
}else{
    echo "sessao iniciada";
    session_start();
}
?>
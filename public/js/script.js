// Funções da página de produto

function updateProductImage(image){
    viewImage = document.getElementsByTagName('img')[0];
    viewImage.src = image.src;
}


function addToCart(productId){
    quantidade = document.querySelector('#counter input').value;
    window.location.href = window.location.origin + '/ecommerce/produto/' + productId + '/quantidade/' + quantidade;
}

// Carrinho

document.querySelector('div.modal').onclick = function(){
    this.style.visibility = 'hidden';
}

document.querySelector('a.finish-order').onclick = function(){
    document.querySelector('div.modal').style.visibility='visible';
}

document.querySelector('#mercadoPago img').onclick = function(){
  document.querySelector('.mercadoPago').style.display='block';
}


function finalizarPedido(metodoDePagamento){
    listProducts(metodoDePagamento);
}

function listProducts(paymentType,requestType='GET'){

    var rows = document.querySelectorAll('tbody tr');
    var apiUrl = document.location.origin + '/ecommerce/api';
    if (paymentType == 'mercadoPago'){
      var formSend = {
        'total'  : document.querySelector('#transaction_amount').value,
        'token'  : document.querySelector('input[name="token"]').value,
        'payment': document.querySelector('#payment_method_id').value,
      }
      apiUrl = document.location.origin + '/ecommerce/class/Api.php';
    }
    //Only for debug
    // var args = {
    //     "finalizar_pedido" : "default",
    //     "userId" : 10,
    //     "produtoInfo": [
    //         {"produto_id":7,"preco_unitario":50,quantidade:4},
    //         {"produto_id":6, "preco_unitario":300,quantidade:1}
    //     ]
    // };


    produtos = [];
    for (var i=0; i<=rows.length - 2; i++){
        produtos.push({
            'produto_id':parseInt(rows[i].children[4].children[0].href.match(/\d+/)[0]),
            'preco_unitario':parseFloat(rows[i].children[2].innerHTML.replace('R$ ','')),
            'quantidade':parseInt(rows[i].children[1].children[0].value)
        });
    }

    // Cancela o pedido caso o carrinho esteja vazio
    if ( produtos.length == 0){
        return;
    }

    // console.log(produtos);

    var request = new XMLHttpRequest();

    request.onreadystatechange = async function (){
        // console.log(this.status);
        if (this.status == 200 && this.readyState==4 ){
            console.log(this.responseText);
            // return valores_de_retorno = this.responseText;
        }
    }

    getParams = '?finalizar_pedido=' + paymentType + '&userId=' + userId;
    getParams += '&productInfo=' + JSON.stringify(produtos);
    if(paymentType == 'mercadoPago')
    {
      getParams += '&token=' + formSend.token + '&payment_method_id=' + formSend.payment;
      getParams += '&total=' + parseInt(formSend.total);
    }


    request.open(requestType,apiUrl +  getParams);
    request.send();
    console.log(request);
    document.querySelector('p.produto-removido.d-none').style.display="block";
    // console.log(produtos);
}

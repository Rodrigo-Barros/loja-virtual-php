<?php
    require 'class/autoload.php';
    $store = new Store();
    session_start();
    if (isset($_SESSION['userInfo'])==False || $_SESSION['userInfo']['userType']=='admin'){
        header('Location: public');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<?php require 'head.php'; ?>
<body>
    <?php require 'header.php'; ?>

    <main>
        <table id="carrinho">
            <thead>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço unitário</th>
                <th>Preço Total</th>
                <th>Ação</th>
            </thead>
            <tbody>
                <!-- <tr>
                    <td>PC master Racer</td>
                    <td><input type="number" max="10" min="1" ></td>
                    <td>R$ 5.000,00</td>
                    <td>R$ 10.000,00</td>
                    <td><a class="remove-btn" href="#">Remover</a></td>
                </tr> -->

                <?php
                    if(isset($_GET['delete_produto'])){
                        // echo $_GET['delete_produto'];
                        $del_sts=$store->removeFromCart($_GET['delete_produto'],$_SESSION['userInfo']['id']);
                        if($del_sts):
                    ?>
                        <p class="produto-removido">Produto Removido do Carrinho</p>
                        <form action="">
                    <?php
                        endif;
                    }
                    $produtos=$store->selectItemsFromCart($_SESSION['userInfo']['id']);
                    $total = 0;
                    foreach ($produtos as $produto):
                        $total += $produto['preco'] * $produto['quantidade'];

                ?>
                    <tr>
                    <td><img src="uploads/<?=json_decode($produto['imagens'])[0]?>" alt="Imagem <?=$produto['nome'];?>"><p><?=$produto['nome']?></p></td>
                    <td><input type="number" max="<?=$produto['estoque']?>" min="1" value="<?=$produto['quantidade'];?>"></td>
                    <td>R$ <?=number_format($produto['preco'], 2,',','.')?></td>
                    <td>R$ <?=number_format($produto['preco']*$produto['quantidade'], 2,',','.')?></td>
                    <td><a class="remove-btn" href="Carrinho?delete_produto=<?=$produto['id'];?>">Remover</a></td>
                </tr>

                <?php
                    endforeach;

                ?>

                <!-- Não alterar essa linha -->
                <tr><td colspan="4">Total: R$ <?=number_format($total, 2,',','.')?></td></tr>
            </tbody>
        </table>
        <p class="produto-removido d-none compra-finalizada" >Compra Finalizada</p>
        <a class="finish-order" href="javascript:void(0)">Finalizar Pedido</a>

        <div class="modal">
            <div class="payments">
                <a href="javascript:void(0)" onclick="finalizarPedido('default')"><img src="imagens/dafault-payment.png" alt="Método de pagamento Padrão"></a>
                <a href="javascript:void(0)" id="mercadoPago"><img src="imagens/mercado-pago.png" alt="Mercado Pago"></a>
                <a href=""><img src="imagens/pagseguro.png" alt="Pagseguro"></a>
            </div>
        </div>

        <div class="mercadoPago">
          <h2>Confirme seus dados do Mercado Pago antes de enviar</h2>
          <form id="pay" name="pay" >
            <fieldset>
                <input type="hidden" name="finalizar_pedido" value="mercadoPago">
                <p>
                    <label for="description">Descrição</label>
                    <input type="text" name="description" id="description" value="Ítem selecionado"/>
                </p>
                <p>
                    <label for="transaction_amount">Valor a pagar</label>
                    <input name="transaction_amount" id="transaction_amount" value="<?=$total?>"/>
                </p>
                <p>
                    <label for="cardNumber">Número do cartão</label>
                    <input type="text" id="cardNumber" data-checkout="cardNumber" />
                </p>
                <p>
                    <label for="cardholderName">Nome e sobrenome</label>
                    <input type="text" id="cardholderName" data-checkout="cardholderName" />
                </p>
                <p>
                    <label for="cardExpirationMonth">Mês de vencimento</label>
                    <input type="text" id="cardExpirationMonth" data-checkout="cardExpirationMonth" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  />
                </p>
                <p>
                    <label for="cardExpirationYear">Ano de vencimento</label>
                    <input type="text" id="cardExpirationYear" data-checkout="cardExpirationYear" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false" />
                </p>
                <p>
                    <label for="securityCode">Código de segurança</label>
                    <input type="text" id="securityCode" data-checkout="securityCode" onselectstart="return false" onpaste="return false" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"  />
                </p>
                <p>
                    <label for="installments">Parcelas</label>
                    <select id="installments" class="form-control" name="installments">

                    </select>
                </p>
                <p>
                    <label for="docType">Tipo de documento</label>
                    <select id="docType" data-checkout="docType" autocomplete="off" ></select>
                </p>
                <p>
                    <label for="docNumber">Número do documento</label>
                    <input type="text" id="docNumber" data-checkout="docNumber" />
                </p>
                <p>
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" value="test@test.com"/>
                </p>
                <input type="hidden" name="payment_method_id" id="payment_method_id" />
                <input type="submit" value="Pagar"/>
            </fieldset>
        </form>
        </div>

    </main>

    <footer></footer>

    <script>
        var userId=<?=$_SESSION['userInfo']['id']?>
    </script>
    <script src="js/script.js"></script>
    <script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
    <script type="text/javascript">
    window.onload = function(){
      window.Mercadopago.getIdentificationTypes();
    }
    window.Mercadopago.setPublishableKey("TEST-4c9ca35f-f253-41bb-8b63-e056cea62dcd");
    doSubmit = false;
    document.querySelector('#pay').addEventListener('submit', doPay);

    function doPay(event){
        event.preventDefault();
        if(!doSubmit){
            var $form = document.querySelector('#pay');

            window.Mercadopago.createToken($form, sdkResponseHandler);

            return false;
        }
    };

    function sdkResponseHandler(status, response) {
        if (status != 200 && status != 201) {
            alert("verify filled data");
        }else{
            var form = document.querySelector('#pay');
            var card = document.createElement('input');
            card.setAttribute('name', 'token');
            card.setAttribute('type', 'hidden');
            card.setAttribute('value', response.id);
            form.appendChild(card);
            doSubmit=true;
            finalizarPedido('mercadoPago');
            setTimeout(function(){document.querySelector('.mercadoPago').style.display='none'}, 2000);
            // form.submit();
        }
    };

    // Validacao do cartão de credito

  document.getElementById('cardNumber').addEventListener('keyup', guessPaymentMethod);
  document.getElementById('cardNumber').addEventListener('change', guessPaymentMethod);

  function guessPaymentMethod(event) {
      let cardnumber = document.getElementById("cardNumber").value;

      if (cardnumber.length >= 6) {
          let bin = cardnumber.substring(0,6);
          window.Mercadopago.getPaymentMethod({
              "bin": bin
          }, setPaymentMethod);
      }
  };

  function setPaymentMethod(status, response) {
      if (status == 200) {
          let paymentMethodId = response[0].id;
          let element = document.getElementById('payment_method_id');
          element.value = paymentMethodId;
          getInstallments();
      } else {
          alert(`payment method info error: ${response}`);
      }
  }

  // Pegar a quantidade de parcelas
  function getInstallments(){
      window.Mercadopago.getInstallments({
          "payment_method_id": document.getElementById('payment_method_id').value,
          "amount": parseFloat(document.getElementById('transaction_amount').value)

      }, function (status, response) {
          if (status == 200) {
              document.getElementById('installments').options.length = 0;
              response[0].payer_costs.forEach( installment => {
                  let opt = document.createElement('option');
                  opt.text = installment.recommended_message;
                  opt.value = installment.installments;
                  document.getElementById('installments').appendChild(opt);
              });
          } else {
              alert(`installments method info error: ${response}`);
          }
      });
  }
    </script>
</body>
</html>

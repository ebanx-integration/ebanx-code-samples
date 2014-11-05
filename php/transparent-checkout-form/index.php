<?php

require_once 'bootstrap.php';

if (!isset($_GET['amount']) || intval($_GET['amount']) <= 0)
{
  exit('No amount was informed.');
}

$amount       = $_GET['amount'];
$currencyCode = isset($_GET['currencyCode']) ? strtoupper($_GET['currencyCode']) : 'USD';
$currencySymbol = ($currencyCode == 'BRL') ? 'R$' : '$';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Secure payment</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/flat-ui.min.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container" id="secure-payment">
      <header>
        <div class="row">
          <div class="col-md-6">
            <img src="img/ebanx.png" alt="Secure payments by EBANX">
          </div>
          <div class="col-md-6">
          <p class="total-amount pull-right">Total: <span class="money"><?= $currencySymbol . money_format('%i', $amount) ?></span></p>
          </div>
        </div>
      </header>

      <div class="row">
        <div class="col-md-12">
          <div id="error-message" class="alert alert-danger">
            <p id="error-text"></p>
          </div>
        </div>
      </div>

      <form role="form" id="ebanx-payment" method="POST">
        <input type="hidden" name="amount" value="<?= $amount ?>">
        <input type="hidden" name="currency_code" value="<?= $currencyCode ?>">

        <div class="customer-data">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="name">Nome</label>
                <input type="text" class="form-control" name="name">
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email">
              </div>
              <div class="form-group">
                <label for="birth_date">Data de nascimento</label>
                <input type="text" class="form-control" name="birth_date">
              </div>
              <div class="form-group">
                <label for="document">CPF</label>
                <input type="text" class="form-control" name="document">
              </div>
              <div class="form-group">
                <label for="phone_number">Telefone</label>
                <input type="text" class="form-control" name="phone_number">
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="zipcode">CEP</label>
                <input type="text" class="form-control" name="zipcode">
              </div>
              <div class="form-group">
                <label for="address">Endereço</label>
                <input type="text" class="form-control" name="address">
              </div>
              <div class="form-group">
                <label for="street_number">Número</label>
                <input type="text" class="form-control" name="street_number">
              </div>
              <div class="form-group">
                <label for="street_complement">Complemento</label>
                <input type="text" class="form-control" name="street_complement">
              </div>
              <div class="form-group">
                <label for="city">Cidade</label>
                <input type="text" class="form-control" name="city">
              </div>
              <div class="form-group">
                <label for="state">Estado</label>
                <select name="state" class="form-control">
                  <option value="" selected="selected">Por favor selecione</option>
                  <option value="AC">Acre</option>
                  <option value="AL">Alagoas</option>
                  <option value="AM">Amazonas</option>
                  <option value="AP">Amapá</option>
                  <option value="BA">Bahia</option>
                  <option value="CE">Ceará</option>
                  <option value="DF">Distrito Federal</option>
                  <option value="ES">Espirito Santo</option>
                  <option value="GO">Goiás</option>
                  <option value="MA">Maranhão</option>
                  <option value="MG">Minas Gerais</option>
                  <option value="MS">Mato Grosso do Sul</option>
                  <option value="MT">Mato Grosso</option>
                  <option value="PA">Pará</option>
                  <option value="PB">Paraíba</option>
                  <option value="PE">Pernambuco</option>
                  <option value="PI">Piauí</option>
                  <option value="PR">Paraná</option>
                  <option value="RJ">Rio de Janeiro</option>
                  <option value="RN">Rio Grande do Norte</option>
                  <option value="RO">Rondônia</option>
                  <option value="RR">Roraima</option>
                  <option value="RS">Rio Grande do Sul</option>
                  <option value="SC">Santa Catarina</option>
                  <option value="SE">Sergipe</option>
                  <option value="SP">São Paulo</option>
                  <option value="TO">Tocantins</option>
                </select>
              </div>

              <input type="hidden" name="contry" value="br">
            </div>
          </div>
        </div>

        <div class="payment-data">
          <div class="row">
            <div class="col-md-12">
              <p>Selecione o método de pagamento:</p>
            </div>

            <div class="col-md-12">
              <div class="radio">
                <label>
                  <input type="radio" id="payment_method_boleto" name="payment_method" value="boleto" checked>
                  <img src="img/payment_boleto.png" alt="Boleto bancário">
                </label>
              </div>
            </div>

            <div class="col-md-12">
              <div class="radio">
                <label>
                  <input type="radio" id="payment_method_tef" name="payment_method" value="tef">
                  <img src="img/payment_tef1.png" alt="Banco do Brasil">
                  <img src="img/payment_tef2.png" alt="Itaú">
                  <img src="img/payment_tef3.png" alt="Bradesco">
                  <img src="img/payment_tef4.png" alt="HSBC">
                  <img src="img/payment_tef5.png" alt="Banrisul">
                </label>
              </div>

              <div class="payment-method-tef-details payment-method-details">
                <div class="form-group">
                  <label for="payment_type_tef">Banco</label>
                  <select class="form-control" name="payment_type_tef">
                    <option value="" selected="selected">Por favor selecione</option>
                    <option value="bancodobrasil">Banco do Brasil</option>
                    <option value="banrisul">Banrisul</option>
                    <option value="bradesco">Bradesco</option>
                    <option value="hsbc">HSBC</option>
                    <option value="itau">Itaú</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="radio">
                <label>
                  <input type="radio" id="payment_method_creditcard" name="payment_method" value="creditcard">
                  <img src="img/payment_cc1.png" alt="Visa">
                  <img src="img/payment_cc2.png" alt="Mastercard">
                  <img src="img/payment_cc3.png" alt="American Express">
                  <img src="img/payment_cc4.png" alt="Hipercard">
                  <img src="img/payment_cc5.png" alt="Elo">
                  <img src="img/payment_cc6.png" alt="Discover">
                  <img src="img/payment_cc7.png" alt="Diners">
                  <img src="img/payment_cc8.png" alt="Aura">
                </label>
              </div>

              <div class="payment-method-creditcard-details payment-method-details">
                <div class="form-group">
                  <label for="cc_name">Nome do titular</label>
                  <input type="text" name="cc_name" class="form-control" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="cc_number">Número do cartão</label>
                  <input type="text" name="cc_number" class="form-control" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="cc_cvv">Código de segurança (CVV)</label>
                  <input type="text" name="cc_cvv" class="form-control" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="cc_scheme">Bandeira</label>
                  <select class="form-control" name="cc_scheme" autocomplete="off">
                    <option value="" selected="selected">Por favor selecione</option>
                    <option value="aura">Aura</option>
                    <option value="amex">American Express</option>
                    <option value="diners">Diners</option>
                    <option value="discover">Discover</option>
                    <option value="elo">Elo</option>
                    <option value="hipercard">Hipercard</option>
                    <option value="mastercard">MasterCard</option>
                    <option value="visa">Visa</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="cc_expiration">Data de validade</label>
                  <input type="text" name="cc_expiration" class="form-control" autocomplete="off">
                </div>

                <div class="form-group">
                  <label for="cc_instalments">Parcelas</label>
                  <select name="cc_instalments" class="form-control" autocomplete="off">
                    <?php foreach (getTotalsWithInstallments($amount) as $i => $total): ?>
                      <option value="<?= $i ?>"><?= $i ?>x de <?= $currencySymbol . money_format('%i', $total / $i)?></option>
                    <?php endforeach ?>
                  </select>
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <a class="btn btn-primary btn-lg" id="submit">Pagar</a>
              <img src="img/loader.gif" id="loader" alt="Carregando...">
            </div>
          </div>
        </div>
      </form>
    </div>

    <div id="success-modal" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="success-modal" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Sucesso!</h4>
          </div>
          <div class="modal-body">
            <p>Seu pagamento foi recebido com sucesso!</p>
            <div class="actions">
              <a id="boleto-url-link" class="btn btn-info" href="" target="_blank">Imprimir boleto</a>
              <a href="<?= RETURN_URL ?>" class="btn btn-default" href="">Voltar para a loja</a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="js/flat-ui.min.js"></script>
    <script src="js/jquery.payment.js"></script>
    <script src="js/jquery.mask.min.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
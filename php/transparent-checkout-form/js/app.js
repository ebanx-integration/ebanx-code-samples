$(document).ready(function() {
  // Toggle payment details
  $('input[name=payment_method]').on('click change', function() {
    var method     = $('input[name=payment_method]:checked')[0]
      , elmClass   = method.id.replace(/_/g, '-') + '-details'
      , elmDetails = $('.' + elmClass);

    // Hide all details
    $('.payment-method-details').hide();

    // Show details for this method
    if (elmDetails) {
      elmDetails.toggle();
    }
  });

  // Input masks
  $('input[name=birth_date]').mask('00/00/0000');
  $('input[name=zipcode]').mask('00000-000');
  $('input[name=phone_number]').mask('(00) 0000-00009');
  $('input[name=document]').mask('000.000.000-00');

  // Masks for credit cards input
  $('input[name=cc_number]').payment('formatCardNumber');
  $('input[name=cc_cvv]').payment('formatCardCVC');
  $('input[name=cc_expiration]').payment('formatCardExpiry');

  function showErrorMessage(msg) {
    $('#error-text').text(msg);
    $('#error-message').slideDown();
  }

  function hideErrorMessage() {
    $('#error-message').slideUp();
  }

  // Disables fields and shows loader on Ajax call
  $(document).bind('ajaxStart', function(){
    $('#secure-payment input, #secure-payment select').attr('disabled', true);
    $('#loader').show();
  }).bind("ajaxComplete", function(){
    $('#secure-payment input, #secure-payment select').removeAttr('disabled');
    $('#loader').hide();
  });

  // Submit the payment details
  $('#submit').on('click', function() {
    hideErrorMessage();

    $.ajax({
        type: 'POST'
      , url: 'create_payment.php'
      , data: $('#ebanx-payment').serialize()
      , success: function(response) {
          var r;

          try {
            r = $.parseJSON(response);
          } catch (error) {
            showErrorMessage('Houve um erro ao criar o pagamento.');
            return;
          }

          if (r) {
            // If the request was successful
            if (r.status == 'success') {
              // Redirect to bank
              if (r.redirect_url) {
                window.location = r.redirect_url;
              // Show success popup
              } else {
                if (r.boleto_url) {
                  $('#boleto-url-link')
                    .attr('href', r.boleto_url)
                    .toggleClass('boleto-show');
                }

                $('#success-modal').modal({
                    backdrop: 'static'
                  , keyboard: false
                  , show: true
                });
              }
            // If the request failed
            } else {
              showErrorMessage(r.message);
            }
          }
        }
    });
  });
});
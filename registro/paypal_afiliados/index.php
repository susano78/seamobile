<?php 
// Include the configuration file  
//http://grupodesoluciones.com/intranetdesarrollo/paypal_afiliados/index.php?idafiliado=37742da6cac91f5695776199d27b8600
//http://grupodesoluciones.com/intranetdesarrollo/paypal_afiliados/index.php?sc=1&idafiliado=37742da6cac91f5695776199d27b8600

require_once 'config.php'; 

$currency='EUR';
$itemNumber=$idafiliado; //es el md5 del afiliado
$itemPrice=!esSoloCliente() ? $rs_empresa[0]['importe_afiliado_empresa']:$rs_empresa[0]['importe_cliente_empresa'];
$itemName=!esSoloCliente() ? 'Afiliación':'Alta de Cliente';
$paramSC=!esSoloCliente() ? '':'sc=1&';

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago por PayPal</title>
  </head>
  <body>
<script src="https://www.paypal.com/sdk/js?client-id=<?php echo $PAYPAL_CLIENT_ID; ?>&currency=<?php echo $currency;?>&locale=es_ES&commit=true&debug=true"></script>

<style>  
.hidden {
visibility: hidden;
}
.centrado {
    text-align: center;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}
.letra { font-size: 150% }
.letraroja { color: #f00; } 
</style>

<script>
paypal.Buttons({
    // Sets up the transaction when a payment button is clicked
    createOrder: (data, actions) => {
        return actions.order.create({
            "application_context": {
                "shipping_preference": "NO_SHIPPING"
            },
            "purchase_units": [{
                "custom_id": "<?php echo $itemNumber; ?>",
                "description": "<?php echo $itemName; ?>",
                "amount": {
                    "currency_code": "<?php echo $currency; ?>",
                    "value": <?php echo $itemPrice; ?>,
                    "breakdown": {
                        "item_total": {
                            "currency_code": "<?php echo $currency; ?>",
                            "value": <?php echo $itemPrice; ?>
                        }
                    }
                },
                "items": [
                    {
                        "name": "<?php echo $itemName; ?>",
                        "description": "<?php echo $itemName; ?>",
                        "unit_amount": {
                            "currency_code": "<?php echo $currency; ?>",
                            "value": <?php echo $itemPrice; ?>
                        },
                        "quantity": "1",
                        "category": "DIGITAL_GOODS"
                    },
                ]
            }]
        });
    },
    // Finalize the transaction after payer approval
    onApprove: (data, actions) => {
        return actions.order.capture().then(function(orderData) {
            setProcessing(true);

            var postData = {paypal_order_check: 1, order_id: orderData.id};
            fetch('paypal_checkout_validate.php?<?=$paramSC;?>idafiliado=<?=$idafiliado;?>', {
                method: 'POST',
                headers: {'Accept': 'application/json'},
                body: encodeFormData(postData)
            })
            .then((response) => response.json())
            .then((result) => {
                if(result.status == 1){
                    window.location.href = "payment-status.php?<?=$paramSC;?>checkout_ref_id="+result.ref_id+"&item_number="+result.item_number+"&amount_value="+result.amount_value;
                }else{
                    const messageContainer = document.querySelector("#paymentResponse");
                    messageContainer.classList.remove("hidden");
                    messageContainer.textContent = result.msg;
                    
                    setTimeout(function () {
                        messageContainer.classList.add("hidden");
                        messageText.textContent = "";
                    }, 20000);
                }
                setProcessing(false);
            })
            .catch(error => console.log(error));
        });
    }
}).render('#paypal-button-container');

const encodeFormData = (data) => {
  var form_data = new FormData();

  for ( var key in data ) {
    form_data.append(key, data[key]);
  }
  return form_data;   
}

// Show a loader on payment form processing
const setProcessing = (isProcessing) => {
    if (isProcessing) {
        document.querySelector(".overlay").classList.remove("hidden");
    } else {
        document.querySelector(".overlay").classList.add("hidden");
    }
}    
</script>

<div class="panel centrado letra">
    <div class="overlay hidden"><div class="overlay-content">
        <img src="loading.gif" alt="Procesando..."/>Procesando...</div>
    </div>

    <div class="panel-heading">
        <h3 class="panel-title">Pago de <?php echo $itemPrice.' Euros'; ?> con PayPal</h3>
        
        <!-- Product Info -->
        <p><b>Concepto:</b> <?php echo $itemName; ?></p>
        <p><b>Importe:</b> <?php echo $itemPrice.' Euros'; ?></p>
    </div>
    <div class="panel-body">
        <!-- Display status message -->
        <div id="paymentResponse" class="hidden letraroja"></div>
        
        <!-- Set up a container element for the button -->
        <div id="paypal-button-container"></div>

    </div>
</div>
</body>
</html>
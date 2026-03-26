<?php 
// Include the configuration file 
require_once 'config.php'; 
 
// Include the PayPal API library 
require_once 'paypalCheckout.class.php'; 
$paypal = new paypalCheckout; 
 
$response = array('status' => 0, 'msg' => 'Transacción fallida!'); 

if(!empty($_POST['paypal_order_check']) && !empty($_POST['order_id'])){ 
    // Validate and get order details with PayPal API 
     $order = $paypal->validate($_POST['order_id']); 

     if(!empty($order) && isset($order['id'])){ 
        $order_id = $order['id']; 
        $intent = $order['intent']; 
        $order_status = $order['status']; 
        $order_time = date("Y-m-d H:i:s", strtotime($order['create_time'])); 
 
        if(!empty($order['purchase_units'][0])){ 
            $purchase_unit = $order['purchase_units'][0]; 
 
            $item_number = $purchase_unit['custom_id']; 
            $item_name = $purchase_unit['description']; 
             
            if(!empty($purchase_unit['amount'])){ 
                $currency_code = $purchase_unit['amount']['currency_code']; 
                $amount_value = $purchase_unit['amount']['value']; 
            } 
 
            if(!empty($purchase_unit['payments']['captures'][0])){ 
                $payment_capture = $purchase_unit['payments']['captures'][0]; 
                $transaction_id = $payment_capture['id']; 
                $payment_status = $payment_capture['status']; 
            }  
        } 

        if(!empty($order_id) && $order_status == 'COMPLETED'){ 
 
            if(!empty($transaction_id)){ 
                $ref_id_enc = base64_encode($transaction_id); 
                $response = array('status' => 1, 'msg' => 'Transacción completada!', 'ref_id' => $ref_id_enc, 'item_number' => $item_number, 'amount_value' => $amount_value); 
            } 
        } 
        else{
            $response = array('status' => 0, 'msg' => 'Transacción no completada! ->'.$order_status); 
        }
    }else{ 
        $response['msg'] = $order['error'];
    } 
}  
echo json_encode($response); 
?>
<?php if ($testmode) { ?>
<div class="alert alert-warning"><?php echo $text_test; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="cmd" value="_cart" />
  <input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>" />
  <input type="hidden" name="access_key" value="<?php echo $access_key; ?>" />
  <input type="hidden" name="signature" value="<?php echo $signature; ?>" />
  <input type="hidden" name="transaction_uuid" value="<?php echo $transaction_uuid; ?>" />
  <input type="hidden" name="reference_number" value="<?php echo $reference_number; ?>" />
  <input type="hidden" name="locale" value="<?php echo $locale; ?>" />
  <input type="hidden" name="transaction_type" value="<?php echo $transaction_type; ?>" />
  <input type="hidden" name="signed_date_time" value="<?php echo $signed_date_time; ?>" />
  <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
  <input type="hidden" name="locale" value="<?php echo $locale; ?>" />
  <input type="hidden" name="bill_to_forename" value="<?php echo $firstname; ?>" />
  <input type="hidden" name="bill_to_surname" value="<?php echo $lastname; ?>" />
  <input type="hidden" name="bill_to_address_line1" value="<?php echo $address1; ?>" />
  <input type="hidden" name="bill_to_address_line2" value="<?php echo $address2; ?>" />
  <input type="hidden" name="bill_to_address_city" value="<?php echo $billingCity; ?>" />
  <input type="hidden" name="bill_to_address_postal_code" value="<?php echo $zipCode; ?>" />
  <input type="hidden" name="bill_to_address_country" value="<?php echo $billingCountry; ?>" />
  <input type="hidden" name="bill_to_email" value="<?php echo $buyerEmail; ?>" />
  <input type="hidden" name="signed_field_names" value="<?php echo $signed_field_names; ?>" />
  <input type="hidden" name="unsigned_field_names" value="<?php echo $unsigned_field_names; ?>" />
  <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />  
  <input type="hidden" name="_opencart" value="<?php echo $_opencart; ?>" />
  <input type="hidden" name="order_desc" value="<?php echo $order_desc; ?>" />
  <input type="hidden" name="description" value="<?php echo $description; ?>" />  

  <?php if ($testmode) { ?>
  <input type="hidden" name="test" value="1" />
  <?php } ?>

  <div class="buttons">
    <div class="pull-right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
    </div>
  </div>
</form>

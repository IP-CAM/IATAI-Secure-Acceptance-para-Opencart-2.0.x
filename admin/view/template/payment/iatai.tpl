<?php echo $header; ?><?php echo $column_left; ?>
<script type="text/javascript">
    function check_status(){
        if (document.getElementById('iatai_test').value == 1) {
            // disable production fields
            document.getElementById('iatai_profileid').disabled=true;
            document.getElementById('iatai_accesskey').disabled=true;
            document.getElementById('iatai_secretkey').disabled=true;
            document.getElementById('iatai_liveurl').disabled=true;
            document.getElementById('iatai_confirmurl').disabled=true;
            document.getElementById('iatai_confirmuser').disabled=true;
            document.getElementById('iatai_confirmpass').disabled=true;
            // enable test fields
            document.getElementById('iatai_testprofileid').disabled=false;
            document.getElementById('iatai_testaccesskey').disabled=false;
            document.getElementById('iatai_testsecretkey').disabled=false;
            document.getElementById('iatai_testurl').disabled=false;
            document.getElementById('iatai_testconfirmurl').disabled=false;
            document.getElementById('iatai_testconfirmuser').disabled=false;
            document.getElementById('iatai_testconfirmpass').disabled=false;            
        } else {
	    // enable production fields
            document.getElementById('iatai_profileid').disabled=false;
            document.getElementById('iatai_accesskey').disabled=false;
            document.getElementById('iatai_secretkey').disabled=false;
            document.getElementById('iatai_liveurl').disabled=false;
            document.getElementById('iatai_confirmurl').disabled=false;
            document.getElementById('iatai_confirmuser').disabled=false;
            document.getElementById('iatai_confirmpass').disabled=false;
            // disable test fields
            document.getElementById('iatai_testprofileid').disabled=true;
            document.getElementById('iatai_testaccesskey').disabled=true;
            document.getElementById('iatai_testsecretkey').disabled=true;
            document.getElementById('iatai_testurl').disabled=true;
            document.getElementById('iatai_testconfirmurl').disabled=true;
            document.getElementById('iatai_testconfirmuser').disabled=true;
            document.getElementById('iatai_testconfirmpass').disabled=true;
        }
    }
</script>

<div id="content">
    <div id="content">
    <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/payment/iatai.png" style="height:25px; margin-top:-5px;" /> <?php echo $heading_title; ?></h1>
      <div class="pull-right"><a onclick="$('#form').submit();" class="btn btn-primary"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="btn btn-default"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content iatai">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form" >
          <tr>
            <td><?php echo $entry_status; ?>
            <span class="help"><?php echo $help_status; ?></span></td>
            <td><select name="iatai_status">
                <?php 
                $st0 = $st1 = "";
                 if ( $iatai_status == 0 ) $st0 = 'selected="selected"';
                  else $st1 = 'selected="selected"';
                ?>
                <option value="1" <?php echo @$st1; ?> ><?php echo $text_enabled; ?></option>
                <option value="0" <?php echo @$st0; ?> ><?php echo $text_disabled; ?></option>

              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_test; ?>
            <span class="help"><?php echo $help_test; ?></span></td>
            <td><select name="iatai_test" id="iatai_test" onchange="check_status();">
              <?php 
                $st0 = $st1 = ""; 
                 if ( $iatai_test == 0 ) $st0 = 'selected="selected"';
                  else $st1 = 'selected="selected"';
              ?>
                <option value="1" <?php echo $st1; ?> ><?php echo $entry_test_on; ?></option>
                <option value="0" <?php echo $st0; ?> ><?php echo $entry_test_off; ?></option>
              </select></td>
          </tr>
          <tr>
            <td colspan="2">
            <table><tr><td valign="top">
              <h3 class="heading">Live Checkout Options</h4>
              <table class="inner-table">
                <tr>
                  <td width="200"><span class="required">*</span> <?php echo $entry_profileid; ?>
                    <span><?php echo $help_profileid; ?></span></td>
                  <td><input type="text" id="iatai_profileid" name="iatai_profileid" value="<?php echo $iatai_profileid; ?>" />
                    <?php if ($iatai_test==0&&$iatai_profileid=='') { ?>
                    <span class="error"><?php echo $error_profileid; ?></span>
                    <?php } ?></td>
                </tr>
                <tr>
                  <td><span class="required">*</span> <?php echo $entry_accesskey; ?>
                    <span><?php echo $help_accesskey; ?></span></td>
                  <td><input type="text" id="iatai_accesskey" name="iatai_accesskey" value="<?php echo $iatai_accesskey; ?>" />
                    <?php if ($iatai_test==0&&$iatai_accesskey==''){ ?>
                    <span class="error"><?php echo $error_secretkey; ?></span>
                    <?php } ?></td>
                </tr>
                <tr>
                  <td><span class="required">*</span> <?php echo $entry_secretkey; ?>
                    <span><?php echo $help_secretkey; ?></span></td>
                  <td><textarea name="iatai_secretkey" id="iatai_secretkey" cols="45" rows="8"><?php echo $iatai_secretkey; ?></textarea>
                    <?php if ($iatai_test==0&&$iatai_secretkey=='') { ?>
                    <span class="error"><?php echo $error_secretkey; ?></span>
                    <?php } ?></td>
                </tr>
                <tr>
                  <td><span class="required">*</span> <?php echo $entry_liveurl; ?>
                    <span><?php echo $help_liveurl; ?></span></td>
                  <td><input type="text" style="width:350px;" id="iatai_liveurl" name="iatai_liveurl" value="<?php echo ($iatai_liveurl)?$iatai_liveurl:$default_liveurl; ?>" />
                    <?php if ($iatai_test==0&&$iatai_liveurl=='') { ?>
                    <span class="error"><?php echo $error_liveurl; ?></span>
                    <?php } ?></td></td>
                </tr>
                <tr>
                  <td><span class="required">*</span> <?php echo $entry_confirmurl; ?>
                    <span><?php echo $help_confirmurl; ?></span></td>
                  <td><input type="text" style="width:350px;" id="iatai_confirmurl" name="iatai_confirmurl" value="<?php echo ($iatai_confirmurl)?$iatai_confirmurl:$default_confirmurl; ?>" />
                    <?php if ($iatai_test==0&&$iatai_confirmurl=='') { ?>
                    <span class="error"><?php echo $error_confirmurl; ?></span>
                    <?php } ?></td>
                </tr>
                <tr>
                  <td><span class="required">*</span> <?php echo $entry_confirmuserpass; ?>
                    <span><?php echo $help_confirmuserpass; ?></span></td>
                  <td>User: <input type="text" style="width:200px;" id="iatai_confirmuser" name="iatai_confirmuser" value="<?php echo ($iatai_confirmuser)?$iatai_confirmuser:$default_testconfirmuser; ?>" /><br /> Pass:<input type="text" style="width:200px;" id="iatai_confirmpass" name="iatai_confirmpass" value="<?php echo ($iatai_confirmpass)?$iatai_confirmpass:$default_testconfirmpass; ?>" />
                    <?php if ($iatai_test==0&&($iatai_confirmuser==''||$iatai_confirmpass=='')) { ?>
                    <span class="error"><?php echo $error_confirmuserpass; ?></span>
                    <?php } ?></td>
                </tr>
                </table>
	    </td>
            <td valign="top">
              <h3>Test Checkout Options</h3>
              <table class="inner-table-test">
                <tr>
                  <td width="200"><span class="required">*</span> <?php echo $entry_testprofileid; ?>
                    <span><?php echo $help_testprofileid; ?></span></td>
                  <td><input type="text" id="iatai_testprofileid" id="iatai_testprofileid" name="iatai_testprofileid" value="<?php echo ($iatai_testprofileid)?$iatai_testprofileid:$default_testprofileid; ?>" />
                    <?php if ($iatai_test==1&&$iatai_testprofileid=='') { ?>
                    <span class="error"><?php echo $error_profileid; ?></span>
                    <?php } ?></td>
                </tr>
                <tr>
                  <td><span class="required">*</span> <?php echo $entry_testaccesskey; ?>
                    <span><?php echo $help_testaccesskey; ?></span></td>
                  <td><input type="text" id="iatai_testaccesskey" name="iatai_testaccesskey" value="<?php echo ($iatai_testaccesskey)?$iatai_testaccesskey:$default_testaccesskey; ?>" />
                    <?php if ($iatai_test==0&&$iatai_testaccesskey=='') { ?>
                    <span class="error"><?php echo $error_accesskey; ?></span>
                    <?php } ?></td>
                </tr>
                <tr>
                  <td><span class="required">*</span> <?php echo $entry_testsecretkey; ?>
                    <span><?php echo $help_testsecretkey; ?></span></td>
                    <td><textarea name="iatai_testsecretkey" id="iatai_testsecretkey" cols="55" rows="8"><?php echo ($iatai_testsecretkey)?$iatai_testsecretkey:$default_testsecretkey; ?></textarea>
                    <?php if ($iatai_test==1&&$iatai_testsecretkey=='') { ?>
                    <span class="error"><?php echo $error_secretkey; ?></span>
                    <?php } ?></td>
                </tr>
                <tr>
                  <td><span class="required">*</span> <?php echo $entry_testurl; ?>
                    <span><?php echo $help_testurl; ?></span></td>
                  <td><input type="text" style="width:350px;" name="iatai_testurl" id="iatai_testurl" value="<?php echo ($iatai_testurl)?$iatai_testurl:$default_testurl; ?>" />
                    <?php if ($iatai_test==1&&$iatai_testurl=='') { ?>
                    <span class="error"><?php echo $error_liveurl; ?></span>
                    <?php } ?></td>
                </tr>
                <tr>
                  <td><span class="required">*</span> <?php echo $entry_testconfirmurl; ?>
                    <span class="help"><?php echo $help_testconfirmurl; ?></span></td>
                  <td><input type="text" style="width:350px;" id="iatai_testconfirmurl" name="iatai_testconfirmurl" value="<?php echo ($iatai_testconfirmurl)?$iatai_testconfirmurl:$default_testconfirmurl; ?>" />
                    <?php if ($iatai_test==0&&$iatai_testconfirmurl=='') { ?>
                    <span class="error"><?php echo $error_confirmurl; ?></span>
                    <?php } ?></td>
                </tr>
                <tr>
                  <td><span class="required">*</span> Test <?php echo $entry_confirmuserpass; ?>
                    <span><?php echo $help_confirmuserpass; ?></span></td>
                  <td>User: <input type="text" style="width:200px;" id="iatai_testconfirmuser" name="iatai_testconfirmuser" value="<?php echo ($iatai_testconfirmuser)?$iatai_testconfirmuser:$default_testconfirmuser; ?>" /><br /> Pass:<input type="text" style="width:200px;" id="iatai_testconfirmpass" name="iatai_testconfirmpass" value="<?php echo ($iatai_testconfirmpass)?$iatai_testconfirmpass:$default_testconfirmpass; ?>" />
                    <?php if ($iatai_test==0&&($iatai_testconfirmuser==''||$iatai_testconfirmpass=='')) { ?>
                    <span class="error"><?php echo $error_confirmuserpass; ?></span>
                    <?php } ?></td>
                </tr>
              </table>
            </td></tr></table>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status_accepted; ?>
            <span class="help"><?php echo $help_order_status_accepted; ?></span></td>
            <td><select name="iatai_order_status_accepted" >
                <?php 
                foreach ($status_list as $order_status) { 

                $st = ($order_status['order_status_id'] == $iatai_order_status_accepted) ? ' selected="selected" ' : ""; 
                ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" <?php echo $st; ?> ><?php echo $order_status['name']; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status_pending; ?>
            <span class="help"><?php echo $help_order_status_pending; ?></span></td>
            <td><select name="iatai_order_status_pending">
                <?php 
                foreach ($status_list as $order_status) { 

                $st = ($order_status['order_status_id'] == $iatai_order_status_pending) ? ' selected="selected" ' : ""; 
                ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" <?php echo $st; ?> ><?php echo $order_status['name']; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status_declined; ?>
            <span class="help"><?php echo $help_order_status_declined; ?></span></td>
            <td><select name="iatai_order_status_declined">
                <?php 
                foreach ($status_list as $order_status) { 

                $st = ($order_status['order_status_id'] == $iatai_order_status_declined) ? ' selected="selected" ' : ""; 
                ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" <?php echo $st; ?> ><?php echo $order_status['name']; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status_canceled; ?>
            <span class="help"><?php echo $help_order_status_canceled; ?></span></td>
            <td><select name="iatai_order_status_canceled">
                <?php 
                foreach ($status_list as $order_status) { 

                $st = ($order_status['order_status_id'] == $iatai_order_status_canceled) ? ' selected="selected" ' : ""; 
                ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" <?php echo $st; ?> ><?php echo $order_status['name']; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status_failed; ?>
            <span class="help"><?php echo $help_order_status_failed; ?></span></td>
            <td><select name="iatai_order_status_failed">
                <?php 
                foreach ($status_list as $order_status) { 

                $st = ($order_status['order_status_id'] == $iatai_order_status_failed) ? ' selected="selected" ' : ""; 
                ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" <?php echo $st; ?> ><?php echo $order_status['name']; ?></option>
                <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?>
              <span class="help"><?php echo $help_sort_order; ?></span></td>
            <td><input type="text" name="iatai_sort_order" value="<?php echo $iatai_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
 </div>
</div>
</div>
</div>
</div>
<script type="text/javascript">
     check_status();
</script>
<?php echo $footer; ?>
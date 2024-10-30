<?php
	global $mixForms;

	$formKey = isset($_REQUEST["formKey"]) ? "&formKey=". $_REQUEST["formKey"] : "";
?>

<script src="<?php echo $mixForms->scriptsUrl; ?>"></script>
<link href='<?php echo $mixForms->stylesUrl; ?>' rel='stylesheet' type='text/css'>

<div class="wrap">
	<img src="<?php echo $mixForms->baseUrl;?>/app/images/logo-transparent.png" class="logo" />
	<iframe id="formbuilderiframe" onload="mixFormsSizeIframe(this)" style="width:100%; height:100%;" src="<?php echo $mixForms->baseUrl. "/submissions?userHash=". $mixForms->key. $formKey; ?>"></iframe>
</div>
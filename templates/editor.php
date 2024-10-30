<?php
	global $mixForms;

	$formKey = isset($_REQUEST["formKey"]) ? "&formKey=". $_REQUEST["formKey"] : "";
?>

<script src="<?php echo $mixForms->scriptsUrl; ?>"></script>
<link href='<?php echo $mixForms->stylesUrl; ?>' rel='stylesheet' type='text/css'>

<div class="wrap mixforms-container">
	<div class="header">
		<span class="fullscreen-toggler fullscreen-toggler--close">Close fullscreen</span>
		<span class="fullscreen-toggler fullscreen-toggler--open">Fullscreen</span>
		<img src="<?php echo $mixForms->baseUrl;?>/app/images/logo-transparent.png" class="logo" />
	</div>
	<iframe id="formbuilderiframe" onload="mixFormsSizeIframe(this)" style="width:100%; height:100%;" src="<?php echo $mixForms->baseUrl. "/formbuilder?userHash=". $mixForms->key. $formKey; ?>"></iframe>
</div>
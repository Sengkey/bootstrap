
<?php
if($data['resend']==1) {
?>
	<!-- error start -->
	<div class="alert alert-success fade in">
       An activation email has been sent to your email address
    </div>
	<!-- error // -->
<?php 
} else if($data['resend']==0) {
?>
	<h3>Account Activation</h3>
	<p>An activation link has been sent to your email address.<p>
	<p>If you haven't received the email to activate your account, please click the button below to resend the activation email.</p>
	<br>
	<form method="POST">
		<input type="hidden" name="type" value="resend">
		<input type="submit" class="btn btn-default" value="Re-send Confirmation" />
	</form>
<?php
}
?>
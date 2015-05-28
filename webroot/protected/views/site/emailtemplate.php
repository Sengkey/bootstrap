<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charsetUTF-8">
    <meta name="viewport" content="width=device-width">
	<title>BeePond | Social Media Aggregator</title>
    <script src="//localhost:35729/livereload.js"></script>
<style type='text/css'>
        @media only screen and (max-width:460px){
            body[yahoo] table {width:100% !important;}
            body[yahoo] .marginAl {width:15px !important;}
            body[yahoo] .socialDivider {display:inline-block;width:7%;}
            body[yahoo] .imageStr {width:100% !important;}
            body[yahoo] .disAppear {display:none !important;}
            }
        </style>    
</head>
<body yahoo="fix" style="margin:0;padding:0;font-family:Arial,Helvetica,sans-serif;font-size:14px;line-height:22px;">
<div class="container">

<!-- // outter table -->	
<table bgcolor="f8f8f8" style="background:#f8f8f8;" width="100%" cellpadding="0" cellspacing="0"><tr><td>
	<br>
	<table id="one" bgcolor="ffffff" style="font-family: arial, helvetica, sans-serif; font-size:14px; line-height:20px; background-color: #FFFFFF; font-color:black;border-top:1px solid #e8e8e8;border-right:1px solid #e8e8e8;border-bottom:1px solid #e8e8e8;border-left:1px solid #e8e8e8;" width="600" cellpadding="0" cellspacing="0" border="0" align="center">
		<!-- Header -->
		<tr>
			<td width="30" class='marginAl'><!-- Left --></td>
			<td width="540">
				<br /><br />
				<img src="http://beepond.com/img/beepond_logo_174x40.png" />
			</td>
			<td width="30" class='marginAl'><!-- Right --></td>
		</tr>
		<!-- //. Header -->

		<!-- Content -->
		<tr>
			<td width="30" class='marginAl'><!-- Left --></td>
			<td width="540">
				<br /><br />

<?php $this->renderPartial($data['tpl'],array("data"=>$data));?>

			</td>
			<td width="30" class='marginAl'><!-- Right --></td>
		</tr>
		<!-- //. Content -->

		<!-- footer -->
		<tr>
			<td width="30" class='marginAl'><!-- Left --></td>
			<td style="font-size:10px;color:#bbbbbb;" width="540">
				<br /><br />
<?php 
if(!isset($data['nofooterlinks']) && !$data['nofooterlinks']) {
?>
				<a href="#" style="color:gray;">Unsubscribe</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <a href="#" style="color:gray;">Forward to a friend</a>
				<br /><br />
<?php
}
?>
				BeePond is owned and operated by BeePond Pty Ltd. All right reserved.
				<br /><br />
			</td>
			<td width="30" class='marginAl'><!-- Right --></td>
		</tr>
		<!-- //. footer -->

	</table><!-- //. outter table -->	
	<br>
</td></tr></table><!-- //. outter table -->

</div><!--/. container -->



</body>
</html>

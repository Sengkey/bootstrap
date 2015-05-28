Please click the following link to activate your account:
<br>
<?php 
$activationLink = Yii::app()->request->getBaseUrl(true)."/confirm?t=".$data['token'];
?>
<a href="<?php echo $activationLink;?>" target="_blank"><?php echo $activationLink;?></a>
<!DOCTYPE html>
<html>
<head>
	<title><?php echo CHtml::encode($this->pageTitle); ?> - <?php echo CHtml::encode(Yii::app()->name); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="viewport" content="initial-scale=0.8, width=device-width, minimum-scale=0.5">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->getBaseUrl(true)?>/css/admin.css" />
	<script type="text/javascript" language="javascript" src="<?php echo Yii::app()->request->getBaseUrl(true); ?>/js/jquery.js"></script>
	<script type="text/javascript" language="javascript" src="<?php echo Yii::app()->request->getBaseUrl(true); ?>/js/jquery.dataTables.min.js"></script>
</head>
<body>
	<?php echo $content; ?>
	<div class="clear"></div>
	<div id="footer">
		<p><?php echo date("Y");?> © <?php echo CHtml::encode(Yii::app()->name); ?>. All Rights Reserved.</p>
		<br><br>
	</div>
</body>
</html>
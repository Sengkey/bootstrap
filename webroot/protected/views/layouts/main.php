<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>BeePond | Social Media Aggregator</title>
	<link href="<?=Yii::app()->request->getBaseUrl(true)?>/css/beepond.css" rel="stylesheet" type="text/css">
	<link href="<?=Yii::app()->request->getBaseUrl(true)?>/css/beepond-admin.css" rel="stylesheet" type="text/css">
	
	<script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/modernizr-2.6.2.min.js"></script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <link rel="stylesheet" href="css/ie8.css">	
	  <script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/html5shiv.js"></script>
	  <script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/respond.min.js"></script>
	<![endif]-->
	<!--[if lt IE 8]>
	  <link rel="stylesheet" href="css/ie7.css">	
	  <script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/html5shiv.js"></script>
	  <script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/respond.min.js"></script>
	<![endif]-->

	<script type='text/javascript' src="<?=Yii::app()->request->getBaseUrl(true)?>/js/jquery-1.8.3.js" ></script>
	<script type='text/javascript' src='<?=Yii::app()->request->getBaseUrl(true)?>/js/bootstrap.min.js'></script>
	<script type='text/javascript' src='<?=Yii::app()->request->getBaseUrl(true)?>/js/main.js'></script>

</head>
<body>
	<?php echo $content;?>
</body>
</html>

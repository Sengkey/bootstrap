<?php $this->pageTitle=Yii::app()->name; ?>

	<div class="login_header" role="navigation">
		<div class="container">
			<div class="row">
				<div class="col-sm-12 col-md-12 hidden-xs"><span class="beepond_logo"><i class="icon beepond"></i><em>Bee<b>Pond</b></em> <small>Social Media Aggregator</small></span></div>
				
				<div class="col-sm-12 col-md-12">
					<!-- Nav tabs -->
						<!-- <ul class="nav nav-tabs">
						  <li><a href="#home" data-toggle="tab">Dashboard</a></li>
						  <li class="active"><a href="#profile" data-toggle="tab">Campaign</a></li>
						  <li><a href="#messages" data-toggle="tab">Billing</a></li>
						  <li><a href="#settings" data-toggle="tab">Settings</a></li>
						</ul> -->	
					<nav class="navbar navbar-inverse" role="navigation">
						<div class="container-fluid">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand visible-xs" href="#"><i class="icon beepond"></i><i>Bee<b>Pond</b></i></a>
							</div>
							 
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul class="nav navbar-nav">
<?php 
$activeprofile = ($data['activemenu']=="profile") ? " active" : "";
$activedashboard = ($data['activemenu']=="dashboard") ? 'class="active"' : "";
$activecampaign = ($data['activemenu']=="campaign") ? 'class="dropdown active"' : 'class="dropdown"';
$activetemplate = ($data['activemenu']=="template") ? 'class="active"' : '';
$activebilling = ($data['activemenu']=="billing") ? 'class="active"' : "";
$activesettings = ($data['activemenu']=="settings") ? 'class="active"' : "";
$activesupport = ($data['activemenu']=="support") ? 'class="active"' : "";
?>
									<li class="dropdown<?php echo $activeprofile;?>">
							        <a id="drop6" role="button" data-toggle="dropdown" href="#"><i class="icon user"></i><?php echo Yii::app()->user->email;?> <b class="caret"></b></a>
								        <ul id="menu3" class="dropdown-menu" role="menu" aria-labelledby="drop6">
								          <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=Yii::app()->request->getBaseUrl(true)?>/profile">Profile</a></li>
								          <!-- <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=Yii::app()->request->getBaseUrl(true)?>/">Another action</a></li>
								          <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=Yii::app()->request->getBaseUrl(true)?>/">Something else here</a></li> -->
								          <li role="presentation" class="divider"></li>
								          <li role="presentation"><a role="menuitem" tabindex="-1" href="<?=Yii::app()->request->getBaseUrl(true)?>/logout">Logout</a></li>
								        </ul>
							      	</li>
							      	<li <?php echo $activedashboard;?>><a href="<?=Yii::app()->request->getBaseUrl(true)?>/dashboard">Dashboard</a></li>
									<!-- <li <?php echo $activecampaign;?>><a href="<?=Yii::app()->request->getBaseUrl(true)?>/campaign">Campaigns</a></li> -->
									<li <?php echo $activecampaign;?>>
							        <a id="drop7" role="button" data-toggle="dropdown" href="#">Campaigns <b class="caret"></b></a>
								        <ul id="menu4" class="dropdown-menu" role="menu" aria-labelledby="drop7">
								          <li role="presentation_"><a role="menuitem" tabindex="-1" href="<?=Yii::app()->request->getBaseUrl(true)?>/campaign">Campaign List</a></li>	
								        </ul>
							      	</li>
									<!-- <li <?php echo $activetemplate;?>><a href="<?=Yii::app()->request->getBaseUrl(true)?>/template">Templates</a></li> -->
									<li <?php echo $activebilling;?>><a href="<?=Yii::app()->request->getBaseUrl(true)?>/billing">Billing</a></li>
									<!-- <li <?php echo $activesettings;?>><a href="<?=Yii::app()->request->getBaseUrl(true)?>/settings">Settings</a></li> -->
									<li <?php echo $activesupport;?>><a href="<?=Yii::app()->request->getBaseUrl(true)?>/support">Support</a></li>
									<!-- <li><a href="">Support</a></li> -->
									
								</ul>


							</div> 
						</div> 
					</nav>	
				</div>
				
			</div><!--/. row -->
		</div><!--/. container -->
	</div>

	<div class="">
		<div class="container">
			<?php $this->renderPartial($data['tpl'],array("data"=>$data));?>
		</div><!--/. container -->
	</div>

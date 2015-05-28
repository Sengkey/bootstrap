			<div class="row">
				<div class="col-md-12">
<?php 
if($data['model']['errors']) {
?>
				<div class="alert alert-danger fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
<?php 
	foreach($data['model']['errors'] AS $error) {
?>
					<?php 
						echo implode("<BR>",array_values($error));
					?>
					<br>
<?php 
	}
?>
				</div>
<?php
}
?>
					<h1>Your Profile</h1>
					<div class="row">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profile-form',
	'htmlOptions'=>array(
		'role'=>'form',
	),
));
echo $form->hiddenField($data['model'],'type',array('value'=>"profile"));
?>
						<div class="col-md-12">
							<h3>Profile Details</h3>
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group">
										<?php 
											echo $form->label($data['model'],"firstname");
											echo $form->textField($data['model'],'firstname',array('class'=>"form-control", "placeholder"=>"Firstname"));
										?>
									</div><!-- form group -->
								</div><!-- col-sm-3 -->
								<div class="col-sm-3">
									<div class="form-group">
										<?php 
											echo $form->label($data['model'],"lastname");
											echo $form->textField($data['model'],'lastname',array('class'=>"form-control", "placeholder"=>"Lastname"));
										?>
									</div><!-- form group -->
								</div><!-- col-sm-3 -->
								<div class="col-sm-3">
									<div class="form-group">
									<?php 
										echo $form->label($data['model'],"country");
										echo $form->DropDownList($data['model'],'country',Yii::app()->params['countries'],array('options'=>array($data['model']['country']=>array('selected'=>'selected')),"class"=>"form-control"));
									?>
									</div><!-- form group -->			
								</div><!-- col-sm-3 -->
								<div class="col-sm-3">
									<div class="form-group">
									<?php 
										echo $form->label($data['model'],"language");
										echo $form->DropDownList($data['model'],'language',Yii::app()->params['languages'],array('options'=>array($data['model']['language']=>array('selected'=>'selected')),"class"=>"form-control"));
									?>
									</div><!-- form group -->			
								</div><!-- col-sm-3 -->
							</div><!-- row -->	
							<div class="row">
								<div class="col-xs-12">
									<p><input type="submit" class="btn btn-primary" value="Submit" /></p>
								</div><!-- col-xs-12 -->
							</div><!-- row -->  
						</div><!-- col-md-12 -->
<?php $this->endWidget();?>
					</div><!-- row -->
					<div class="row">
						<div class="col-md-6">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profileemail-form',
	'htmlOptions'=>array(
		'role'=>'form',
	),
));
echo $form->hiddenField($data['model'],'type',array('value'=>"email"));
?>
							<h3>Email Address <small><?php echo Yii::app()->user->email;?></small></h3>
							<!-- Email start -->
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group">
										<label for="campaignPlan">New Email Address</label>
										<?php 
											echo $form->textField($data['model'],'email',array('class'=>"form-control", "placeholder"=>"name@emailaddress.com"));
										?>
									</div><!-- form group -->
								</div><!-- col-xs-6 -->
								<div class="col-xs-6">
									<div class="form-group">
										<label for="campaignPlan">Re-type Email Address</label>
										<?php 
											echo $form->textField($data['model'],'email1',array('class'=>"form-control", "placeholder"=>"name@emailaddress.com"));
										?>
									</div><!-- form group -->
								</div><!-- col-xs-6 -->
							</div><!-- row -->
							<div class="row">
								<div class="col-xs-12">
									<p><input type="submit" class="btn btn-primary" value="Update Email Address" /></p>
								</div><!-- col-xs-12 -->
							</div><!-- row --> 
							<!-- Email end -->
<?php $this->endWidget();?>
						</div><!-- col-md-6 -->
						<div class="col-md-6">
							<h3>Reset Password</h3>
							<!-- Password start -->
							<div class="row">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'profilepassword-form',
	'htmlOptions'=>array(
		'role'=>'form',
	),
));
echo $form->hiddenField($data['model'],'type',array('value'=>"password"));
?>

								<div class="col-xs-6">
									<div class="form-group">
										<label for="campaignPlan">New Password</label>
										<?php 
											echo $form->passwordField($data['model'],'password',array('class'=>"form-control"));
										?>
									</div><!-- form group -->
								</div><!-- col-xs-6 -->
								<div class="col-xs-6">
									<div class="form-group">
										<label for="campaignPlan">Re-type Password</label>
										<?php 
											echo $form->passwordField($data['model'],'password1',array('class'=>"form-control"));
										?>
									</div><!-- form group -->
								</div><!-- col-xs-6 -->
							</div><!-- row -->
							<div class="row">
								<div class="col-xs-12">
									<p><input type="submit" class="btn btn-primary" value="Update Password" /></p>
								</div><!-- col-xs-12 -->
							</div><!-- row --> 
							<!-- Password end -->
<?php $this->endWidget();?>
						</div><!-- col-md-6 -->
					</div><!-- row // -->
				</div><!--/. col-md-12 -->
			</div><!--/. row -->

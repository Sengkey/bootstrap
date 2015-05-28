<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
    'htmlOptions'=>array(
		'class'=>'form-horizontal',
		'role'=>'form',
    ),
)); ?>
				  <div class="form-group">
				    <?php echo $form->label($data['model'], 'Email', array("for"=>"email", 'class'=>"col-sm-2 control-label")); ?>
				    <div class="col-sm-10">
				      <?php echo $form->textField($data['model'],'email',array('class'=>"form-control", "id"=>"email")); ?>
				    </div>
				  </div>
				  <div class="form-group">
				    <?php echo $form->label($data['model'], 'Password', array("for"=>"password", 'class'=>"col-sm-2 control-label")); ?>
				    <div class="col-sm-10">
				      <?php echo $form->passwordField($data['model'],'password',array('class'=>"form-control", "id"=>"password")); ?>
				    </div>
				  </div>
				  <div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				      <div class="checkbox">
				        <label>
				        	<?php echo $form->checkBox($data['model'], "rememberme",array("id"=>"rememberme")); ?> 
				        	<label for="rememberme">Remember me</label>
				        </label>
				      </div>
				    </div>
				  </div>
				  <div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				      <input type="submit" class="btn btn-primary" value="Sign in" /> 
				    </div>
				  </div>
				  <div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				  		<p><a href="/register">Create a New Account</a><br /><a href="/forgotpassword">Forgot password</a></p>
				  	</div>
				  </div>

<?php $this->endWidget();?>

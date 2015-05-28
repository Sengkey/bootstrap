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
				        	<?php echo $form->checkBox($data['model'], "iagree",array("id"=>"iagree")); ?> 
				        	<label for="iagree">I agree to the <a href="#">terms and conditions</a> and <a href="#">privacy policy</a>.</label>
				        </label>
				      </div>
				    </div>
				  </div>
				  <div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				      <input type="submit" class="btn btn-primary" value="Create Account" />
				    </div>
				  </div>
				  <div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				  		<p>I already have an account. <a href="/login">Sign in</a></p>
				  	</div>
				  </div>
<?php $this->endWidget();?>

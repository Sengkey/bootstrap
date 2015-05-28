<div class="row">
	<div class="col-md-12">
<script type='text/javascript' src='<?=Yii::app()->request->getBaseUrl(true)?>/js/bootstrap-colorpicker.js'></script>
<?php 
/*
<script type='text/javascript' src='<?=Yii::app()->request->getBaseUrl(true)?>/js/wysihtml5-0.3.0.js'></script>	
<script type='text/javascript' src='<?=Yii::app()->request->getBaseUrl(true)?>/js/prettify.js'></script>
<script type='text/javascript' src='<?=Yii::app()->request->getBaseUrl(true)?>/js/bootstrap-wysihtml5.js'></script>	
*/ ?>
<script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/wysihtml5-0.3.0.js"></script>
<script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/prettify.js"></script>
<script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/bootstrap-wysihtml5.js"></script>

<script>
	var templates = new Object();
	var loading = 0;
	var prevTemplate;
	$(function(){
		$('.demo2').colorpicker();

		// Wysihtml5
		$('.textarea').wysihtml5({
			"html": true
		});
		$(prettyPrint);
	});
	function setName(selectedTemplate) {
		if(prevTemplate == 0) {
			var templateData = new Object();
			templateData = {
				id: 0,
				backgroundcolor: $("#CampaigntemplateForm_backgroundcolor").val(),
				blockbordercolor: $("#CampaigntemplateForm_blockbordercolor").val(),
				blockpaddingsize: $("#CampaigntemplateForm_blockpaddingsize").val(),
				captionbackgroundcolor: $("#CampaigntemplateForm_captionbackgroundcolor").val(),
				captiontextcolor: $("#CampaigntemplateForm_captiontextcolor").val(),
				captiontextfont: $("#CampaigntemplateForm_captiontextfont").val(),
				captiontextsize: $("#CampaigntemplateForm_captiontextsize").val(),
				name: $("#CampaigntemplateForm_name").val()
			};
			templates[0] = new Object();
			templates[0] = templateData;
		}
		if(selectedTemplate.value==0) {
			$("#templatename-container").removeClass("hidden");
			if(typeof templates[0] !== 'undefined') {
				setTemplateForm(templates[0]);
			}
		} else {
			$("#templatename-container").addClass("hidden");
			if(typeof templates[selectedTemplate.value] !== 'undefined') {
				setTemplateForm(templates[selectedTemplate.value]);
			} else if(!loading) {
				loading = 1;
				// Load more
				$.ajax({
					type: "POST",
					url: "<?=Yii::app()->request->getBaseUrl(true)?>/ajax",
					data: { 'type':'selecttemplate','templateid':selectedTemplate.value },
					success: function(json) {
						var data = $.parseJSON(json);
						if(data[0]==1) { // no error
							setTemplateForm(data[1]);
						}
						loading=0;
					},
					error: function(resp) {
					}
				});
			}
		}
		prevTemplate = selectedTemplate.value;
	}
	function setTemplateForm(templateData) {
		if(typeof templates[templateData['id']] === "undefined") {
			templates[templateData['id']] = templateData;
		}
		$.each(templateData, function(field, value) {
			if($.inArray(field, ["backgroundcolor","captionbackgroundcolor","captiontextcolor"])!==-1) {
				value = "#"+value;
				$("#CampaigntemplateForm_"+field).siblings(".input-group-addon").children("i").css("background-color",value);
			}
		    $("#CampaigntemplateForm_"+field).val(value);
		});
	}
</script>

<?php /*	
<link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->getBaseUrl(true)?>/js/css/prettify.css"></link>
<link rel="stylesheet" type="text/css" href="<?=Yii::app()->request->getBaseUrl(true)?>/js/css/bootstrap-wysihtml5.css"></link>
*/ ?>
		<h1>Edit Appearence</h1>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'campaigntemplate-form',
	'htmlOptions'=>array(
		'class'=>'form-horizontal',
		'role'=>'form',
	),
)); ?>
		<!-- start -->
				<div class="row">

					<div class="col-md-12">
						<h3><?php echo $data['campaign']['title'];?> <a href="<?=Yii::app()->request->getBaseUrl(true)?>/campaignedit?cid=<?php echo $data['campaign']['cid'];?>" class="btn href btn-xs btn-default"><i class="icon edit"></i>Back to Edit Campaign</a> <span style=""><a href="<?=Yii::app()->request->getBaseUrl(true)?>/<?php echo $data['campaign']['cid'];?>" class="href btn btn-xs btn-default" title="<?=Yii::app()->request->getBaseUrl(true)?>/<?php echo $data['campaign']['cid'];?>" target="_blank"><i class="icon external url"></i>Preview</a></span></h3>
					</div><!--/. col-md-12 -->

					<div class="col-md-12">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="campaignTitle">Theme</label>
									<?php 
									echo $form->DropDownList($data['model'],'id',$data['templates'],array('options'=>array($data['model']['id']=>array('selected'=>'selected')),"class"=>"form-control","onChange"=>"setName(this);")); ?>
								</div><!-- form-group -->
							</div><!--/. col-sm-6 -->
							<div class="col-sm-3">
								<div id="templatename-container" class="form-group <?php echo (!$data['model']['id']) ? "" : "hidden";?> ">
									<?php echo $form->label($data['model'],'name'); ?>
									<?php echo $form->textField($data['model'],"name",array("class"=>"form-control","placeholder"=>"Enter a New Theme Name")); ?>
								</div><!-- form-group -->
							</div><!--/. col-sm-3 -->
							<?php /* <div class="col-sm-1">
								<div class="form-group">
									<label for="campaignTitle">&nbsp;</label>
									<input type="submit" class="form-control btn btn-default" value="Apply">							
								</div><!--/. form-group -->
							</div><!--/. col-sm-6 -->
							*/ ?>
						</div><!--/. row -->
					</div><!--/. col-md-12 -->

					<div class="col-md-12">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<?php echo $form->label($data['model'],'backgroundcolor'); ?>
								<div class="input-group demo2">
									<?php echo $form->textField($data['model'],"backgroundcolor",array("class"=>"form-control")); ?>
									<span class="input-group-addon"><i></i></span>
								</div>
							</div><!-- form-group -->
						</div><!-- col-sm-3 -->
						<div class="col-sm-3">
							<div class="form-group">
								<?php echo $form->label($data['model'],'blockpaddingsize'); ?>
								<?php echo $form->DropDownList($data['model'],'blockpaddingsize',Yii::app()->params['blockpaddingsize'],array('options'=>array($data['model']['blockpaddingsize']=>array('selected'=>'selected')),"class"=>"form-control")); ?>
							</div><!-- form-group -->
						</div>
					</div>	
					</div>	

					<div class="col-md-12">

					<div class="row">
						
						<div class="col-sm-3">
							<div class="form-group">
								<?php echo $form->label($data['model'],'captionbackgroundcolor'); ?>
								<div class="input-group demo2">
									<?php echo $form->textField($data['model'],"captionbackgroundcolor",array("class"=>"form-control")); ?>
									<span class="input-group-addon"><i></i></span>
								</div>
							</div><!-- form-group -->
						</div><!-- col-sm-3 -->
						<div class="col-sm-3">
							<div class="form-group">
								<?php echo $form->label($data['model'],'captiontextcolor'); ?>
								<div class="input-group demo2">
									<?php echo $form->textField($data['model'],"captiontextcolor",array("class"=>"form-control")); ?>
									<span class="input-group-addon"><i></i></span>
								</div>
							</div><!-- form-group -->
						</div><!-- col-sm-3 -->
						<div class="col-sm-3">
							<div class="form-group">
								<?php echo $form->label($data['model'],'captiontextsize'); ?>
								<?php echo $form->DropDownList($data['model'],'captiontextsize',Yii::app()->params['captiontextsize'],array('options'=>array($data['model']['captiontextsize']=>array('selected'=>'selected')),"class"=>"form-control")); ?>
							</div><!-- form-group -->
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<?php echo $form->label($data['model'],'captiontextfont'); ?>
								<?php echo $form->DropDownList($data['model'],'captiontextfont',$data['fonts'],array('options'=>array($data['model']['captiontextfont']=>array('selected'=>'selected')),"class"=>"form-control")); ?>
							</div><!-- form-group -->
						</div><!--/. col-sm-6 -->

						<?php /*
						<div class="col-sm-3">
							<div class="form-group">
								<?php echo $form->label($data['model'], "Block Heading Color", array("for"=>"blockheadingcolor")); ?>
								<div class="input-group demo2">
									<?php echo $form->textField($data['model'],"blockheadingcolor",array("class"=>"form-control","id"=>"blockheadingcolor")); ?>
									<span class="input-group-addon"><i></i></span>
								</div>
							</div><!-- form-group -->
						</div><!-- col-sm-3 -->
						<div class="col-sm-3">
							<div class="form-group">
								<?php echo $form->label($data['model'], "Block Paragraph Color", array("for"=>"blockparagraphcolor")); ?>
								<div class="input-group demo2">
									<?php echo $form->textField($data['model'],"blockparagraphcolor",array("class"=>"form-control","id"=>"blockparagraphcolor")); ?>
									<span class="input-group-addon"><i></i></span>
								</div>
							</div><!-- form-group -->
						</div><!-- col-sm-3 -->
						<div class="col-sm-3">
							<div class="form-group">
								<?php echo $form->label($data['model'], "Block Active Color", array("for"=>"blockactivecolor")); ?>
								<div class="input-group demo2">
									<?php echo $form->textField($data['model'],"blockactivecolor",array("class"=>"form-control","id"=>"blockactivecolor")); ?>
									<span class="input-group-addon"><i></i></span>
								</div>
							</div><!-- form-group -->
						</div><!-- col-sm-3 -->
						*/ ?>
					</div><!-- row -->	
					</div><!--/. col-md-12 -->

					<div class="col-md-12">
						<div class="form-group">
							<p><input type="submit" class="btn btn-primary" value="Submit"></p>
						</div><!-- row -->
					</div><!--/. col-md-12 -->
						
					

					<div class="col-md-12">
						<div class="form-group">
							<label for="campaignTitle">Custom Header (Optional)</label>
							<textarea class="textarea form-control" placeholder="Enter text ..." style="height: 200px"></textarea>
						</div>
					</div><!--/. col-md-12 -->

					<div class="col-md-12">
						<div class="form-group">
							<p><input type="submit" class="btn btn-primary" value="Submit"></p>
						</div><!-- row -->
					</div><!--/. col-md-12 -->

					


				</div><!--/. row -->		
		<!-- end -->
				
	</div><!--/. col-md-12 -->
</div><!--/. row -->

<?php $this->endWidget();?>

<!-- Test Preview START -->
</div><!--/. container -->
<div bgcolor="#ffcc00" style="background:#ffcc00;">

	<div class="container"><!--/. container -->
		<div class="row">

			<h1>HTML PREVIEW HERE</h1>

		</div><!--/. row -->
	</div>

</div><!--/. preview -->
<div class="container">
<!-- Test Preview END -->

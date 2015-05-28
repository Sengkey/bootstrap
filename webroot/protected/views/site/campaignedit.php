<div class="row">
			<div class="col-md-12">
<script type='text/javascript' src='<?=Yii::app()->request->getBaseUrl(true)?>/js/bootstrap-datetimepicker.js'></script>

<script>
$(function() {
	$('#starttimewrapper, #endtimewrapper').datetimepicker({
		starttime: '<?php echo date("Y-m-d H:i",strtotime($data['model']['starttime']));?>',
		format: "dd M yyyy hh:ii",
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
	});

	$('#starttimerulewrapper, #endtimerulewrapper').datetimepicker({
		format: "dd M yyyy hh:ii",
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
	});
	 
	$(".endtimeinfo, .customurlinfo, .specifyusers, .specifyusers_, .allowtags, .allowtags_, .feedtype").popover({
		trigger: 'click',
		html: true
	});
});
</script>


<?php 
if($data['notification']) {
?>
				<div class="alert alert-success fade in">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					Campaign is updated successfully.
				</div>
<?php
}
?>

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

<?php 
if(isset($data['errors'])) {
}
?>

				<h1><?php echo ($data['editcampaign']) ? "Edit Campaign <small>CID:".$data['campaign']['cid']."</small>" : "Add Campaign" ;?></h1>

				

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'campaign-form',
	'htmlOptions'=>array(
		'role'=>'form',
	),
)); ?>

<?php 
if($data['model']['plantype'] == 0) { // Non Upgraded plan start
?>
			<!-- start -->
				<div class="row">
					<div class="col-md-6">
						<h3>Campaign Details <a href="<?=Yii::app()->request->getBaseUrl(true)?>/templateedit?cid=<?php echo $data['campaign']['cid'];?>" class="btn href btn-xs btn-default"><i class="icon bullseye"></i>Edit Appearance</a> <span style=""><a href="<?=Yii::app()->request->getBaseUrl(true)?>/<?php echo $data['campaign']['cid'];?>" class="href btn btn-xs btn-default" title="<?=Yii::app()->request->getBaseUrl(true)?>/<?php echo $data['campaign']['cid'];?>" target="_blank"><i class="icon external url"></i>Preview</a></span></h3>
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<?php 
										echo $form->label($data['model'],"title");
									?>
<?php if($data['editcampaign']) {
?>
									
<?php 
}
?>
									<?php 
										echo $form->textField($data['model'],'title',array('class'=>"form-control", "placeholder"=>"Enter Campaign Title"));
									?>
								 </div>
							</div><!-- col-xs-12 -->
						</div><!-- row -->	
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<?php 
										echo $form->label($data['model'],"starttime");
									?>
									<div class="input-group date" id="starttimewrapper" data-date="<?php echo date("d M y H:i");?>">
										<?php 
											echo $form->textField($data['model'],'starttime',array('class'=>"form-control", "readonly"=>true));
										?>
										<span class="input-group-addon"><i class="icon calendar"></i></span>
									</div>
								</div><!-- form group -->
							</div><!-- col-xs-6 -->
							<div class="col-xs-6">
								<div class="form-group">
								<?php 
									echo $form->label($data['model'],"endtime");
								?>
								<span class="endtimeinfo" data-container="body" data-toggle="popover" data-placement="top" data-content="Leave blank for ongoing campaign."><i class="icon gray info"></i></span>
								<div class="input-group date" id="endtimewrapper">
									<?php 
										echo $form->textField($data['model'],'endtime',array('class'=>"form-control", "readonly"=>true));
									?>
									<span class="input-group-addon"><i class="icon calendar"></i></span>
								</div>
								</div><!-- form group -->
							</div><!-- col-xs-6 -->
						</div><!-- row -->
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<?php 
										echo $form->label($data['model'],"timezone");
										echo $form->DropDownList($data['model'],'timezone',Yii::app()->params['timezone'],array('options'=>array($data['model']['timezone']=>array('selected'=>'selected')),"class"=>"form-control"));
									?>
								</div><!-- form group -->
							</div><!-- col-xs-6 -->
						</div><!-- row -->
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
								<?php 
									echo $form->label($data['model'],"visibility");
									echo $form->DropDownList($data['model'],'visibility',Yii::app()->params['visibility'],array('options'=>array($data['model']['visibility']=>array('selected'=>'selected')),"class"=>"form-control"));
								?>
								</div><!-- form group -->			
							</div><!-- col-xs-6 -->
							<div class="col-xs-6">
<?php 
/*
								<div class="form-group">
								<?php 
									echo $form->label($data['model'],"customurl");
								?>
								<span class="customurlinfo" data-container="body" data-toggle="popover" data-placement="top" data-content="DNS Zone 'A record' must be updated. <a href=''>See examples here</a>."><i class="icon gray info"></i></span>
									<div class="row">
										<div class="col-xs-5 col-sm-4">
											<?php 
												echo $form->DropDownList($data['model'],'https',Yii::app()->params['https'],array('options'=>array($data['model']['https']=>array('selected'=>'selected')),"class"=>"form-control"));
											?>
										</div><!-- col-xs-4 -->
										<div class="col-xs-7 col-sm-8">
											<?php 
												echo $form->textField($data['model'],'customurl',array('class'=>"form-control", "placeholder"=>"www.MyDomain.com"));
											?>
										</div><!-- col-xs-8 -->
									</div><!-- row -->
								</div><!-- form group -->
*/
?>
							</div><!-- col-xs-6 -->
						</div><!-- row -->	 
					</div><!-- col-md-6 -->
					<div class="col-md-6">
						<h3>Plan Details</h3>
						<!-- plan start -->
						<label for="campaignPlan">Upgrade Plan</label>
						<div class="row">
							<div class="col-xs-12">
							<?php 
								echo $form->DropDownList($data['model'],'plantype',Yii::app()->params['plantype'],array('options'=>array($data['model']['plantype']=>array('selected'=>'selected')),"class"=>"form-control"));
							?>
							</div>
						</div><!-- row -->
						<div class="row">
							<div class="col-xs-12">
								<p>
								<dl class="dl-horizontal">
									<dt>Free Trial <small>($0)</small></dt><dd> To pre-configure campaign sources &mdash; One-time per campaign. 50 Feeds limit. Campaign is Private viewing only.</dd>
									<dt>Production <small>($149/month)</small></dt><dd> Campaign can be set to public or private. *Unlimited Feeds.</dd>
									<!-- <dt>Downgrade Fee ($99) </dt><dd> One off charge when you downgrade plan.</dd> -->
									<!-- <dt>Vault <small>($0.70/day)*</small></dt><dd> To preserve all data for future use. Campaign is freezed automatically and Private viewing only.</dd> -->
								</dl>
								</p>
							</div><!-- col-xs-12 -->	
						</div><!-- row -->
					<!-- plan end -->
					</div><!-- col -->
				</div><!-- row -->	

<?php // Non Upgraded plan end 
} else { // Upgraded plan start
?>

				<!-- start -->
				<div class="row">
					<div class="col-md-6">
						<h3>Campaign Details</h3>
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<?php 
										echo $form->label($data['model'],"title");
									?>
									<span style=""> &nbsp;&nbsp;&nbsp;<a class="href btn btn-xs btn-default" title="http://254675.beepond.com"><i class="icon external url"></i>View</a></span>
									<?php 
										echo $form->textField($data['model'],'title',array('class'=>"form-control", "placeholder"=>"Enter Campaign Title"));
									?>
								 </div>
							</div><!-- col-xs-12 -->
						</div><!-- row -->
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<?php 
										echo $form->label($data['model'],"starttime");
									?>
									<div class="input-group date" id="starttimewrapper" data-date="<?php echo date("d M y H:i");?>">
										<?php 
											echo $form->textField($data['model'],'starttime',array('class'=>"form-control", "readonly"=>true));
										?>
										<span class="input-group-addon"><i class="icon calendar"></i></span>
									</div>
								</div><!-- form group -->
							</div><!-- col-xs-6 -->
							<div class="col-xs-6">
								<div class="form-group">
								<?php 
									echo $form->label($data['model'],"endtime");
								?>
								<span class="endtimeinfo" data-container="body" data-toggle="popover" data-placement="top" data-content="Leave blank for ongoing campaign."><i class="icon gray info"></i></span>
								<div class="input-group date" id="endtimewrapper">
									<?php 
										echo $form->textField($data['model'],'endtime',array('class'=>"form-control", "readonly"=>true));
									?>
									<span class="input-group-addon"><i class="icon calendar"></i></span>
								</div>
								</div><!-- form group -->
							</div><!-- col-xs-6 -->
						</div><!-- row -->
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<?php 
										echo $form->label($data['model'],"timezone");
										echo $form->DropDownList($data['model'],'timezone',Yii::app()->params['timezone'],array('options'=>array($data['model']['timezone']=>array('selected'=>'selected')),"class"=>"form-control"));
									?>
								</div><!-- form group -->
							</div><!-- col-xs-6 -->
						</div><!-- row -->
					</div><!-- col-md-6 -->
					<div class="col-md-6">
						<h3>Plan Details</h3>
						<!-- plan start -->
						<label for="campaignPlan">Current Plan</label>
						<div class="row">
							<div class="col-xs-12">
							<div class="form-group">
								<div class="form-control"><?php echo Yii::app()->params['plantype'][$data['model']['plantype']];?></div>
							</div><!-- form group -->
							</div><!-- col-xs-12 -->
						</div><!-- row -->
					<!-- plan end -->
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
								<?php 
									echo $form->label($data['model'],"visibility");
									echo $form->DropDownList($data['model'],'visibility',Yii::app()->params['visibility'],array('options'=>array($data['model']['visibility']=>array('selected'=>'selected')),"class"=>"form-control"));
								?>
								</div><!-- form group -->			
							</div><!-- col-xs-6 -->
							<div class="col-xs-6">
<?php 
/*
								<div class="form-group">
								<?php 
									echo $form->label($data['model'],"customurl");
								?>
								<span class="customurlinfo" data-container="body" data-toggle="popover" data-placement="top" data-content="DNS Zone 'A record' must be updated. <a href=''>See examples here</a>."><i class="icon gray info"></i></span>
									<div class="row">
										<div class="col-xs-5 col-sm-4">
											<?php 
												echo $form->DropDownList($data['model'],'https',Yii::app()->params['https'],array('options'=>array($data['model']['https']=>array('selected'=>'selected')),"class"=>"form-control"));
											?>
										</div><!-- col-xs-4 -->
										<div class="col-xs-7 col-sm-8">
											<?php 
												echo $form->textField($data['model'],'customurl',array('class'=>"form-control", "placeholder"=>"www.MyDomain.com"));
											?>
										</div><!-- col-xs-8 -->
									</div><!-- row -->
								</div><!-- form group -->
*/
								?>
							</div><!-- col-xs-6 -->
						</div><!-- row -->	 
					</div><!-- col -->
				</div><!-- row -->

<?php 
} // Upgraded plan end
?>				
				<div class="row">
					<div class="col-xs-12">
						<p><input type="submit" class="btn btn-primary" value="Submit"></p>
					</div><!-- col-xs-12 -->
				</div><!-- row -->
<!-- end -->
<?php $this->endWidget();?>


<?php
if($data['editcampaign']==1) {
?>

<script type='text/javascript' src='<?=Yii::app()->request->getBaseUrl(true)?>/js/bootstrap-tagsinput.js'></script>
<script>
	var validating=0;
	$(function() {
		// $("select[multiple][data-role=tagsinput]").tagsinput({
			// tagClass: 'big'
		// });
		$("input[name=publicpost]").each(function() {
			$(this).change(function() {
				toggleUserpost(this, $(this).val());
				if($(this).attr('id').replace("userpost","").replace("publicpost","") == "ig") { // Instagram API does not provide time constraint for public images
					if($(this).val()==1) {
						$(".igmsg").show();
						$(".igtime").hide();
					} else if($(this).val()==0) {
						$(".igmsg").hide();
						$(".igtime").show();
					}
				}
			});
		});
		$('textarea.usertags').tagsinput({
			confirmKeys: [13, 32, 44], // enter, space, comma
			itemValue: function(item) {
				if(item.id) {
					return item.id;
				} else {
					return item;
				}
			},
			itemText: 'text',
			tagClass: function(item) {
				return (item.length > 10 ? 'big' : 'small');
			}
		});
		$('textarea[data-role=tagsinput]').tagsinput({
			confirmKeys: [13, 32, 44], // enter, space, comma
			tagClass: function(item) {
				return (item.length > 10 ? 'big' : 'small');
			}
		});
		// FB only
		$('#fb textarea.usertags').on('beforeItemAdd', function(event) {
			var thisobj = $(this);
			if(typeof event.item.id == 'undefined') {
				if(validating == 0) {
					validating = 1;
					$.ajax({
						type: "POST",
						url: "ajax",
						data: { 'type':'getsocialuser', 'source':"fb", 'username': event.item },
						success: function(json) {
							data = jQuery.parseJSON(json);
							validating = 0;
							if(data[0]==1) {
								$(thisobj).tagsinput('add', { id: data[1], text: event.item });
							}
						},
						error: function(resp) {
						}
					});
				}
				event.cancel = true;
			} else {
			}
		});
		// TW only
		$('#tw textarea.usertags').on('beforeItemAdd', function(event) {
			var thisobj = $(this);
			if(typeof event.item.id == 'undefined') {
				if(validating == 0) {
					validating = 1;
					$.ajax({
						type: "POST",
						url: "ajax",
						data: { 'type':'getsocialuser', 'source':"tw", 'username': event.item },
						success: function(json) {
							console.log(json);
							data = jQuery.parseJSON(json);
							validating = 0;
							if(data[0]==1) {
								$(thisobj).tagsinput('add', { id: data[1], text: event.item });
							}
						},
						error: function(resp) {
						}
					});
				}
				event.cancel = true;
			} else {
			}
		});
		// IG only
		$('#ig textarea.usertags').on('beforeItemAdd', function(event) {
			$("#ig .alert-danger .errors").html("");
			$("#ig .alert-danger").slideUp();
			$("#ig .alert-success").slideUp();
			var thisobj = $(this);
			if(typeof event.item.id == 'undefined') {
				if(validating == 0) {
					validating = 1;
					$.ajax({
						type: "POST",
						url: "ajax",
						data: { 'type':'getsocialuser', 'source':"ig", 'username': event.item },
						success: function(json) {
							data = jQuery.parseJSON(json);
							validating = 0;
							if(data[0]==1) {
								$(thisobj).tagsinput('add', { id: data[1], text: event.item });
							} else {
								var errors = data[1].join("<BR>");
								$("#ig .alert-danger .errors").append(errors);
								$("#ig .alert-danger").slideDown();
							}
						},
						error: function(resp) {
						}
					});
				}
				event.cancel = true;
			} else {
			}
		});
		$('textarea[data-role=tagsinput]').on('itemAdded', function(event) {
			var wtf    = $(this).parent().find('.tagsinput-box');
			var height = wtf[0].scrollHeight;
			wtf.scrollTop(height);
		});
	});
	function toggleUserpost(obj, val) {
		if(val == 0) {
			$(obj).parents(".panel-body").find(".userpost").show();
		} else {
			$(obj).parents(".panel-body").find(".userpost").hide();
		}
	}
	function handleData(json, type) {
		data = $.parseJSON(json);
		$("#"+type+" .alert-danger .errors").html("");
		$("#"+type+" .alert-danger").slideUp();
		$("#"+type+" .alert-success").slideUp();
		if(!data[0]) { // has errors
			var errors = data[1].join("<BR>");
			$("#"+type+" .alert-danger .errors").append(errors);
			$("#"+type+" .alert-danger").slideDown();
		} else { // success
			$("#"+type+" .alert-success").slideDown();
			$("#"+type+" .alerts").append(data[1]['html']);
		}
	}
	function pasteRule(ruleid, source) {
		$.ajax({
			type: "POST",
			url: "ajax",
			data: { 'type':'pasterule', 'ruleid':ruleid, 'source': source },
			success: function(json) {
				data = jQuery.parseJSON(json);
				if(data[0]==1) {
					var rule = data[1];
					resetForm(source);

					$("#"+source+" input[name=publicpost][value="+rule['publicpost']+"]").prop('checked', true);

					if(rule['publicpost']==0) { // specific users
						$.each( rule['allowuser'], function( index, user ) {
							$("#"+source+" textarea[name=allowuser]").tagsinput('add', user);
						});

						$.each( rule['blockuser'], function( index, user ) {
							$("#"+source+" textarea[name=blockuser]").tagsinput('add', user);
						});
						if(source == "ig") { // Instagram API does not provide time constraint for public images
							$(".igmsg").hide();
							$(".igtime").show();
						}
					} else {
						$(".igmsg").show();
						$(".igtime").hide();
					}
					toggleUserpost($("#"+source+" input[name=publicpost]"), rule['publicpost']);

					if(rule['starttime']) {
						$("#"+source+" input[name=starttime]").val(rule['starttime']);
					} else {
						$("#"+source+" input[name=starttime]").val("");
					}

					if(rule['endtime']) {
						$("#"+source+" input[name=endtime]").val(rule['endtime']);
					} else {
						$("#"+source+" input[name=endtime]").val("");
					}

					$.each( rule['allowtag'], function( index, tag ) {
						$("#"+source+" textarea[name=allowtag]").tagsinput('add', tag);
					});

					$.each( rule['blocktag'], function( index, tag ) {
						$("#"+source+" textarea[name=blocktag]").tagsinput('add', tag);
					});

					if(rule['istext']==1) {
						$("#"+source+" input[name=istext]").attr('checked','checked');
					} else {
						$("#"+source+" input[name=istext]").removeAttr('checked');
					}
					if(rule['isimage']==1) {
						$("#"+source+" input[name=isimage]").attr('checked','checked');
					} else {
						$("#"+source+" input[name=isimage]").removeAttr('checked');
					}
					if(rule['isvideo']==1) {
						$("#"+source+" input[name=isvideo]").attr('checked','checked');
					} else {
						$("#"+source+" input[name=isvideo]").removeAttr('checked');
					}
				}
			},
			error: function(resp) {
		// 		searching=0;
			}
		});
	}
	function resetForm(source) {
		$("#"+source+" input[name=publicpost][value=1]").prop('checked', true);
		toggleUserpost($("#"+source+" input[name=publicpost]"), 1);
		$("#"+source+" textarea[name=allowuser]").tagsinput('removeAll');
		$("#"+source+" textarea[name=blockuser]").tagsinput('removeAll');
		$("#"+source+" textarea[name=allowtag]").tagsinput('removeAll');
		$("#"+source+" textarea[name=blocktag]").tagsinput('removeAll');
		$("#"+source+" input[name=istext]").attr('checked',"checked");
		$("#"+source+" input[name=isimage]").attr('checked',"checked");
		$("#"+source+" input[name=isvideo]").removeAttr('checked');
		$("#"+source+" input[name=starttime]").val("");
		$("#"+source+" input[name=endtime]").val("");
	}
	function openDeleteRuleModal(ruleid, source, obj) {
		var rule = obj.clone();
		$(rule).find("button").remove();
		$("#modalRule .modal-rule").html("");
		$("#modalRule .modal-rule").removeAttr("id");
		$("#modalRule input[name='deletetext']").val("");
		$("#modalRule .modal-rule").append($(rule));
		$("#modalRule .modal-rule").attr("id",ruleid);
	}
	function deleteRule() {
		if($("#modalRule input[name='deletetext']").val() == "DELETE") {
			var ruleid = $("#modalRule .modal-rule").attr("id");
			$.ajax({
				type: "POST",
				url: "ajax",
				data: { 'type':'deleterule', 'ruleid':ruleid },
				success: function(json) {
					data = jQuery.parseJSON(json);
					if(data[0]==1) {
						$("#rule"+ruleid).slideUp();
					}
				},
				error: function(resp) {
				}
			});
			$("#modalRule").modal('hide');
		} else {
			$("#modalRule input[name='deletetext']").val("");
		}
	}
	function toggleSwitch(source, cid) {
		$.ajax({
			type: "POST",
			url: "ajax",
			data: { 'type':'toggleswitchcampaign', 'source':source, 'cid': cid },
			success: function(json) {
				console.log(json);
				// data = jQuery.parseJSON(json);
				// if(data[0]==1) {

				// }
			},
			error: function(resp) {
			}
		});
	}
</script>
				<h3>Social Media Sources</h3>

				<!-- accordeon -->
				<div class="panel-group" id="accordion">

<?php 
// foreach(Yii::app()->params['campaignsource'] AS $sourceid=>$source) {
// 	$this->renderPartial("/site/campaignsource",array("data"=>$data));
// }
	$this->renderPartial("/site/campaignsourcefb",array("data"=>$data));
	$this->renderPartial("/site/campaignsourcetw",array("data"=>$data));
	$this->renderPartial("/site/campaignsourceig",array("data"=>$data));
?>

				</div>
				<!-- accordeon end -->

				<br /><br /><br /><br /><br />
				<!-- Delete // -->
					<div class="panel panel-default">
						<div class="panel-body">
						<p>This means all data will be <b>erased permanently</b> and cannot be restored.</p>
						<button type="submit" class="btn btn-warning"><i class="icon icon-large warning"></i> DELETE FOREVER <i class="icon icon-large warning"></i></button>
						</div>
					</div>
				</div>
<?php
}
?>
			</div><!--/. row -->

<!-- Modal -->
<div class="modal fade" id="modalRule" tabindex="-1" role="dialog" aria-labelledby="modalRuleLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="modalRuleLabel">Confirm Deletion</h4>
			</div>
			<form onSubmit="return false;">
				<div class="modal-body">
					<h3>Are you sure you want to delete this rule?</h3>
					<span class="modal-rule"></span>
					<input type="hidden" name="campaignruleid" value="">
					<label><b>Type DELETE to confirm</b></label>
					<input type="text" name="deletetext" class="form-control" />
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary" onClick="deleteRule();">Delete</button>
				</div>
			</form>
		</div>
	</div>
</div>

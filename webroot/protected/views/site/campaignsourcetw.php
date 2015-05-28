
<?php 
$form=$this->beginWidget("CActiveForm", array(
	"id"=>"twitter-form",
	"htmlOptions"=>array(
		"class"=>"form-tw",
	)
)); ?>
				<div class="panel panel-default">
					<div class="panel-heading">
					<h4 class="panel-title">
						<div class="switch">
							<input type="checkbox" id="tw_switch" onClick="toggleSwitch('tw',<?php echo $data['campaign']['id'];?>)" <?php echo (isset($data['campaignsource']['tw']) && $data['campaignsource']['tw']==1) ? "checked" : "";?>>
							<label for="tw_switch"><i class="icon circular inverted small off"></i></label>
						</div> 
						<a data-toggle="collapse" data-parent="#accordiontw" href="#tw">
							<i class="icon icontw"></i>Twitter
						</a>
					</h4>
					</div>
					<div id="tw" class="panel-collapse collapse">
						<div class="panel-body">

							<div class="alerts">
<?php 
if(isset($data['campaignrule']['tw'])) {
	echo $data['campaignrule']['tw'];
}
?>
							</div>

							<div class="alert alert-danger" style="display:none">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<span class="errors"></span>
							</div>
							<div class="alert alert-success" style="display:none">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								Rule has been saved successfully.
							</div>

							<div class="col-sm-12">
								<h4>Add New Rule</h4>
								<div class="radio">
								<label for="publicposttw">
									<input type="radio" name="publicpost" id="publicposttw" class="publicpost" value="1" checked>
									Get data from <b>all public posts</b>.
								</label>
								</div>
								<div class="radio">
								<label for="userposttw">
									<input type="radio" name="publicpost" id="userposttw" class="publicpost" value="0">
									Get data from <b>specific users / pages</b>.
								</label>
								</div>
							</div>

							<div class="userpost" style="display:none">
								<div class="col-sm-6 allowtag">
									<h4><small class="label label-success">Allow</small> Specify Users 
										<span class="specifyusers" data-container="body" data-toggle="popover" data-placement="top" data-content="Specify Users"><i class="icon href gray info"></i></span></h4>
									<textarea class="form-control usertags" name="allowuser" data-role="tagsinput" rows="3" placeholder="http://www.facebook.com/user_or_page"></textarea>
								</div>
								<div class="col-sm-6 blocktag">
									<h4><small class="label label-danger">Block</small> Specify Users <span class="specifyusers_" data-container="body" data-toggle="popover" data-placement="top" data-content="Block Users"><i class="icon href gray info"></i></span></h4>
									<textarea class="form-control usertags" name="blockuser" data-role="tagsinput" rows="3" placeholder="http://www.facebook.com/user_or_page"></textarea>
								</div>
							</div>

							<div class="col-sm-6 allowtag">
								<h4><small class="label label-success">Allow</small> Tags <span class="allowtags" data-container="body" data-toggle="popover" data-placement="top" data-content="Allow Tags"><i class="icon href gray info"></i></span></h4>
								<textarea class="form-control" name="allowtag" data-role="tagsinput" rows="3" placeholder="tag1, tag2"></textarea>
							</div>
							<div class="col-sm-6 blocktag">
								<h4><small class="label label-danger">Block</small> Tags <span class="allowtags_" data-container="body" data-toggle="popover" data-placement="top" data-content="Block Tags"><i class="icon href gray info"></i></span></h4>
								<textarea class="form-control" name="blocktag" data-role="tagsinput" rows="3" placeholder="tag1, tag2"></textarea>
							</div>

							<div class="col-sm-6"><div class="row">
								<div class="col-xs-6">
									<div class="form-group">
										<label for="starttime">Start Time</label>
										<div class="input-group date" id="starttimerulewrapper">
											<input type="text" name="starttime" class="form-control" readonly />
											<span class="input-group-addon"><i class="icon calendar"></i></span>
										</div>
									</div><!-- form group -->
								</div><!-- col-xs-6 -->
								<div class="col-xs-6">
									<div class="form-group">
									<label for="endtime">End Time</label>
									<span class="endtimeinfo" data-container="body" data-toggle="popover" data-placement="top" data-content="Leave blank for ongoing campaign."><i class="icon href gray info"></i></span>
									<div class="input-group date" id="endtimerulewrapper">
										<input type="text" name="endtime" class="form-control" readonly />
										<span class="input-group-addon"><i class="icon calendar"></i></span>
									</div>
									</div><!-- form group -->
								</div><!-- col-xs-6 -->
							</div></div><!-- row & col-sm-6 -->

							<div class="col-sm-6"><div class="row"><div class="col-xs-12">
								<div class="form-group">
									<label>Feed Type <span class="feedtype" data-container="body" data-toggle="popover" data-placement="top" data-content="Feed Type"><i class="icon href gray info"></i></span></label><br />
									<div class="feedtypegroup">
									  	
										<input type="checkbox" name="istext" id="istext_tw" value="1" checked> 
										<label for="istext_tw" class="btn btn-default istext"><i class="icon font"></i> Text</label>
										
										<input type="checkbox" name="isimage" id="isimage_tw" value="2" checked> 
										<label for="isimage_tw" class="btn btn-default isimage"><i class="icon photo"></i> Image</label>
										
										<input type="checkbox" name="isvideo" id="isvideo_tw" value="3"> 
										<label for="isvideo_tw" class="btn btn-default isvideo"><i class="icon video"></i> Video</label>

									</div>
								</div><!-- form group -->
							</div></div></div><!-- row & col-sm-6 -->

							<div class="col-sm-12"><div class="form-group">
								<br />
									<input type="hidden" name="type" value="campaignrule" />
									<input type="hidden" name="source" value="tw" />
									<input type="hidden" name="cid" value="<?php echo $_GET['cid'];?>" />
									<?php echo CHtml::ajaxSubmitButton("Add Rule",CHtml::normalizeUrl(array("site/ajax","render"=>true)),
										array(
											"type"=>"POST",
											"success"=>"function(data) {
												console.log(data);
												handleData(data, 'tw');
											}"
										),array("class"=>"href btn btn-primary"))
									?>
									<button type="button" class="href btn btn-default" onClick="resetForm('tw');">Reset</button>
							
							</div></div><!-- row & col-sm-12 -->	

						</div>
					</div>
				</div>
<?php $this->endWidget(); ?>
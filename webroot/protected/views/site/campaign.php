			<div class="row">
				<div class="col-md-12">
				<h1>Campaign List</h1>
				<!-- <h3>Recent Campaigns</h3> -->
					<table class="table table-striped table-responsive">
						<thead>
						<tr>
							<th>#</th>
							<th>Campaign Title</th>
							<th>Visibility</th>
							<th>Sources</th>
							<th>Feeds</th>
						</tr>
						</thead>
						<tbody>
<?php 
if(count($data['campaigns'])) {
	$i=1;
	foreach($data['campaigns'] AS $campaign) {
		// visibility
		if($campaign['visibility']) {
			$visibilityname = "Public";
			$visibilityclass = "success";
		} else {
			$visibilityname = "Private";
			$visibilityclass = "primary";		
		}

		// schedule
		$scheduled = 0;
		if($campaign['starttime'] > time()) {
			$scheduled = 1;
		}
?>
							<tr class="active_">
								<td><?php echo $i;?></td>
								<td>
									<b><a href="<?=Yii::app()->request->getBaseUrl(true)?>/campaignedit?cid=<?php echo $campaign['cid'];?>">
										<?php echo $campaign['title'];?>
									</a></b>
									&nbsp; <a href="<?=Yii::app()->request->getBaseUrl(true)?>/<?php echo $campaign['cid'];?>" class="href btn btn-xs btn-default" target="_blank"><i class="icon external url"></i>Preview</a>
									<br>
									<small>
										From <?php echo date("d M Y",$campaign['starttime']);?> 
										<?php if($campaign['endtime']) { ?>
											to <?php echo date("d M Y",$campaign['endtime']);?>
										<?php } ?>
									</small>
								</td>
								<td>
									<span class="label label-<?php echo $visibilityclass;?>"><?php echo $visibilityname;?></span>
<?php 
		if($scheduled) {
?>
									<span class="label label-warning">Scheduled</span>
<?php
		}
?>
								</td>
								<td><i class="icon gray iconfb"></i> 
									<i class="icon gray icontw"></i> 
									<i class="icon gray iconig"></i>
									<i class="icon gray iconpt"></i>
									<i class="icon gray icontr"></i>
									<i class="icon gray iconyt"></i> 
								</td>
								<td><a href="<?=Yii::app()->request->getBaseUrl(true)?>/feeds?cid=<?php echo $campaign['cid'];?>" class="href btn btn-xs btn-default"><i class="icon list"></i><?php echo $campaign['feeds'];?></a></td>
							</tr>
<?php
		$i++;
	}
} else {
?>
							<tr>
								<td colspan="5">
									You have no campaign yet
								</td>
							</tr>
<?php
}
?>

						</tbody> 
					</table>
					<p><a href="<?=Yii::app()->request->getBaseUrl(true)?>/campaignedit" class="btn btn-primary">Add New Campaign</a></p>
				</div>	
			</div><!--/. row -->

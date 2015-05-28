
	<script>
		$(function() {
			$(".showmore").each(function() {
				$(this).click(function() {
					$(this).siblings(".ellipsis").remove();
					$(this).siblings(".more-text").removeClass("hidden");
					$(this).remove();
				});
			});
		});
		function toggleSwitch(obj,feedid) {
			var checked=0;
			var statuscheck = $(obj).siblings("._feedswitch");
			if($(statuscheck).attr("checked") == "checked") {
				checked=0;
			} else {
				checked=1;
			}
			if(!$(obj).hasClass("ajaxprocess")) {
				$(obj).addClass("ajaxprocess");
				$(obj).children(".feedstatus").addClass("loading").removeClass("feedstatus");
				$.ajax({
					type: "POST",
					url: "ajax",
					data: { 'type':"feedstatusswitch",'feedid':feedid,'cid':"<?php echo $data['campaign']['cid'];?>",'checked':checked },
					success: function(json) {
						data = jQuery.parseJSON(json);
						$(obj).removeClass("ajaxprocess");
						if(data[0]==1) {
							if(checked==1) {
								$(statuscheck).attr("checked","checked");
							} else {
								$(statuscheck).removeAttr("checked");
							}
						}
						$(obj).children(".loading").addClass("feedstatus").removeClass("loading");
					},
					error: function(resp) {
					}
				});
			}
		}
	</script>

			<div class="row">
				<div class="col-md-12">
				<h1><?php echo $data['campaign']['title'];?> <small>CID:<?php echo $data['campaign']['cid'];?></small></h1>
<!-- 				<dl class="dl-horizontal">
					<dt>Status:</dt><dd> <span class="label label-success">Active</span> <a class="href btn btn-xs btn-default"><i class="icon edit"></i>Edit</a></dd> 
					<dt>Start Date:</dt><dd> 25 Oct 2014</dd>
					<dt>End Date:</dt><dd> 25 Jan 2015</dd>
					<dt>Custom URL:</dt><dd> http://www.my-domain.com <a class="href btn btn-xs btn-default"><i class="icon external url"></i>View</a></dd>
					<dt>Plan:</dt><dd> Production &mdash; $37.50 (this month)</dd>
				</dl>
 -->				<!-- Pagination start // -->
					<div class="row">
						
						<div class="col-md-6">
							<p class="">
								<ul class="pagination">
<?php 
	$pagination="";
	$totalPages = ceil($data['totalCampaignNumber'] / Yii::app()->params['elementsPerPage']);
	$edgespanNumber = 3;
	$spanNumber = 1;
	$blankflag=0;
	$active = "";

	// <<
	if($data['pageNum'] > 1) {
		$pagination .= "<li><a href='".Yii::app()->request->getPathInfo()."?cid=".$data['campaign']['cid']."&p=".($data['pageNum']-1)."'>&laquo;</a></li>";
	} else {
		$pagination .= "<li class='disabled'><a>&laquo;</a></li>";
	}

	$pageNumbers = array();
	for($p=1;$p<=$totalPages;$p++) {
		if($p==$data['pageNum']) {
			$paginationhtml = "<li class='active'><a>".$p."</a></li>";
		} else {
			$paginationhtml = "<li><a href='".Yii::app()->request->getPathInfo()."?cid=".$data['campaign']['cid']."&p=".$p."'>".$p."</a></li>";
		}
		if($p <= $edgespanNumber || // the first 3 numbers
			$totalPages - $edgespanNumber < $p || // the last 3 numbers
			($data['pageNum']-$spanNumber <= $p && $p <= $data['pageNum']+$spanNumber)) { // numbers around current page
			$blankflag=0;
			$pageNumbers[$p] = 1;
			$pagination .= $paginationhtml;
		} else {
			if(!$blankflag) {
				$pagination .= "<li class='disabled'><a>...</a></li>";
				$blankflag=1;
			}
			$pageNumbers[$p] = 0;
		}
	}

	// >>
	if($data['pageNum'] < $totalPages) {
		$pagination .= "<li><a href='".Yii::app()->request->getPathInfo()."?cid=".$data['campaign']['cid']."&p=".($data['pageNum']+1)."'>&raquo;</a></li>";
	} else {
		$pagination .= "<li class='disabled'><a>&raquo;</a></li>";
	}
?>
									<?php echo $pagination;?>
								</ul>
							</p>
						</div>
						<div class="col-md-6">
							<p>
							<div class="row">
								<form method="GET">
									<input type="hidden" name="cid" value="<?php echo $data['campaign']['cid'];?>">
									<div class="col-xs-5">
<?php 
	$sourcearray = array();
	$sourcearray = array_merge($sourcearray, array(""=>"All Sources"));
	foreach(Yii::app()->params['campaignsource'] AS $k=>$v) {
		$sourcearray = array_merge($sourcearray, array($k=>ucfirst($v)));
	}
	echo CHtml::DropDownList("feedsource",$data['feedsource'],$sourcearray,array("class"=>"form-control"));
?>


										</select>
									</div>
									<div class="col-xs-5">
										<input type="text" name="q" class="form-control" id="campaignTitle" placeholder="Keyword" value="<?php echo $data['q'];?>">
									</div>
									<div class="col-xs-2">
										<input type="submit" class="btn btn-primary" value="Filter" />
									</div>
								</form>
							</p>
							</div><!-- row -->
						</div>

					</div><!--/. row -->
					<!-- Pagination end // -->
				
					<table class="table table-hover">
						<thead>
						<tr>
							<th>#</th>
							<th></th>
							<th>Feeds Title</th>
							<th>Date</th>
							<th>Sources</th>
							<th>Action</th>
							<!-- <th>Origin</th> -->
						</tr>
						</thead>
			  			<tbody>
<?php 
$i=1 + (Yii::app()->params['elementsPerPage'] * ($data['pageNum']-1));
foreach($data['feed'] AS $feed) {
	if(strlen($feed['desc']) > 120) {
		$feed['desc'] = substr_replace($feed['desc'], "<span class='ellipsis'> … </span><a class='href showmore' style='display:inline-block'>Show more &raquo;</a><span class='more-text hidden'>", 100, 0) . "</span>";
	}
?>
			  				<tr>
								<td><?php echo $i;?></td>
								<td>
									<a href="<?php echo $feed['imgurl'];?>" target="_blank">
										<img src="<?php echo $feed['imgurl'];?>" width="30" height="30" />
									</a>
								</td>
			  					<td class="feeddesc">
			  						<?php echo $feed['desc'];?>
<!-- 			  						 &nbsp;&nbsp; 
			  						<a class="href btn btn-xs btn-default"><i class="icon external url"></i>View</a>
 -->			  					</td>
			  					<td><?php echo date("d/m/y",$feed['time']);?></td>
			  					<td>
			  						<i class="icon origin<?php echo $feed['feedsource'];?>"></i> <a href="<?php echo $feed['link'];?>" class="href btn btn-xs" target="_blank"><i class="icon external url"></i></a>
			  					</td>
			  					<td>
			  						<div class="feedswitch">
										<input type="checkbox" id="_switch<?php echo $feed['_id'];?>" class="_feedswitch" <?php echo ($feed['status']==1) ? "checked" : "";?>>
										<label for="_switch<?php echo $feed['_id'];?>" class="feedstatusswitch" onClick="toggleSwitch($(this),'<?php echo $feed['_id'];?>');"><i class="icon feedstatus"></i></label>
									</div>
			  					</td>
			  				</tr>
<?php 
	$i++;
}
?>
<!-- 			  				<tr>
								<td>1</td>
			  					<td><b>Mercedes Fashion Week</b> is on super panjang banget sampe ga bisa cukup tempatnya panjang banget sampe ga bisa cukup tempatnya ... &nbsp;&nbsp; <a class="href btn btn-xs btn-default"><i class="icon external url"></i>View</a></td>
			  					<td>10/10/2014</td>
			  					<td><i class="icon gray fbook"></i> <a class="href btn btn-xs "><i class="icon external url"></i></a></td>
			  				</tr>
		  					<tr>
								<td>2</td>
			  					<td>Mercedes Fashion Week is on &nbsp;&nbsp; <a class="href btn btn-xs btn-default"><i class="icon external url"></i>View</a></td>
			  					<td>10/10/2014</td>
			  					<td><i class="icon gray fbook"></i> <a class="href btn btn-xs "><i class="icon external url"></i></a></td>
			  				</tr>
			  				<tr>
								<td>3</td>
			  					<td>Mercedes Fashion Week is on &nbsp;&nbsp; <a class="href btn btn-xs btn-default"><i class="icon external url"></i>View</a></td>
			  					<td>10/10/2014</td>
			  					<td><i class="icon gray fbook"></i> <a class="href btn btn-xs "><i class="icon external url"></i></a></td>
			  				</tr>
			  				<tr>
								<td>4</td>
			  					<td>Mercedes Fashion Week is on &nbsp;&nbsp; <a class="href btn btn-xs btn-default"><i class="icon external url"></i>View</a></td>
			  					<td>10/10/2014</td>
			  					<td><i class="icon gray fbook"></i> <a class="href btn btn-xs "><i class="icon external url"></i></a></td>
			  				</tr> -->
			  			</tbody> 
					</table>

					<!-- Pagination start // -->
					<div class="row">
						
						<div class="col-md-6">
							<p class="">
								<ul class="pagination">
									<?php echo $pagination;?>
								</ul>
							</p>
						</div>
						<div class="col-md-6">
							<p>
							<div class="row">
								<form method="GET">
									<input type="hidden" name="cid" value="<?php echo $data['campaign']['cid'];?>">
									<div class="col-xs-5">
<?php 
	echo CHtml::DropDownList("feedsource",$data['feedsource'],$sourcearray,array("class"=>"form-control"));
?>


										</select>
									</div>
									<div class="col-xs-5">
										<input type="text" name="q" class="form-control" id="campaignTitle" placeholder="Keyword" value="<?php echo $data['q'];?>">
									</div>
									<div class="col-xs-2">
										<input type="submit" class="btn btn-primary" value="Filter" />
									</div>
								</form>
							</p>
							</div><!-- row -->
						</div>

					</div><!--/. row -->
					<!-- Pagination end // -->
				</div>	
			</div><!--/. row -->

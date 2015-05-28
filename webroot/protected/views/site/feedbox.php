<?php 
foreach($data['feed'] AS $feed) {
?>

			<div class="post-box col-lg-2 col-md-3 col-sm-4 col-xs-6">
				<div class="bee-node" onClick="openModal('<?php echo $feed['_id'];?>');"> 
					<div class="tile-user-info clearfix" style="position:absolute;top:-75px;left:0px;">
						<img src="<?php echo $feed['userimgurl'];?>" class="tile-avatar-profile-image" alt="<?php echo $feed['username'];?>'s Avatar" width="100%" height="100%">
						<div class="tile-user ">
							<i class="icon origin<?php echo $feed['feedsource'];?>"></i> <div class="tile-user-name"><div class="ellipsis_text"><?php echo $feed['username'];?></div></div>
							<!-- &middot; --> <div class="tile-timestamp" data-source-created-at="<?php echo $feed['time'];?>"><?php echo date("d M Y",$feed['time']);?></div>
						</div>
					</div><!-- //. tile-user-info-->

<?php
	$imgexist=0;
	if(isset($feed['imgurl'])) {
		$imgexist=1;
		$imgresponsivesize="";
		$imagedimensionratio = 1;
		if(isset($feed['imgw']) && isset($feed['imgh']) && $feed['imgw'] && $feed['imgh']) {
			$imagedimensionratio = $feed['imgw'] / $feed['imgh'];
		}
		if(2.2 < $imagedimensionratio) {
			$imgresponsivesize = " img-responsive-lg";
		} else {
			$imgresponsivesize = " img-responsive";
		}
?>
					<span class="img-span">
						<!-- <a href="<?php echo $feed['link'];?>">Link</a> -->
<?php

		if(isset($feed['videourl'])) {
?>
						<div class="playvideothumbbox"><i class="icon play circle playvideothumb"></i></div> <!-- conditional if video -->
<?php
		}
?>

						<img class="img-bee href<?php echo $imgresponsivesize;?>" src="<?php echo $feed['imgurl'];?>" />
					</span>
<?php
	}
	if(isset($feed['desc'])) {
		$textonly="";
		if(!$imgexist) {
			$textonly = "textonly";
		} else {
			$textonly = "caption";
		}
?>
					<div class="tile-<?php echo $textonly;?>">
						<div class="ellipsis_text"><p><?php echo $feed['desc'];?></p></div>
					</div><!-- //. tile-caption--> 
<?php
	}
?>
				</div>
			</div>
<?php 
}
?>

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<div class="tile-user-info clearfix">
					<img src="<?php echo $data['feed']['userimgurl'];?>" class="tile-avatar-profile-image" alt="<?php echo $data['feed']['username'];?>'s Avatar" width="100%" height="100%">
					<div class="tile-user hasAvatar_">
						<i class="icon source<?php echo $data['feed']['feedsource'];?>"></i><span class="tile-user-name"><?php echo $data['feed']['username'];?></span>
						<!-- <i class="icon sourcefb"></i>
						<i class="icon sourcetw"></i>
						<i class="icon sourcept"></i>
						<i class="icon sourcetr"></i>
						<i class="icon sourceig"></i>
						<i class="icon sourceyt"></i>
						<i class="icon sourcego"></i>
						<i class="icon sourcefl"></i>
						<i class="icon sourceli"></i>
						<i class="icon sourcevi"></i> -->
					</div>
				</div><!-- //. tile-user-info-->
			</div>
			<div class="modal-body">
				<!-- social share -->
				<div class="modal_social_share">
					<small class="social_share_title">Share</small> 
					<ul>
						<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $data['feed']['link'];?>" target="_blank"><i class="icon circular inverted blue iconfb"></i></a></li>
						<li><a href="https://twitter.com/intent/tweet?url=<?php echo $data['feed']['link'];?>" target="_blank"><i class="icon circular inverted blue icontw"></i></a></li>
						<li><a href="http://pinterest.com/pin/create/link/?url=<?php echo $data['feed']['link'];?>" target="_blank"><i class="icon circular inverted blue iconpt"></i></a></li>
						<li><a href="http://www.tumblr.com/share/link?url=<?php echo $data['feed']['link'];?>" target="_blank"><i class="icon circular inverted blue icontr"></i></a></li>
						<li><a href="" target="_blank"><i class="icon circular inverted blue mail"></i></a></li>
					</ul>
				</div>
<?php
		if($data['videotype']) {
			$this->renderPartial("feeddetails-".$data['videotype'],array("data"=>$data));
?>
<?php
		} else if($data['feed']['largeimgurl']) {
			$imgresponsivesize="";
			$imagedimensionratio=1;
			if(isset($data['imgw']) && isset($data['imgh']) && $data['imgw'] && $data['imgh']) {
				$imagedimensionratio = $data['imgw'] / $data['imgh'];
			}
			if(2.2 < $imagedimensionratio) {
				$imgresponsivesize = " img-responsive-lg";
			} else {
				$imgresponsivesize = " img-responsive";
			}
?>
				<img class="img-thumbnail<?php echo $imgresponsivesize;?>" src="<?php echo $data['feed']['largeimgurl'];?>" />
<?php
		}
?>
			</div>
			<div class="modal-footer">
				<div class="modal-tile-caption">
					<p><?php echo $data['feed']['desc'];?></p>
					<div class="modal-tile-foot clearfix"><span class="tile-timestamp" data-source-created-at="1384237469"><?php echo date("d M Y",$data['feed']['time']);?></span>
					</div>
				</div><!-- //. tile-caption--> 
			</div>

<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>BeePond | Social Media Aggregator</title>
		<link href="<?=Yii::app()->request->getBaseUrl(true)?>/css/beepond-frontpage.css" rel="stylesheet" type="text/css">
	
	<script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/modernizr-2.6.2.min.js"></script>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<link rel="stylesheet" href="css/ie8.css">  
		<script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/html5shiv.js"></script>
		<script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/respond.min.js"></script>
	<![endif]-->
	<!--[if lt IE 8]>
		<link rel="stylesheet" href="css/ie7.css">  
		<script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/html5shiv.js"></script>
		<script src="<?=Yii::app()->request->getBaseUrl(true)?>/js/respond.min.js"></script>
	<![endif]-->

	<script type='text/javascript' src="<?=Yii::app()->request->getBaseUrl(true)?>/js/jquery-1.8.3.js" ></script>
	<script type='text/javascript' src="<?=Yii::app()->request->getBaseUrl(true)?>/js/bootstrap.min.js"></script>
	<script type='text/javascript' src="<?=Yii::app()->request->getBaseUrl(true)?>/js/imagesloaded.pkgd.min.js"></script>
	<script type='text/javascript' src="<?=Yii::app()->request->getBaseUrl(true)?>/js/masonry.pkgd.min.js"></script>
	<script type='text/javascript' src="<?=Yii::app()->request->getBaseUrl(true)?>/js/jquery.ThreeDots.min.js"></script>
	<script type='text/javascript' src="<?=Yii::app()->request->getBaseUrl(true)?>/js/main.js"></script>

	<!-- Load JS and start masonry -->
	<script type="text/javascript">
		var ajx=0;
		var feednumber = <?php echo $data['campaign']['initialamount'];?>;
		var maxfeednumber = <?php echo $data['maxfeednumber'];?>;
		$(function() {
			var loading=0;
			$(".loader").hide();
			$(window).scroll(function() {
				if(($(window).scrollTop() + $(window).height() >= $(document).height() - 100) && loading == 0 && feednumber < maxfeednumber) {
					loading = 1;
					// Load more
					$.ajax({
						type: "POST",
						url: "<?=Yii::app()->request->getBaseUrl(true)?>/ajax",
						data: { 'type':'loadmore','cid':"<?php echo $data['campaignid'];?>",'feednumber':feednumber },
						success: function(json) {
							var data = $.parseJSON(json);
							console.log(json);
							if(data[0]==1) { // no error
								var newFeeds = $.parseHTML(data[1]);
								$(".masonry").append(newFeeds).masonry( 'appended', newFeeds );
								feednumber = $(".post-box").length;
								initPage();
							}
							$(".loader").hide();
							$(".powerbox span").hide();
							loading=0;
						},
						error: function(resp) {
						}
					});

					$(".loader").show();
					$(".powerbox span").css("display","inline-block");
					$(".powerbox span").animate({
						top:"-116px"
					}, 300);
				}
			});

			$('#myModal').on('hidden.bs.modal', function() {
				$("#myModal .modal-content").html("");
			});

			// Lead More Animation
			$(".load-container a").on("click",function() {
				var hash = $(this).attr("href");
				var clas = "\\"+hash.replace("#",".") + " ";
				
				var re = new RegExp(clas, "g");
				
				var css = $(hash).find(".css").val().replace(re,"");

				$(hash).find(".css").val(css);
				$(".overlay,"+hash).removeClass("hidden");
				
				return false;
			});
			$(".overlay").on("click",function() {
				$(".source,.overlay").addClass("hidden");
			});

			initPage();

		});
		function initPage() {
			$.when( setMasonry() ).done(function() {
				$.when( threeDots() ).done(function() {
					setMasonry();
					$(window).trigger('resize');
				});
			});

			$(".post-box").each(function() {
				$(this).mouseenter(function() {
					$(this).addClass("hovered");
					// $(".post-box").find("img").css("opacity",0.9);
					// $(".post-box.hovered").find("img").css("opacity",1);
					$(this).find(".tile-user-info").animate({
						top: "0px"
					}, {
						duration:300,
						queue: false,
						complete:function() {
						}
					});
					// $(this).find(".tile-caption").animate({
					// 	opacity: 1,
					// 	bottom: "0px"
					// }, {
					// 	duration:500,
					// 	queue: false,
					// 	complete:function() {
					// 	}
					// });
				})
				$(this).mouseleave(function() {
					$(".hovered").removeClass("hovered");
					// $(".post-box").find("img").css("opacity",1);
					$(this).find(".tile-user-info").animate({
						top: "-75px"
					}, {
						duration:300,
						queue: false,
						complete:function() {
						}
					});
					// $(this).find(".tile-caption").animate({
					// 	opacity: 0,
					// 	bottom: "-120px"
					// }, {
					// 	duration:600,
					// 	queue: false,
					// 	complete:function() {
					// 	}
					// });
				})
			});
		}
		function setMasonry() {
			var elem = $(".masonry");
			elem.imagesLoaded( $(".masonry"), function() {
				elem.masonry({
					columnWidth : '.post-box',
					itemSelector : '.post-box',
					gutter: 0
				});
			});
		}
		function threeDots() {
			$('.tile-user-name').ThreeDots({
				max_rows: 2,
				ellipsis_string: '…'
			});
			$('.tile-caption').ThreeDots({
				max_rows: 3,
				whole_word: true,
				ellipsis_string: '…'
			});
			$('.tile-textonly').ThreeDots({
				max_rows: 8,
				whole_word: true,
				ellipsis_string: '…'
			});
		}
		function openModal(id) {
			// console.log("opening Modal: " + id);
			if(ajx == 0) {
				ajx = 1;
				$.ajax({
					type: "POST",
					url: "<?=Yii::app()->request->getBaseUrl(true)?>/feeddetails",
					data: { 'id':id,'cid':"<?php echo $data['campaignid'];?>" },
					success: function(json) {
						data = jQuery.parseJSON(json);
						// console.log(data);
						ajx = 0;
						if(data[0]==1) {
							$("#myModal .modal-content").html(data[1]);
							$('#myModal').modal('show');
						}
					},
					error: function(resp) {
						// console.log("error");
						// console.log(resp);
					}
				});
			}
		}
	</script>

</head>
<body>
	
<div class="mainbg">
	<!-- Posts -->
<?php
if(isset($data['font'])) {
	if($data['font']['url']) {
?>
	<link href="<?php echo $data['font']['url'];?>" rel="stylesheet" type="text/css">
<?php
	}
}
?>
	<style type="text/css">
		/* This CSS value is adjustable */
		 body,.mainbg { 
			background:#<?php echo $data['campaigntemplate']['backgroundcolor'];?> !important; 
		}
		.post-box {
			padding: <?php echo $data['campaigntemplate']['blockpaddingsize']/2;?>px !important; /* 4px defaut */
		}
		.tile-caption, .tile-textonly { 
			background-color: #<?php echo $data['campaigntemplate']['captionbackgroundcolor'];?> !important; 
			color: #<?php echo $data['campaigntemplate']['captiontextcolor'];?> !important;
	<?php
	if(isset($data['font'])) {
		if($data['font']['name']) {
	?>
		font-family: <?php echo $data['font']['name'];?>;
	<?php
		} else {
	?>
		font-family: inherit;
	<?php
		}
	}
	?>
		font-size: <?php echo $data['campaigntemplate']['captiontextsize'];?>px !important;
		}
	/* 
	<?php print_r($data['campaigntemplate']['attributes']);?>
	*/
	</style>

	<div class="container-fluid">
		<div class="masonry">
<?php $this->renderPartial("/site/feedbox",array("data"=>$data));?>
			
		</div><!-- //. masonry-->
		<div class="powerbox">
			<div class="loader">Loading...</div>
			<span><a href="<?=Yii::app()->request->getBaseUrl(true)?>" target="_blank" class="poweredby">
				<small>Powered by</small> <i class="icon large beepond"></i><em><b>Bee</b>Pond</em></a>
			</span>
		</div><!-- //. powerbox -->
	</div><!-- //. container -->
</div><!-- //. mainbg -->

<!-- Modal start -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
<?php 
// Content is at /site/feeddetails
?>
		</div>
	</div>
</div>
<!-- //. Modal end -->

</body>
</html>
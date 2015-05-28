<?php 
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<rss version="2.0">
<channel>
<title><?php echo $data['feed']['title'];?></title>
<link><?php echo Yii::app()->request->getBaseUrl(true)."/".$data['feed']['link'];?></link>
<description><?php echo $data['feed']['description'];?></description>

<?php 
if(isset($data['pages'])) {
	foreach($data['pages'] AS $page) {
?>
<item>
<title><?php echo $page['title'.$data['lang']];?></title>
<link><?php echo Yii::app()->request->getBaseUrl(true)."/".$page['link'.$data['lang']];?></link>
<guid><?php echo Yii::app()->request->getBaseUrl(true)."/".$page['link'.$data['lang']];?></guid>
<pubDate><?php echo date("D, d M Y H:i:s T",$page['published']) ?></pubDate>
<description><?php echo $page['shortdesc'.$data['lang']];?></description>
</item>
<?php
	}
}
?>
</channel>
</rss>
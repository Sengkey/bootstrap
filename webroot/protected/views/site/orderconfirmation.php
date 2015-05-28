
			<div class="row">
				<div class="col-md-12">
				<h1>Order Confirmation</h1>
					<!-- Details Table start // -->
					<table class="table table-responsive">
						<thead>
						<tr>
							<th>Details</th>
							<th>Amount</th>
						</tr>
						</thead>
			  			<tbody>
			  				<tr class="active_">
			  					<td><b><?php echo $data['campaign']['title'];?></b> 
			  						<br /><small>(30 days x <?php echo Yii::app()->params['plantype'][$data['plantype']];?>)</small></td>
			  					<td>$<?php echo Functions::turnTo2Dec($data['total']);?></td>
			  				</tr>
			  			</tbody> 
			  			<tfoot>
			  				<tr class="active">
			  					<td>Tax (if applicable)</td>
			  					<td>$0.00</td>
			  				</tr>
			  				<tr class="active">
			  					<th>Amount Payable Monthly</th>
			  					<th>$<?php echo Functions::turnTo2Dec($data['total']);?></th>
			  				</tr>	
			  			<tfoot>	
					</table>
					<p>
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="NGSEBM65VPV8C">
							<input type="hidden" name="item_name" value="<?php echo $data['campaign']['title'];?> (ID:<?=$data['campaign']['id']?>)">
							<input type="hidden" name="no_note" value="1">
							<input type="submit" class="btn btn-primary" value="Checkout via Paypal">
							<img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
						</form>
<?php 
/*
						<form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_xclick">
							<input type="hidden" name="business" value="admin@realestateaussie.net.au">
							<input type="hidden" name="return" value="<?=Yii::app()->request->getBaseUrl(true)?>/thankyou">
							<input type="hidden" name="notify_url" value="<?=Yii::app()->request->getBaseUrl(true)?>/notified">
							<input type="hidden" name="item_name" value="<?php echo $data['campaign']['title'];?> (ID:<?=$data['campaign']['id']?>)">
							<input type="hidden" name="item_number" value="1">
							<input type="hidden" name="amount" value="<?=$data['total']?>">
							<input type="hidden" name="currency_code" value="AUD">
							<input type="hidden" name="no_shipping" value="1">
							<input type="hidden" name="upload" value="1">
							<input type="hidden" name="rm" value="2">

							<input type="submit" class="btn btn-primary" value="Checkout via Paypal">

							<img alt="" border="0" src="https://www.paypalobjects.com/en_AU/i/scr/pixel.gif" width="1" height="1">
						</form>
*/
?>
					</p>
				</div>	
			</div><!--/. row -->

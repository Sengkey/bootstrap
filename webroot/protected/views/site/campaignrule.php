
						<div class="alert alert-info alert-dismissable" id="rule<?php echo $data['ruleid'];?>">
							<button type="button" class="close" data-dismiss="alert_" aria-hidden="true" data-toggle="modal" data-target="#modalRule" onClick="openDeleteRuleModal(<?php echo $data['ruleid'];?>,'<?php echo $data['source'];?>', $(this).parent());"><i class="icon trash small"></i></button>
							<button type="button" class="close" data-dismiss="alert__" aria-hidden="true" onClick="pasteRule(<?php echo $data['ruleid'];?>,'<?php echo $data['source'];?>');"><i class="icon copy small"></i></button>
							<span class="label label-primary">Rule</span> <?php echo $data['statement'];?>
						</div>

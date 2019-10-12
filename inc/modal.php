<div class="modal fade" id="AddNewTask" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Add new task</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="post">
					<div class="form-group">
						<label class="col-sm-3 control-label">Task title</label>
						<div class="col-sm-6">
							<input type="text" class="form-control" name="form_title" id="form_title" placeholder="Title"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label">Freelancer</label>
						<div class="col-sm-6">
							<select class="form-control" name="form_freelancer" id="form_freelancer">
								<option value="0">Select freelancer</option>
								<?php
								$args_freelancer = array(
									'posts_per_page' => -1,
									'post_type' => 'freelancer'
								);
								$all_freelancer = get_posts($args_freelancer);
								foreach($all_freelancer as $freelancer) {
								$freelancer = get_post_meta($freelancer->ID, 'name', true);
								?>
									<option><?php echo $freelancer; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label"></label>
						<div class="col-sm-6">
							<button class="btn btn-primary" id="addTask">Add</button>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			</div>
		</div>
	</div>
</div>
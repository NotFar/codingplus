<div class="row">
	<div class="col-lg-3 col-md-4">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-users fa-5x"></i>
					</div>
						<?php
						$args_freelancer = array(
							'posts_per_page' => -1,
							'post_type' => 'freelancer'
						);
						$all_freelancer = get_posts($args_freelancer);
							$f=0;
						foreach($all_freelancer as $freelancer) {
							$f++;
						?>
						<?php } ?>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo $f; ?></div>
						<div>Freelancers</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-3 col-md-4">
		<div class="panel panel-green">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-tasks fa-5x"></i>
					</div>
						<?php
						$args_task = array(
							'posts_per_page' => -1,
							'post_type' => 'task'
						);
						$all_task = get_posts($args_task);
						$t=0;
						foreach($all_task as $task) {
						$t++;
						?>
						<?php } ?>
					<div class="col-xs-9 text-right">
						<div class="huge"><?php echo $t; ?></div>
						<div>Tasks</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
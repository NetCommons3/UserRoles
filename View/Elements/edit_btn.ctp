<?php
/**
 * Edit button template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="panel-footer text-center">
	<a class="btn btn-default btn-workflow" href="<?php echo $this->Html->url('/user_roles/user_roles/index/'); ?>">
		<span class="glyphicon glyphicon-remove"></span>
		<?php echo __d('net_commons', 'Cancel'); ?>
	</a>

	<?php echo $this->Form->button(__d('net_commons', 'OK'), array(
			'class' => 'btn btn-primary btn-workflow',
			'name' => 'save',
		)); ?>
</div>

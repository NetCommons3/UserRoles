<?php
/**
 * Element of delete form
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div ng-init="dangerZone=false;">
	<?php echo $this->Form->create(null, array('type' => 'delete', 'url' => array('action' => 'delete'))); ?>
		<accordion close-others="false">
			<accordion-group is-open="dangerZone" class="panel-danger">
				<accordion-heading style="cursor: pointer">
					<span style="cursor: pointer">
						<?php echo __d('net_commons', 'Danger Zone'); ?>
					</span>
					<span class="pull-right glyphicon" ng-class="{'glyphicon-chevron-down': dangerZone, 'glyphicon-chevron-right': ! dangerZone}"></span>
				</accordion-heading>

				<div class="inline-block">
					<?php echo sprintf(__d('net_commons', 'Delete all data associated with the %s.'), __d('user_roles', 'User role')); ?>
				</div>

				<?php echo $this->Form->hidden('UserRole.0.id'); ?>

				<?php echo $this->Form->hidden('UserRole.0.key'); ?>

				<?php echo $this->Form->button('<span class="glyphicon glyphicon-trash"> </span> ' . __d('net_commons', 'Delete'), array(
						'name' => 'delete',
						'class' => 'btn btn-danger pull-right',
						'onclick' => 'return confirm(\'' . sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('user_roles', 'User role')) . '\')'
					)); ?>
			</accordion-group>
		</accordion>
	<?php echo $this->Form->end(); ?>
</div>

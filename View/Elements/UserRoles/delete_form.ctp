<?php
/**
 * 会員権限の削除Element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="nc-danger-zone" ng-init="dangerZone=false;">
	<accordion close-others="false">
		<accordion-group is-open="dangerZone" class="panel-danger">
			<accordion-heading class="clearfix">
				<span style="cursor: pointer">
					<?php echo __d('net_commons', 'Danger Zone'); ?>
				</span>
				<span class="pull-right glyphicon" ng-class="{'glyphicon-chevron-down': dangerZone, 'glyphicon-chevron-right': ! dangerZone}"></span>
			</accordion-heading>

			<?php if (! $isDeletable) : ?>
				<?php echo $this->NetCommonsForm->error('UserRole.key'); ?>

			<?php else : ?>
				<?php echo $this->NetCommonsForm->create('UserRoleDelete', array(
						'type' => 'delete',
						'url' => $this->NetCommonsHtml->url(array('action' => 'delete'))
					)); ?>
					<div class="pull-left">
						<?php echo sprintf(__d('net_commons', 'Delete all data associated with the %s.'), __d('user_roles', 'User role')); ?>
					</div>
					<?php echo $this->Form->hidden('UserRole.0.id'); ?>
					<?php echo $this->Form->hidden('UserRole.0.key'); ?>

					<?php echo $this->Button->delete(
							__d('net_commons', 'Delete'),
							sprintf(__d('net_commons', 'Deleting the %s. Are you sure to proceed?'), __d('user_roles', 'User role')),
							array('addClass' => 'pull-right')
						); ?>
				<?php endif; ?>
			<?php echo $this->NetCommonsForm->end(); ?>
		</accordion-group>
	</accordion>
</div>

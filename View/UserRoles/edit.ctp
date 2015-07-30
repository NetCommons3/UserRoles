<?php
/**
 * UserRoles edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->Html->script(
	array(
		'/user_roles/js/user_roles.js'
	),
	array('plugin' => false)
);
?>

<?php $this->assign('title', __d('user_roles', 'User Roles')); ?>

<?php echo $this->element('UserRoles.tabs'); ?>

<div class="panel panel-default">
	<?php echo $this->Form->create(null, array('novalidate' => true)); ?>

	<div class="panel-body">
		<?php echo $this->SwitchLanguage->tablist('user_roles_'); ?>

		<?php foreach ($languages as $langId => $langCode) : ?>
			<div id="user-roles-<?php echo $langCode; ?>"
					class="tab-pane<?php echo ($activeLangCode === $langCode ? ' active' : ''); ?>">

				<?php echo $this->element('UserRoles/edit_form', array(
						'langId' => $langId,
					)); ?>
			</div>
		<?php endforeach; ?>

	</div>

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

	<?php echo $this->Form->end(); ?>
</div>

<?php if ($this->request->params['action'] === 'edit' && ! $isSystemized) : ?>
	<?php echo $this->element('UserRoles/delete_form'); ?>
<?php endif;

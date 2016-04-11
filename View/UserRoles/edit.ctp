<?php
/**
 * 権限管理の会員権限の追加・編集テンプレート
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css('/user_roles/css/style.css');

if ($this->params['action'] === 'edit') {
	$type = 'put';
	$description = __d('user_roles', 'Enter the title of the authority, enter the description of authority, and press &#039;NEXT&#039;.');
} else {
	$type = 'post';
	$description = __d('user_roles', 'Enter the title of the authority, enter the description of authority, and specify the level of the authority, and press &#039;NEXT&#039;.');
}
?>

<?php echo $this->element('UserRoles.subtitle'); ?>
<?php echo $this->Wizard->outputWizard(UserRolesAppController::WIZARD_USER_ROLES); ?>
<?php echo $this->MessageFlash->description($description); ?>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create('UserRole', array('type' => $type)); ?>

	<div class="panel-body">
		<?php echo $this->SwitchLanguage->tablist('user-roles-'); ?>

		<div class="tab-content">
			<?php echo $this->element('UserRoles/edit_form'); ?>
		</div>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Button->cancelAndSave(
				__d('net_commons', 'Cancel'),
				__d('net_commons', 'NEXT') . '<span class="glyphicon glyphicon-chevron-right"></span>',
				$this->NetCommonsHtml->url(array('action' => 'index'))
			); ?>
	</div>

	<?php echo $this->NetCommonsForm->end(); ?>
</div>

<?php if ($this->request->params['action'] === 'edit') : ?>
	<?php if (! Hash::get($this->request->data, 'UserRole.0.is_system')) : ?>
		<?php echo $this->element('UserRoles/delete_form'); ?>
	<?php endif; ?>
<?php endif;
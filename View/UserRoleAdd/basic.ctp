<?php
/**
 * 権限管理の会員権限の追加テンプレート
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

echo $this->NetCommonsHtml->css('/user_roles/css/style.css');
?>

<?php
	echo $this->Wizard->navibar(UserRoleAddController::WIZARD_USER_ROLES);
	echo $this->MessageFlash->description(__d(
		'user_roles',
		'Enter the title of the authority, enter the description of authority, and specify the level of the authority, and press [NEXT].'
	));
?>

<div class="panel panel-default">
	<?php echo $this->NetCommonsForm->create('UserRole'); ?>

	<div class="panel-body">
		<?php echo $this->SwitchLanguage->tablist('user-roles-'); ?>

		<div class="tab-content">
			<?php echo $this->element('UserRoles/edit_form'); ?>
		</div>
	</div>

	<div class="panel-footer text-center">
		<?php echo $this->Wizard->buttons(UserRoleAddController::WIZARD_USER_ROLES); ?>
	</div>

	<?php echo $this->NetCommonsForm->end(); ?>
</div>

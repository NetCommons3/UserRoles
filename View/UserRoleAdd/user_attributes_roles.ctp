<?php
/**
 * 権限管理の個人情報設定タブのテンプレート
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
	echo $this->Wizard->navibar(UserRolesAppController::WIZARD_USER_ATTRIBUTES_ROLES);
	echo $this->MessageFlash->description(
		__d('user_roles', 'You can set whether or not to view the user information of others.')
	);
?>

<div class="alert alert-warning">
	<?php echo __d('user_roles', 'There is a possibility that if does not set properly outflow of personal information occurs.'); ?>
</div>

<?php echo $this->NetCommonsForm->create('UserAttributesRoles'); ?>
	<?php echo $this->UserAttributeLayout->renderRow('UserAttributesRoles/render_edit_row'); ?>

	<div class="text-center">
		<?php
			if (! $this->Wizard->naviUrl(UserRolesAppController::WIZARD_USER_ROLES_PLUGINS)) {
				$prevOptions = array(
					'url' => $this->Wizard->naviUrl(UserRolesAppController::WIZARD_USER_ROLES)
				);
			} else {
				$prevOptions = array();
			}
			echo $this->Wizard->buttons(
				UserRolesAppController::WIZARD_USER_ATTRIBUTES_ROLES,
				array(),
				$prevOptions,
				array()
			);
		?>
	</div>

<?php echo $this->NetCommonsForm->end();

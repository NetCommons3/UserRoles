<?php
/**
 * UserAttribute index col template
 *   - $row: UserAttributeLayout.row
 *   - $col: UserAttributeLayout.row
 *   - $layout: UserAttributeLayout
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<ul class="user-attribute-roles-edit">
	<li class="list-group-item clearfix<?php echo (! $this->data['UserRoleSetting']['is_usable_user_manager'] &&
														$userAttribute['UserAttributeSetting']['only_administrator'] ? ' disabled' : ''); ?>">
		<div class="pull-left">
			<?php echo h($userAttribute['UserAttribute']['name']); ?>
			<?php if ($userAttribute['UserAttributeSetting']['required']) : ?>
				<?php echo $this->element('NetCommons.required'); ?>
			<?php endif; ?>
		</div>

		<div class="pull-right">
			<?php echo $this->UserRoleForm->selectUserAttributeRole($userAttribute['UserAttribute']['key']); ?>
		</div>
	</li>
</ul>

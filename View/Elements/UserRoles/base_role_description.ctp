<?php
/**
 * UserAttribute edit form template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<ul class="list-group user-roles-base-role-desc">
	<?php foreach ($baseRoles as $roleKey => $roleName) : ?>
		<li class="list-group-item<?php echo ($activeUserRole === $roleKey ? ' list-group-item-info' : '') ; ?>">
			<h4 class="list-group-item-heading">
				<?php echo h($roleName); ?>
			</h4>
			<p class="list-group-item-text">
				<?php echo (isset($baseRoleDescriptions[$roleKey]) ? $baseRoleDescriptions[$roleKey] : ''); ?>
			</p>
		</li>
	<?php endforeach; ?>
</ul>

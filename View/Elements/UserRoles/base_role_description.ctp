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

<hr>
<?php foreach ($baseRoles as $roleKey => $roleName) : ?>
	<div class="form-group">
		<div>
			<strong><?php echo h($roleName); ?></strong>
		</div>

		<div>
			<?php echo (isset($baseRoleDescriptions[$roleKey]) ? $baseRoleDescriptions[$roleKey] : ''); ?>
		</div>
	</div>
<?php endforeach;

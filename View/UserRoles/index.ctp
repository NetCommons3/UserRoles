<?php
/**
 * UserRoles index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php $this->assign('title', __d('user_roles', 'User Roles')); ?>

<table class="table table-condensed">
	<thead>
		<tr>
			<th></th>
			<th>
				<?php echo __d('user_roles', 'Role name'); ?>
			</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($roles as $index => $role) : ?>
			<tr>
				<td>
					<?php echo ($index + 1); ?>
				</td>
				<td>
					<a href="<?php echo $this->Html->url(array('action' => 'edit')) . '/' . h($role['Role']['key']) . '/'; ?>">
						<?php echo h($role['Role']['name']); ?>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>

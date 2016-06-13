<?php
/**
 * 権限管理の詳細表示のプラグイン設定テンプレート
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<div class="panel panel-default">
	<div class="panel-body plugin-checkbox-outer clearfix">
		<?php
			foreach ($this->data['PluginsRole'] as $i => $pluginsRole) {
				echo $this->UserRoleForm->checkboxUserRole('PluginsRole.' . $i . '.PluginsRole.is_usable_plugin',
					array(
						'label' => $pluginsRole['Plugin']['name'],
						'div' => false,
						'checked' => (bool)$pluginsRole['PluginsRole']['id'],
						'disabled' => true
					)
				);
			}
		?>
	</div>

</div>

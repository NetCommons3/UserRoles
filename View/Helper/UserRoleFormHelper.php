<?php
/**
 * UserRolesForm Helper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppHelper', 'View/Helper');
App::uses('CakeNumber', 'Utility');

/**
 * UserRolesForm Helper
 *
 * @package NetCommons\UserRoles\View\Helper
 */
class UserRoleFormHelper extends AppHelper {

/**
 * 使用ヘルパー
 *
 * @var array
 */
	public $helpers = array(
		'Form',
		'NetCommons.NetCommonsForm',
		'NetCommons.NetCommonsHtml',
	);

/**
 * Option is_usable
 *
 * @var array
 */
	public $isUsableOptions;

/**
 * Option is_allow
 *
 * @var array
 */
	public $isPermittedOptions;

/**
 * Radio attributes
 *
 * @var array
 */
	public $radioAttributes = array(
		'legend' => false,
		'separator' => '<span class="radio-separator"> </span>',
	);

/**
 * Radio attributes
 *
 * @var array
 */
	public $optionsMaxSize = array(
		5242880,
		10485760,
		20971520,
		52428800,
		104857600,
		209715200,
		524288000,
		1073741824
	);

/**
 * Default Constructor
 *
 * @param View $View The View this helper is being attached to.
 * @param array $settings Configuration settings for the helper.
 */
	public function __construct(View $View, $settings = array()) {
		parent::__construct($View, $settings);
		$this->UserRole = ClassRegistry::init('UserRoles.UserRole');
		$this->Role = ClassRegistry::init('Roles.Role');

		$this->isUsableOptions = array(
			'1' => __d('user_roles', 'Use'),
			'0' => __d('user_roles', 'Not use'),
		);

		$this->isPermittedOptions = array(
			'1' => __d('user_roles', 'Permitted'),
			'0' => __d('user_roles', 'Not permitted'),
		);
	}

/**
 * コピー元の権限のSELECTボックス出力
 *
 * @param string $fieldName フィールド名(Modelname.fieldname形式)
 * @param array $attributes タグ属性
 * @return string Formatted HTMLタグ
 */
	public function selectOriginUserRoles($fieldName, $attributes = array()) {
		$html = '';
		$displayDescription = Hash::get($attributes, 'description', false);
		Hash::remove($attributes, 'description');

		$attributes = Hash::merge(array(
			'type' => 'select',
			'div' => false,
			'options' => $this->_View->viewVars['userRoles']
		), $attributes);
		$html .= $this->NetCommonsForm->input($fieldName, $attributes);

		if ($displayDescription) {
			$html .= $this->NetCommonsHtml->div(array('user-roles-origin-role-desc', 'bg-warning', 'text-danger'),
					__d('user_roles', 'Role description'));
		}
		return $html;
	}

/**
 * ユーザ毎のプラグインの利用(ルーム管理、会員管理)RADIOボタンの出力
 *
 * @param string $fieldName フィールド名(Modelname.fieldname形式)
 * @param array $options ラジオボタンのOPTION
 * @param array $attributes タグ属性
 * @return string HTMLタグ
 */
	public function radioUserRole($fieldName, $options = null, $attributes = array()) {
		$html = '';
		if (! isset($options)) {
			$options = $this->isUsableOptions;
		}

		$verifySystem = Hash::get($attributes, 'verifySystem', false);
		$attributes = Hash::remove($attributes, 'verifySystem');

		if ($verifySystem && $this->_View->data['UserRoleSetting']['origin_role_key'] === UserRole::USER_ROLE_KEY_COMMON_USER) {
			$html .= $this->NetCommonsForm->hidden($fieldName);
			$html .= $this->NetCommonsForm->radio(null, $options,
					Hash::merge($this->radioAttributes, array('disabled' => true, 'hiddenField' => false), $attributes));
		} else {
			$html .= $this->NetCommonsForm->radio($fieldName, $options,
					Hash::merge($this->radioAttributes, $attributes));
		}

		return $html;
	}

/**
 * 権限管理の個人情報設定SELECTボックス出力
 *
 * @param array $userAttribute UserAttributeデータ
 * @return string HTMLタグ
 */
	public function selectUserAttributeRole($userAttribute) {
		$userAttributeKey = $userAttribute['UserAttribute']['key'];
		$userAttributeRole = Hash::extract(
			$this->_View->request->data['UserAttributesRole'],
			'{n}.UserAttributesRole[user_attribute_key=' . $userAttributeKey . ']'
		);

		$id = $userAttributeRole[0]['id'];
		$fieldName = 'UserAttributesRole.' . $id . '.UserAttributesRole.other_user_attribute_role';

		if ($userAttributeRole[0]['other_editable']) {
			$this->_View->request->data = Hash::insert($this->_View->request->data, $fieldName, UserAttributesRolesController::OTHER_EDITABLE);
		} elseif ($userAttributeRole[0]['other_readable']) {
			$this->_View->request->data = Hash::insert($this->_View->request->data, $fieldName, UserAttributesRolesController::OTHER_READABLE);
		} else {
			$this->_View->request->data = Hash::insert($this->_View->request->data, $fieldName, UserAttributesRolesController::OTHER_NOT_READABLE);
		}

		$html = '';
		$html .= '<div class="form-group">';
		$html .= '<div class="input-group input-group-sm user-attribute-roles-edit">';

		$label = h($userAttribute['UserAttribute']['name']);
		if ($userAttribute['UserAttributeSetting']['required']) {
			$label .= $this->_View->element('NetCommons.required', array('size' => 'h5'));
		}
		$html .= $this->Form->label($fieldName, $label, array('class' => 'input-group-addon user-attribute-roles-edit'));

		if ($this->_View->request->data['UserRoleSetting']['is_usable_user_manager']) {
			$disabled = true;
		} else {
			$disabled = false;
			$html .= $this->Form->hidden('UserAttributesRole.' . $id . '.UserAttributesRole.id');
			$html .= $this->Form->hidden('UserAttributesRole.' . $id . '.UserAttributesRole.role_key');
			$html .= $this->Form->hidden('UserAttributesRole.' . $id . '.UserAttributesRole.user_attribute_key');
		}

		$options = $this->__optionsUserAttributeRole($userAttribute);
		if (count($options) <= 1) {
			$disabled = true;
		}
		$attributes = array(
			'type' => 'select',
			'class' => 'form-control',
			'empty' => false,
			'disabled' => $disabled,
		);
		$html .= $this->Form->select($fieldName, $options, $attributes);

		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}

/**
 * 権限管理の個人情報設定のOptions
 *
 * @param array $userAttribute UserAttributeデータ
 * @return string HTMLタグ
 */
	private function __optionsUserAttributeRole($userAttribute) {
		$userAttributeKey = $userAttribute['UserAttribute']['key'];
		$userAttributeRole = Hash::extract(
			$this->_View->request->data['UserAttributesRole'],
			'{n}.UserAttributesRole[user_attribute_key=' . $userAttributeKey . ']'
		);

		if ($userAttributeRole[0]['user_attribute_key'] === 'handlename') {
			//ハンドルの場合、「閲覧させない」を除外する
			$options = array(
				UserAttributesRolesController::OTHER_READABLE => __d('user_roles', 'Readable of others'),
			);
		} elseif ($userAttribute['UserAttributeSetting']['data_type_key'] === DataType::DATA_TYPE_PASSWORD) {
			//パスワードの場合、「閲覧させる」を除外する
			$options = array(
				UserAttributesRolesController::OTHER_NOT_READABLE => __d('user_roles', 'Not readable of others'),
			);
		} elseif (! $userAttribute['UserAttributeSetting']['only_administrator_readable'] ||
				$this->_View->request->data['UserRoleSetting']['is_usable_user_manager']) {
			$options = array(
				UserAttributesRolesController::OTHER_NOT_READABLE => __d('user_roles', 'Not readable of others'),
				UserAttributesRolesController::OTHER_READABLE => __d('user_roles', 'Readable of others'),
			);
		} else {
			$options = array(
				UserAttributesRolesController::OTHER_NOT_READABLE => __d('user_roles', 'Not readable of others'),
			);
		}

		//以下の場合、編集の選択肢は表示させない
		// * ラベルタイプ
		// * 会員管理が使用できない
		if ($userAttribute['UserAttributeSetting']['data_type_key'] === DataType::DATA_TYPE_LABEL ||
				! $this->_View->request->data['UserRoleSetting']['is_usable_user_manager']) {
			return $options;
		}

		//編集の選択肢を表示する
		$options[UserAttributesRolesController::OTHER_EDITABLE] = __d('user_roles', 'Editable of others');

		return $options;
	}

}

<?php
/**
 * EdumapHelper
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FormHelper', 'View/Helper');
App::uses('CakeNumber', 'Utility');

/**
 * DataTypesHelper
 *
 * @package NetCommons\UserAttributes\View\Helper
 */
class UserRoleFormHelper extends FormHelper {

/**
 * Other helpers used by FormHelper
 *
 * @var array
 */
	public $helpers = array('Form');

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
 * Outputs base user roles select
 *
 * @param string $fieldName Name attribute of the SELECT
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted SELECT element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function selectOriginUserRoles($fieldName, $attributes = array()) {
		$userRoles = $this->UserRole->find('list', array(
			'recursive' => -1,
			'fields' => array('key', 'name'),
			'conditions' => array(
				'type' => UserRole::ROLE_TYPE_USER,
				'language_id' => Configure::read('Config.languageId')
			),
			'order' => array('id' => 'asc')
		));
		unset($userRoles[UserRole::USER_ROLE_KEY_SYSTEM_ADMINISTRATOR]);

		$html = '';

		$attributes = Hash::merge(array(
			'type' => 'select',
			'class' => 'form-control',
			'options' => $userRoles
		), $attributes);
		$html .= $this->Form->input($fieldName, $attributes);

		return $html;
	}

/**
 * Outputs default room roles select
 *
 * @param string $fieldName Name attribute of the SELECT
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted SELECT element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function selectDefaultRoomRoles($fieldName, $attributes = array()) {
		$defaultRoles = $this->Role->find('list', array(
			'fields' => array('key', 'name'),
			'conditions' => array(
				'is_systemized' => true,
				'language_id' => Configure::read('Config.languageId'),
				'type' => Role::ROLE_TYPE_ROOM
			),
			'order' => array('id' => 'asc')
		));

		if (isset($attributes['options'])) {
			$defaultRoles = Hash::merge($defaultRoles, $attributes['options']);
			unset($attributes['options']);
		}

		$html = '';

		if (isset($attributes['label'])) {
			if (is_array($attributes['label'])) {
				$label = $attributes['label']['label'];
				unset($attributes['label']['label']);

				$html .= $this->Form->label($fieldName, $label, $attributes['label']) . ' ';
			} else {
				$html .= $this->Form->label($fieldName, $attributes['label']) . ' ';
			}
			unset($attributes['label']);
		}
		$attributes = Hash::merge(array(
			'type' => 'select',
			'class' => 'form-control',
			'empty' => false
		), $attributes);
		$html .= $this->Form->select($fieldName, $defaultRoles, $attributes);

		return $html;
	}

/**
 * Outputs radio
 *
 * @param string $fieldName Name attribute of the RADIO
 * @param array $options The HTML options of the radio element.
 * @param array $attributes The HTML attributes of the radio element.
 * @return string Formatted RADIO element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function radioUserRole($fieldName, $options, $attributes = array()) {
		$attributes = Hash::merge($this->radioAttributes, $attributes);

		return $this->Form->radio($fieldName, $options, $attributes);
	}

/**
 * Outputs upload max size select
 *
 * @param string $fieldName Name attribute of the SELECT
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted SELECT element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function selectMaxSize($fieldName, $attributes = array()) {
		$maxSizes = array_combine($this->optionsMaxSize, $this->optionsMaxSize);
		$maxSizes = array_map('CakeNumber::toReadableSize', $maxSizes);

		$attributes = Hash::merge(array(
			'type' => 'select',
			'class' => 'form-control',
			'empty' => false
		), $attributes);

		return $this->Form->select($fieldName, $maxSizes, $attributes);
	}

/**
 * Outputs UserAttributeRole select
 *
 * @param string $userAttributeKey user_attributes.key
 * @param array $attributes The HTML attributes of the select element.
 * @return string Formatted SELECT element
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/form.html#options-for-select-checkbox-and-radio-inputs
 */
	public function selectUserAttributeRole($userAttributeKey, $attributes = array()) {
		if (! $userAttributeRole = Hash::extract(
			$this->_View->request->data['UserAttributesRole'],
			'{n}.UserAttributesRole[user_attribute_key=' . $userAttributeKey . ']'
		)) {
			return;
		}
		if (! $userAttribute = Hash::extract(
			$this->_View->request->data['UserAttribute'],
			'{n}.{n}.{n}.UserAttributeSetting[user_attribute_key=' . $userAttributeKey . ']'
		)) {
			return;
		}

		$id = $userAttributeRole[0]['id'];
		$fieldName = 'UserAttributesRole.' . $id . '.UserAttributesRole.other_user_attribute_role';

		if ($userAttributeRole[0]['other_editable']) {
			$this->_View->request->data['UserAttributesRole'][$id]['other_user_attribute_role'] = UserAttributesRolesController::OTHER_EDITABLE;
		} elseif ($userAttributeRole[0]['other_readable']) {
			$this->_View->request->data['UserAttributesRole'][$id]['other_user_attribute_role'] = UserAttributesRolesController::OTHER_READABLE;
		} else {
			$this->_View->request->data['UserAttributesRole'][$id]['other_user_attribute_role'] = UserAttributesRolesController::OTHER_NOT_READABLE;
		}

		if ($this->_View->request->data['UserRoleSetting']['is_usable_user_manager'] ||
				$userAttribute[0]['only_administrator'] ||
				$this->_View->request->data['UserRole']['is_systemized']) {

			$disabled = true;
		} else {
			$disabled = false;
		}

		$options = array(
			UserAttributesRolesController::OTHER_NOT_READABLE => __d('user_roles', 'Not readable of others'),
			UserAttributesRolesController::OTHER_READABLE => __d('user_roles', 'Readable of others'),
			UserAttributesRolesController::OTHER_EDITABLE => __d('user_roles', 'Editable of others'),
		);

		$attributes = Hash::merge(array(
			'type' => 'select',
			'value' => $this->_View->request->data['UserAttributesRole'][$id]['other_user_attribute_role'],
			'class' => 'form-control',
			'empty' => false,
			'disabled' => $disabled,
		), $attributes);

		$html = $this->Form->select($fieldName, $options, $attributes);

		if (! $disabled) {
			$html .= $this->Form->hidden('UserAttributesRole.' . $id . '.UserAttributesRole.id');
			$html .= $this->Form->hidden('UserAttributesRole.' . $id . '.UserAttributesRole.role_key');
			$html .= $this->Form->hidden('UserAttributesRole.' . $id . '.UserAttributesRole.user_attribute_key');
		}

		return $html;
	}

}

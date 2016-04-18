<?php
/**
 * UserAttributesRole Behavior
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('ModelBehavior', 'Model');
App::uses('UserAttribute', 'UserAttributes.Model');
App::uses('DataType', 'DataTypes.Model');

/**
 * UserAttributesRole Behavior
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\UserRoles\Model\Behavior
 */
class UserAttributesRoleBehavior extends ModelBehavior {

/**
 * デフォルト閲覧とするフィールド
 *
 * @var array
 */
	public $readableDefault = array(
		UserAttribute::HANDLENAME_FIELD, UserAttribute::AVATAR_FIELD,
	);

/**
 * UserAttributesRoleのデフォルト値
 *
 * * パスワード＝自分／他人とも読み取り不可
 * * ラベル項目＝自分／他人とも書き込み不可
 * * 会員管理が使用可＝上記以外、自分・他人とも自分／他人の読み・書き可
 * * 会員管理が使用不可
 * ** 管理者以外、読み取り不可項目とする＝自分／他人の読み・書き不可。※読めないのに書けるはあり得ないため。
 * ** 管理者以外、書き込み不可項目とする＝自分／他人の書き込み不可。
 * ** 上記以外
 * *** ハンドル・アバター＝自分は、読み・書き可。他人は、読み取り可／書き込み不可。
 * *** それ以外＝自分は、読み・書き可。他人は、読み・書き不可。
 *
 * @param Model $model Model using this behavior
 * @param array|string $userAttrSetting 配列：ユーザ属性設定データ、文字列：ユーザ属性キー
 * @param bool $enableUserManager 有効かどうか
 * @return array ユーザ属性ロールデータ
 */
	public function defaultUserAttributeRole(Model $model, $userAttrSetting, $enableUserManager) {
		$model->loadModels([
			'UserAttributeSetting' => 'UserAttributes.UserAttributeSetting',
		]);
		$userAttrSetting = $model->UserAttributeSetting->create($userAttrSetting);

		$userAttributeRole = array();
		$userAttributeRole['UserAttributesRole']['self_readable'] = true;
		$userAttributeRole['UserAttributesRole']['self_editable'] = true;

		$userAttributeKey = $userAttrSetting['UserAttributeSetting']['user_attribute_key'];
		if ($userAttributeKey === UserAttribute::PASSWORD_FIELD) {
			$userAttributeRole['UserAttributesRole']['self_readable'] = false;
			$userAttributeRole['UserAttributesRole']['other_readable'] = false;
		} elseif ($enableUserManager) {
			$userAttributeRole['UserAttributesRole']['other_readable'] = true;
		} elseif (Hash::get($userAttrSetting, 'UserAttributeSetting.only_administrator_readable')) {
			$userAttributeRole['UserAttributesRole']['self_readable'] = false;
			$userAttributeRole['UserAttributesRole']['other_readable'] = false;
			$userAttrSetting['UserAttributeSetting']['only_administrator_editable'] = true;
		} else {
			$userAttributeRole['UserAttributesRole']['self_readable'] = true;
			$userAttributeRole['UserAttributesRole']['other_readable'] =
							in_array($userAttributeKey, $this->readableDefault, true);
		}

		$userAttributeRole['UserAttributesRole']['other_editable'] = false;
		if ($userAttrSetting['UserAttributeSetting']['data_type_key'] === DataType::DATA_TYPE_LABEL) {
			$userAttributeRole['UserAttributesRole']['self_editable'] = false;
		} elseif ($enableUserManager) {
			$userAttributeRole['UserAttributesRole']['other_editable'] = true;
		} elseif (Hash::get($userAttrSetting, 'UserAttributeSetting.only_administrator_editable')) {
			$userAttributeRole['UserAttributesRole']['self_editable'] = false;
		}

		return $userAttributeRole;
	}

}

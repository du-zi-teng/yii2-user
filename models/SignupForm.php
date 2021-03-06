<?php

namespace yii2mod\user\models;

use yii\base\Model;
use Yii;

/**
 * Class SignupForm
 * @package yii2mod\user\models
 */
class SignupForm extends Model
{
    /**
     * @var string username
     */
    public $username;

    /**
     * @var string email
     */
    public $email;

    /**
     * @var string password
     */
    public $password;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => BaseUserModel::className(), 'message' => Yii::t('yii2mod.user', 'This username has already been taken.')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => BaseUserModel::className(), 'message' => Yii::t('yii2mod.user', 'This email address has already been taken.')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('yii2mod.user', 'Username'),
            'email' => Yii::t('yii2mod.user', 'Email'),
            'password' => Yii::t('yii2mod.user', 'Password'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return BaseUserModel|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new BaseUserModel();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->lastLogin = time();

        return $user->save() ? $user : null;
    }
}
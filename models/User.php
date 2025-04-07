<?php

namespace app\models;

use Yii;
use Exception;
use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    /* public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken; */

    public $password_repeats;
    public $email;

    public static function tableName()
    {
        return 'users';
    }

    public function rules() {
        return [
            [['username', 'password'], 'required'],
            ['username', 'filter', 'filter' => function($v) {
                $v = ltrim(rtrim($v));
                $v = strtolower($v);
                return $v;
            }],
            ['username', 'unique'],
            ['username', 'string', 'length' => [3, 100]],
            ['password', 'compare', 'compareAttribute' => 'password_repeats'],
            ['password_repeats', 'default'],
            [['bio'], 'default'],
            ['email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Usuario',
        ];
    }

    public function attributeHints()
    {
        return [
            'username' => 'Deberá ser unico en el sistema',
            'password_repeats' => 'Tiene que ser igual al anterior'
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        $user = self::findOne($id);
        if (empty($user)) {
            return null;
        }
        return $user;
        //return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = self::findOne(['token' => $token]);
        if (empty($user)) {
            return null;
        }
        return $user;
    }
    public static function findByUsername($username)
    {
        $user = self::find()->where(['username' => $username])->one();
        if (empty($user)) {
            return null;
        }
        return $user;
    }
    public function getId()
    {
        return $this->user_id;
    }
    public function getAuthKey()
    {
        return $this->auth_key;
    }
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }
    public function validatePassword($password)
    {
        return $this->password === $this->ofuscatePassword($password);
    }

    public function ofuscatePassword($password)
    {
        if (empty(getenv('salt'))) {
            throw new Exception('no salt');
        }
        return md5(sprintf('%s-%s-%s', $password, $this->username, getenv('salt')));
    }

    public function beforeSave($insert)
    {
        if ($insert == true) {
            $this->password = $this->ofuscatePassword($this->password);
        }
        return parent::beforeSave($insert);
    }
}

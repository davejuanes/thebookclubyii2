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

    public static function tableName()
    {
        return 'users';
    }
    public static function findIdentity($id)
    {
        // return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
        $user = self::findOne($id);
        if (empty($user)) {
            return null;
        }
        return $user;
    }
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /* foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null; */
        $user = self::findOne(['token' => $token]);
        if (empty($user)) {
            return null;
        }
        return $user;
    }
    public static function findByUsername($username)
    {
        /* foreach (self::$users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null; */
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
        $salt = getenv('salt');
        if (empty($salt)) {
            Yii::error('Error: La variable salt no está definida.', __METHOD__);
            throw new Exception('Error: La variable salt no está definida.');
        }
        return md5(sprintf('%s-%s-%s', $password, $this->username, $salt));
    }

    public function beforeSave($insert)
    {
        if ($insert == true) {
            $this->password = $this->ofuscatePassword($this->password);
        }
        return parent::beforeSave($insert);
    }
}

<?php

namespace app\commands;

use yii\console\Controller;
use yii\console\ExitCode;
use app\models\User;

class UserController extends Controller {
    public function actionNew($username, $password) {
        $user = new User();
        $user->username = $username;
        $user->password = $password;
        if ($user->save()) {
            printf("New user ok, id: %d\n", $user->id);
        } else {
            printf("Problema creando usuario\n");
        }
    }
    public function actionCheck($username, $password) {
        $user = User::findOne(['username' => $username]);
        if (!empty($user)) {
            if ($user->password === $user->ofuscatePassword($password)) {
                printf("Login Valido\n");
                return ExitCode::OK;
            }
        }
        printf("nel\n");
        return ExitCode::OK;
    }
}
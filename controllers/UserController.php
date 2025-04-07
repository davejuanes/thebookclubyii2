<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use Exception;

class UserController extends Controller {
    public function actionNew() {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('warning', 'Ya estás logueado, no puedes crear un nuevo usuario');
            return $this->goHome();
        }
        $user = new User();

        if($user->load(Yii::$app->request->post())) {
            // Hay algo en POST que es para mi
            if($user->validate()) {
                // Lo que cargue validó bien
                if ($user->save()) {
                    // Lo que valide se salvó en la base de datos
                    Yii::$app->session->setFlash('success', 'Usuario creado correctamente');
                    return $this->redirect(['site/login']);
                } else {
                    throw new \Exception('error al guardar el usuario');
                    return;
                }
            }
            $user->password = '';
            $user->password_repeats = '';
        }

        return $this->render('new.tpl', [
            'user' => $user,
        ]);
    }
}
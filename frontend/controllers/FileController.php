<?php

namespace frontend\controllers;

use frontend\controllers\base\BaseController;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use common\models\User;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class FileController extends BaseController
{
    public function actionIndex()
    {
        $model = User::findOne(560);
        if (Yii::$app->request->isPost) {
            $file = UploadedFile::getInstance($model, 'avatar');
            $path = 'image/'.'up_'.time().".".$file->getExtension();
            if($file->saveAs(Yii::getAlias("@webroot").'/'.$path) === true){
                $model->avatar = $path;
            }
            $model->update();
//            var_dump($file);
        }
        
        return $this->render('index', [
            'model' => $model
        ]);
        // 视图里代码 <?= $form->field($model,'avatar')->fileInput();
    }
}

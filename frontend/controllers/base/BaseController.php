<?php
/**
 * Created by PhpStorm.
 * User: WAXKI
 * Date: 2018/12/19
 * Time: 15:29
 */
namespace frontend\controllers\base;



use yii\web\Controller;

class BaseController extends Controller
{
    public function beforeAction($action)
    {
        if(!parent::beforeAction($action)) {
            return false;
        }
        return true;
        
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: WAXKI
 * Date: 2018/12/19
 * Time: 15:39
 */

namespace frontend\controllers;

use common\models\Cats;
use common\models\PostExtends;
use common\models\Posts;
use common\models\User;
use frontend\models\PostForm;
use Yii;
use frontend\controllers\base\BaseController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class PostController extends BaseController
{
    public function behaviors()
    {
        return [
            // 访问控制
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'upload', 'ueditor'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['create', 'upload', 'ueditor'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    '*' => ['get', 'post'],
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionCreate()
    {
        $model = new PostForm();
        //设置当前处理场景为创建文章
        $model->setScenario(PostForm::SCENARIOS_CREATE);

//        var_dump(Yii::$app->request->post());die;
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->create()) {
                    return $this->redirect(['post/view', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('warning', $model->_lastError);
                }
                
            } else {
                Yii::$app->session->setFlash('warning', json_encode($model->getErrors(), JSON_UNESCAPED_UNICODE));
            }
        }
        $cat = Cats::getAllCats();
        return $this->render('create', compact('model', 'cat'));
    }
    
    public function actionDetail($id)
    {
        $postForm = new PostForm();
        $data = $postForm->getViewById($id);
//        echo '<pre>';
//        print_r($data);die;
        $pe = new PostExtends();
        $pe->upCounter(['post_id'=>$id],'browser',1);
        return $this->render('detail', compact('data'));
    }
    
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'common\widgets\file_upload\UploadAction',     //这里扩展地址别写错
                'config' => [
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}",
                ]
            ],
            
            'ueditor' => [
                'class' => 'common\widgets\ueditor\UeditorAction',
                'config' => [
                    //上传图片配置
                    'imageUrlPrefix' => "", /* 图片访问路径前缀 */
                    'imagePathFormat' => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", /* 上传保存路径,可以自定义保存路径和文件名格式 */
                ]
            ]
        ];
    }
    
}
<?php

namespace frontend\models;


use common\models\Posts;
use common\models\Tags;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

class TagForm extends Model
{
    public $id;
    public $tags;

//    public $_lastError = '';
    
    public function rules()
    {
        return [
            [['tags',], 'required'],
            [['tags'], 'each', 'rule' => ['string']],
        ];
    }
    
    
    public function attributeLabels()
    {
        return [
            'id' => '标签ID',
            'tags' => '标签内容'
        ];
    }
    
    /*
     * 场景设置
     * */
//    public function scenarios()
//    {
//        $scenarios = [
//            self::SCENARIOS_CREATE => ['title', 'content', 'label_img', 'cat_id', 'tags'],
//            self::SCENARIOS_UPDATE => ['title', 'content', 'label_img', 'cat_id', 'tags'],
//        ];
//        return array_merge(parent::scenarios(), $scenarios);
//    }
    
    public function saveTags()
    {
        $ids = [];
        if (!empty($this->tags)) {
            foreach ($this->tags as $tag) {
                $ids[] = $this->_saveTag($tag);
            }
        }
        return $ids;
    }
    
    private function _saveTag($tagName)
    {
        $tag = new Tags();
        
        if ($res = $tag->find()->where(['tag_name' => $tagName])->one()) { //原有标签的关联文章数+1
            $res->updateCounters(['post_num' => 1]);
        } else { //新建标签
            $tag->tag_name = $tagName;
            $tag->post_num = 1;
            if (!$tag->save()) throw new \Exception('标签保存失败::' . json_encode($tag->errors, JSON_UNESCAPED_UNICODE));
            $res = $tag; //为使返回操作一致
        }
        
        return $res->id;
    }
    

}
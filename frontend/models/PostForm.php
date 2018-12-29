<?php
/**
 * Created by PhpStorm.
 * User: WAXKI
 * Date: 2018/12/19
 * Time: 16:43
 */

namespace frontend\models;


use common\models\base\Base;
use common\models\Posts;
use common\models\RelationPostTags;
use Yii;
use yii\base\Model;
use yii\db\Query;

class PostForm extends Model
{
    public $id;
    public $title;
    public $content;
    public $label_img;
    public $cat_id;
    public $tags;
    
    public $_lastError = '';
    
    const SCENARIOS_CREATE = 'create';
    const SCENARIOS_UPDATE = 'update';
    
    const EVENT_AFTER_CREATE = 'create';
    const EVENT_AFTER_UPDATE = 'update';
    
    public function rules()
    {
        return [
            [['id', 'title', 'content', 'cat_id'], 'required'],
            [['id', 'cat_id'], 'integer'],
            ['title', 'string', 'min' => 4, 'max' => 50],
        ];
    }
    
    
    public function attributeLabels()
    {
        return [
            'id' => '文章ID',
            'title' => '标题',
            'content' => '内容',
            'label_img' => '标签图',
            'tags' => '标签'
        ];
    }
    
    /*
     * 场景设置
     * */
    public function scenarios()
    {
        $scenarios = [
            self::SCENARIOS_CREATE => ['title', 'content', 'label_img', 'cat_id', 'tags'],
            self::SCENARIOS_UPDATE => ['title', 'content', 'label_img', 'cat_id', 'tags'],
        ];
        return array_merge(parent::scenarios(), $scenarios);
    }
    
    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function create()
    {
        $trans = Yii::$app->db->beginTransaction();
        try {
            $post = new Posts();
            $post->setAttributes($this->attributes);
            $post->summary = $this->_getSummary();
            $post->user_id = Yii::$app->user->identity->id;
            $post->user_name = Yii::$app->user->identity->username;
            $post->is_valid = Posts::VALID;
            $post->updated_at = $post->created_at = time();
            
            //如果保存失败,则拼接错误信息,并抛出
            if (!$post->save()) {
                $err = '';
                foreach ($post->errors as $k => $item) {
                    $err .= "字段($k)=>";
                    foreach ($item as $kk => $vv) {
                        $i = $kk + 1;
                        $err .= "$i.$vv;";
                    }
                    $err .= "\n";
                }
                throw new \Exception($err);
            }
            
            $this->id = $post->id;
            //调用事件
            $data = array_merge($this->getAttributes(), $post->getAttributes());
//            var_dump($data);die;
            $this->_eventAfterCreate($data);
            
            $trans->commit();
            return true;
        } catch (\Exception $e) {
            $trans->rollBack();
            $this->_lastError = $e->getMessage();
            return false;
        }
        return $this->render('index');
    }
    
    /**
     * @param int $s
     * @param int $e
     * @param string $char
     * @return null|string
     */
    private function _getSummary($s = 0, $e = 90, $char = 'utf-8')
    {
        if (empty($this->content)) return null;
        return (mb_substr(str_replace('&nbsp;', '', strip_tags($this->content)), $s, $e, $char));
    }
    
    /**
     * 文章保存后事件
     * @param $data
     */
    private function _eventAfterCreate($data)
    {
        //add event. use off() cancel
        $this->on(self::EVENT_AFTER_CREATE, [$this, '_eventAddTag'], $data);
        //trigger event
        $this->trigger(self::EVENT_AFTER_CREATE);
    }
    
    /**
     *
     */
    public function _eventAddTag($event)
    {
        //保存标签
        $tf = new TagForm();
        $tf->tags = $event->data['tags'];
        $tagids = $tf->saveTags();
//        var_dump($event->data,'tagggg');die;
        
        
        //删除原先的关联关系
        RelationPostTags::deleteAll(['post_id' => $event->data['id']]);
        
        //批量保存文章和标签的关联关系
        if (!empty($tagids)) {
            foreach ($tagids as $k => $id) {
                $row[$k]['post_id'] = $this->id;
                $row[$k]['tag_id'] = $id;
            }
            
            $res = (new Query())
                ->createCommand()
                ->batchInsert(RelationPostTags::tableName(), ['post_id', 'tag_id'], $row)
                ->execute();
            
            if (!$res) throw new \Exception('关联关系保存失败!');
            
        }
    }
    
    public function getViewById($id)
    {
        $res = Posts::find()
            ->where(['id' => $id])
//            ->with('relate')
            ->with('relate.tag', 'extend')
            ->asArray()
            ->one();
        
        if (!$res) new NotFoundHttpException('文章不存在');
        
        $res['tags'] = [];
        if (!empty($res['relate'])) {
            foreach ($res['relate'] as $v) {
                $res['tags'][] = $v['tag']['tag_name'];
            }
            unset($res['relate']);
        }
        
        return $res;
    }
    
    public static function getList($cond, $curPage = 1, $pageSize = 5, $orderBy = ['id' => SORT_DESC])
    {
        $post = new Posts();
        $fields = [
            'id',
            'title',
            'summary',
            'label_img',
            'cat_id',
            'user_id',
            'user_name',
            'is_valid',
            'created_at',
            'updated_at',
        ];
        
        $query = $post->find()
            ->select($fields)
            ->where($cond)
            ->with('relate.tag', 'extend')
            ->orderBy($orderBy);
        
        //获取分页数据
        $ret = $post->getPages($query, $curPage, $pageSize);
//        var_dump($ret,111111);die;
    
        //格式化
        $ret['data'] = self::_formatList($ret['data']);
//        var_dump($ret,222);die;
        
        return $ret;
    }
    
    /**
     * 数据格式化
     * @param $data
     */
    public static function _formatList($data)
    {
        foreach ($data as &$list) {
            $list['tags'] = [];
            if (!empty($list['relate'])) {
                foreach ($list['relate'] as $v) {
                    $list['tags'][] = $v['tag']['tag_name']; //所有标签名都汇总在tags组下
                }
            }
            unset($list['relate']);
        }
        
        return $data;
    }
    
    /*
     * array (size=6)
  'count' => string '4' (length=1)
  'curPage' => int 1
  'pageSize' => int 6
  'start' => int 1
  'end' => string '4' (length=1)
  'data' =>
    array (size=4)
      0 =>
        array (size=12)
          'id' => string '137' (length=3)
          'title' => string '文章标题' (length=12)
          'summary' => string 'Congratulations!You have successfully created your Yii-powered application.HeadingLorem ip' (length=90)
          'label_img' => string '/image/20181222/1545472530130105.jpg' (length=36)
          'cat_id' => string '5' (length=1)
          'user_id' => string '560' (length=3)
          'user_name' => string 'jf' (length=2)
          'is_valid' => string '1' (length=1)
          'created_at' => string '1545472569' (length=10)
          'updated_at' => string '1545472569' (length=10)
          'relate' =>
            array (size=2)
              ...
          'extend' =>
            array (size=6)
              ...
      1 =>
     * */
    
}
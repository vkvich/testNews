<?php


namespace app\models;

use yii\db\ActiveRecord;
use yii\base\Model;
use yii\data\ActiveDataProvider;
/**
 * This is the model class for table "page".
 *
 * @property int $id
 * @property string $image
 * @property string $title
 * @property string $text
 */
class Article extends ActiveRecord
{
    public function scenarios()
    {
        return Model::scenarios();
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }
    public function search($params)
    {
        $query = Article::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id
        ]);

        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['image', 'text'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image' => 'Cover',
            'title' => 'Title',
            'text' => 'Text',
        ];
    }

}
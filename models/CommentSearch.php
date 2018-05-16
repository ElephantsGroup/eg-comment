<?php

namespace elephantsGroup\comment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use elephantsGroup\comment\models\Comment;

/**
 * CommentSearch represents the model behind the search form about `elephantsGroup\comment\models\Comment`.
 */
class CommentSearch extends Comment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'item_id', 'service_id', 'status'], 'integer'],
            [['ip', 'email', 'subject', 'description', 'update_time', 'creation_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Comment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'item_id' => $this->item_id,
            'service_id' => $this->service_id,
            'status' => $this->status,
            'update_time' => $this->update_time,
            'creation_time' => $this->creation_time,
        ]);

        $query->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}

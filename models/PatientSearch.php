<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about `webvimark\modules\UserManagement\models\User`.
 */
class PatientSearch extends Patient {
    public $modelClass = Patient::class;

    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params) {
        /** @var Patient $modelClass */
        $modelClass = $this->modelClass;
        $query = $modelClass::find();

        $query->with(['status', 'polyclinic', 'treatment', 'formDisease', 'updatedBy']);

        /*if ( !Yii::$app->user->isSuperadmin )
		{
			$query->where(['superadmin'=>0]);
		}*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->request->cookies->getValue('_grid_page_size', 100),
            ],
            'sort' => [
                'defaultOrder' => [
                    'updated' => SORT_DESC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'polyclinic_id' => $this->polyclinic_id,
            'status_id' => $this->status_id,
            'form_disease_id' => $this->form_disease_id,
            'treatment_id' => $this->treatment_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])->andFilterWhere(['like', 'phone', $this->phone]);

        return $dataProvider;
    }
}

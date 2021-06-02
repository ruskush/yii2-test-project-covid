<?php

namespace app\controllers;

use app\models\resource\Patient;
use app\models\PatientSearch;
use Yii;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\rest\IndexAction;
use yii\rest\Serializer;

class PatientssApiController extends Controller {
    public $modelClass = Patient::class;
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items',
    ];

    public function behaviors() {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        $behaviors['ghost-access'] = [
            'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
        ];
        return $behaviors;
    }

    public function actions() {
        return [
            'index' => [
                'class' => IndexAction::class,
                'modelClass' => $this->modelClass,
                'prepareDataProvider' => function (IndexAction $action, $filter) {
                    $searchModel = new PatientSearch(['modelClass' => $this->modelClass]);
                    return $searchModel->search(Yii::$app->request->get());
                },
            ],
        ];
    }
}

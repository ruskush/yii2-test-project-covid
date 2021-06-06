<?php

namespace app\controllers;

use app\components\CreateModelSerializer;
use app\models\resource\Patient;
use app\models\PatientSearch;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\rest\CreateAction;
use yii\rest\IndexAction;
use yii\rest\Serializer;

class PatientssApiController extends Controller {
    public $modelClass = Patient::class;
    public $serializer = [
        'class' => Serializer::class,
        'collectionEnvelope' => 'items',
    ];

    public function verbs() {
        return [
            'index' => ['get'],
            'create' => ['post'],
        ];
    }

    public function beforeAction($action) {
        if ($action->id === 'create') {
            $this->serializer = CreateModelSerializer::class;
        }
        return parent::beforeAction($action);
    }

    public function behaviors() {
        // Если запрос совершён из браузера залогиненного пользователя (т.е. имеется авторизационная кука)
        if (Yii::$app->request->cookies->has(Yii::$app->user->identityCookie['name'])) {
            $authMethods = [];
        } else {
            // Запрос от сторонних сервисов - авторизация по Bearer токену
            $authMethods = [HttpBearerAuth::class];
        }

        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::class,
            'authMethods' => $authMethods,
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
            'create' => [
                'class' => CreateAction::class,
                'modelClass' => $this->modelClass,
            ],
        ];
    }
}

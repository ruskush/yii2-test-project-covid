<?php

namespace app\components;

use yii\db\ActiveRecord;
use yii\rest\Serializer;

class CreateModelSerializer extends Serializer {

    public function serialize($data) {
        $statusCreated = false;
        $errors = [];
        $createdId = null;

        if ($data instanceof ActiveRecord) {
            if ($data->hasErrors()) {
                $errors = $this->serializeModelErrors($data);
            } else {
                $statusCreated = true;
                $createdId = $data->id;
            }
        }

        return ['statusCreated' => $statusCreated, 'errors' => $errors, 'id' => $createdId];
    }
}

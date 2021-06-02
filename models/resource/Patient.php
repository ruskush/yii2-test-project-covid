<?php

namespace app\models\resource;

class Patient extends \app\models\Patient {
    public function fields() {
        return [
            'name',
            'birthday',
            'phone',
            'polyclinic',
            'status',
            'treatment',
            'formDisease',
            'updated',
            'diagnosis_date',
            'recovery_date',
        ];
    }
}

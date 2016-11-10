<?php

namespace App\Routines;

use MongoHue;

class RoutinesManager
{
    public static function updateRoutine($routine)
    {
        $routineId = new \MongoDB\BSON\ObjectID($routine["_id"]['$oid']);
        $fields = [];
        foreach (array_keys($routine) as $field) {
            $fields[$field] = $routine[$field];
        }
        unset($fields['_id']);

        MongoHue::table('routines')->updateOne([
            '_id' => $routineId,
        ], [
            '$set' => $fields,
        ], [
            'upsert' => true,
        ]);
    }
}
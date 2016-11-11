<?php

namespace App\Routines;

use MongoDB\BSON\ObjectID;
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

    public static function createRoutine($routine)
    {
        MongoHue::table('routines')->insertOne($routine);
    }

    public static function deleteRoutine($routineId)
    {
        MongoHue::table("routines")->deleteOne([
            '_id' => new ObjectID($routineId),
        ]);
    }
}
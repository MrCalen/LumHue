<?php

declare(strict_types=1);

namespace App\Helpers\Mongo;

use \MongoDB\Driver\Cursor;
use \MongoDB\Driver\BSONDocument;
use \MongoDB\BSON\Serializable;

class Utils
{
    public static function mongoArray(Cursor $bsonDocuments) : array
    {
        $unserialized = [];
        array_map(function (Serializable $element) use (&$unserialized) {
            $unserialized[] = \MongoDB\BSON\toPHP(\MongoDB\BSON\fromPHP($element));
        }, iterator_to_array($bsonDocuments));

        return $unserialized;
    }

    public static function toJson(array $document) : \stdClass
    {
        return json_decode(\MongoDB\BSON\toJSON(\MongoDB\BSON\fromPHP($document)));
    }
}

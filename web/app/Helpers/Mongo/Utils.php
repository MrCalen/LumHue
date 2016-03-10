<?php

declare(strict_types=1);

namespace App\Helpers\Mongo;
use \MongoDB\Driver\Cursor;
use \MongoDB\Driver\BSONDocument;
use \MongoDB\BSON\Serializable;

class Utils
{
  public static function MongoArray(Cursor $bsonDocuments) : array
  {
    $unserialized = [];
    array_map(function (Serializable $element) use (&$unserialized) {
      $unserialized[] = json_decode(json_encode($element));
    }, iterator_to_array($bsonDocuments));
    return $unserialized;
  }
}

?>

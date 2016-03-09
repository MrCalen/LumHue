<?php

namespace App\Helpers\Mongo;

class Utils
{
  public static function MongoArray($bsonDocuments)
  {
    $unserialized = [];
    array_map(function ($element) use (&$unserialized) {
      $unserialized[] = $element->bsonSerialize();
    }, iterator_to_array($bsonDocuments));
    return $unserialized;
  }
}

?>

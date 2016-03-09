<?php

namespace App\Helpers\MongoDBModel;

use App\Models\Light;

class LumHueMongo
{
  protected $client;

  public function __construct()
  {
    $username =  env('MONGO_USERNAME', "");
    $password =  env('MONGO_PASSWORD', "");
    $host =  env('MONGO_HOST', "");

    $this->client = new \MongoDB\Client("mongodb://" . $username . ':' . $password . '@' . $host);
    //$client->selectDB('LumHue');
  }

  public function table($table)
  {
    return $this->client->LumHue->{$table};
  }
}

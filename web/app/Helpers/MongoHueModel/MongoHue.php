<?php

namespace App\Helpers\MongoHueModel;

use App\Models\Light;

class MongoHue
{
  protected $client;
  protected $db;

  public function __construct()
  {
    $username =  env('MONGO_USERNAME', "");
    $password =  env('MONGO_PASSWORD', "");
    $host =  env('MONGO_HOST', "");

    $this->client = new \MongoDB\Client("mongodb://" . $username . ':' . $password . '@' . $host);
    $this->db = $this->client->LumHue;
  }

  public function table($table)
  {
    return $this->db->{$table};
  }
}

<?php

declare(strict_types=1);

namespace App\Helpers\MongoHueModel;

use App\Models\Light;
use App\Helpers\Mongo\Utils;

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

    public function table($table) : \MongoDB\Collection
    {
        return $this->db->{$table};
    }

    public function find($table, $criteria = [], $filter = [ 'sort' => ['_id' =>  1]]) : \stdClass
    {
        return Utils::toJson(Utils::MongoArray($this->table($table)
                                                    ->find($criteria, $filter)));
    }

    public function insert($table, $data, $filter = [])
    {
        $this->table($table)
             ->insertOne($data);
    }
}

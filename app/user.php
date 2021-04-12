<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;

class User extends Model implements AuthenticatableContract {

    protected $connection = 'default';
    
    use Authenticatable;
    
    public function __construct() {
      parent::__construct();
      $BBDD = session('db_name');
      $this->connection = $BBDD;
    }
}

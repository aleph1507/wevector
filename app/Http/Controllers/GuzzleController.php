<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Traits\GuzzleTrait;

class GuzzleController extends Controller
{
  use GuzzleTrait;

  public function index()
  {
    $this->getRemoteData();
  }

  

}

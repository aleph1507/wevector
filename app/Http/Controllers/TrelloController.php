<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\GuzzleTrait;

class TrelloController extends Controller
{

        // API key: 32335dc9a96db5455bb307490a60d207
        // OAuth 1 authorization secret: fbec2fa3ca57db53fb5dd18ee54bc22d1fe4a97b54c073e8f6eb258ee62aedb6
        // generated token: 24ceb7c8e7c3f8e9b32e4c25788359ea21db87a71c8ada27a23d7af890841c0a
    // id, username: hristo82

//'https://api.trello.com/1/members/hristo82/key={32335dc9a96db5455bb307490a60d207}&token={24ceb7c8e7c3f8e9b32e4c25788359ea21db87a71c8ada27a23d7af890841c0a}'

    use GuzzleTrait;

    public $id = 'hristo82';
    public $api_key = '32335dc9a96db5455bb307490a60d207';
    public $token = '24ceb7c8e7c3f8e9b32e4c25788359ea21db87a71c8ada27a23d7af890841c0a';

    public function index()
    {

        $boards = $this->request('GET', "https://api.trello.com/1/members/$this->id/boards?key=$this->api_key&token=$this->token");
//        $boards = $this->request('GET', "https://api.trello.com/1/members/hristo82/boards?key=32335dc9a96db5455bb307490a60d207&token=24ceb7c8e7c3f8e9b32e4c25788359ea21db87a71c8ada27a23d7af890841c0a");
//        dd(json_decode($boards));
        var_dump(json_decode($this->getResBody($boards)));
//        $new = $this->new_board();
    }

    public function new_board()
    {
        $body = 'newBoard';
        return $this->request('POST', 'https://api.trello.com/1/boards/', ['key' => $this->api_key, 'token' => $this->token], ['key' => $this->api_key, 'token' => $this->token]);
    }
}

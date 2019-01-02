<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Traits\GuzzleTrait;

trait TrelloTrait
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
        $new = $this->new_board();
        return $new;
    }

    public function new_board($name)
    {
        $headers = [
            'Authorization' => 'Bearer ' . $this->token,
            'key' => $this->api_key,
            'name' => 'newB'
        ];
        $query_string = "key=$this->api_key&token=$this->token&name=$name";
        return $this->request('POST', 'https://api.trello.com/1/boards?' . $query_string, ['headers' => $headers]);
    }

    public function check_board($name = 'WeVectorBoard')
    {
        $bid = false;
        $boards = json_decode($this->getResBody($this->get_boards()));
        for($i = 0; $i < count($boards); $i++)
            if($boards[$i]->name == $name)
                $bid = $boards[$i]->id;

        if(!$bid)
            $bid = json_decode($this->getResBody($this->new_board($name)))->id;

        return $bid;
    }

    public function get_boards()
    {
        $url = "https://api.trello.com/1/members/me/boards?";
        $query_string = "key=$this->api_key&token=$this->token";

        return $this->request('GET', $url . $query_string);
    }

    public function get_boards_lists($bid)
    {
        $url = "https://api.trello.com/1/boards/$bid/lists?";
        $query_string = "key=$this->api_key&token=$this->token";

        return $this->request('GET', $url . $query_string);
    }

    public function new_order_card($listId, $name, $desc)
    {
        $url = "https://api.trello.com/1/cards?";
        $query_string = "key=$this->api_key&token=$this->token&idList=$listId&name=$name&desc=$desc";
        return $this->request('POST', $url . $query_string);
    }

}

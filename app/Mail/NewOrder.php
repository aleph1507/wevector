<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewOrder extends Mailable
{
    use Queueable, SerializesModels;

    public $primary_img;
    public $drive_folder;
    public $trello_board;
    public $oid;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     */
    public function __construct($primary_img, $drive_folder, $trello_board, $oid)
    {
        $this->primary_img = $primary_img;
        $this->drive_folder = $drive_folder;
        $this->trello_board = $trello_board;
        $this->oid = $oid;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Order Number: ' . $this->oid)->view('mail.new_order')->to('xrristo@gmail.com');
    }
}

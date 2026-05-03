<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $password;
    public $email;
    public $name;

    /**
     * Create a new message instance.
     *
     * @param string $email
     * @param string $password
     * @param string $name
     */
    public function __construct($email, $password, $name)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your New Account Password')
                    ->view('emails.send_password')
                    ->with([
                        'name' => $this->name,
                        'password' => $this->password,
                    ]);
    }
}

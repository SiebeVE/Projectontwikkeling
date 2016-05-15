<?php
/**
 * Created by PhpStorm.
 * User: Siebe
 * Date: 15/05/2016
 * Time: 16:48
 */

namespace App\Mailer;

use App\User;
use Illuminate\Contracts\Mail\Mailer;

class AppMailer
{
	protected $mailer;

	protected $to;

	protected $view;

	protected $data = [];

	public function __construct(Mailer $mailer)
	{
		$this->mailer = $mailer;
	}

	public function sendEmailConfirmationTo(User $user)
	{
		$this->to = $user->email;
		$this->view = 'emails.confirm';
		$this->data = compact('user');

		$this->deliver();
	}

	public function sendEmailChangeConfirmationTo(User $user)
	{
		$this->to = $user->email;
		$this->view = 'emails.confirmChange';
		$this->data = compact('user');

		$this->deliver();
	}

	public function deliver()
	{
		$this->mailer->send($this->view, $this->data, function ($message)
		{
			$message->to($this->to);
		});
	}
}
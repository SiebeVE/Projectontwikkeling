Click here to reset your password: <a href="{{ $link = url('wachtwoord/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>

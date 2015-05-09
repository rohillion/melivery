<?php

namespace libraries\CoreExtension\Auth\Reminders;

use Closure;
use Illuminate\Auth\Reminders\ReminderRepositoryInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Mail\Mailer;

class PasswordBroker extends \Illuminate\Auth\Reminders\PasswordBroker {

    public function __construct(ReminderRepositoryInterface $reminders, UserProviderInterface $users, Mailer $mailer, $reminderView) {
        parent::__construct($reminders, $users, $mailer, $reminderView);
    }

    public function remind(array $credentials, Closure $callback = null) {

        $user = $this->getUser($credentials);

        $code = $this->reminders->create($user);

        return $this->sendReminder($user, $code, $callback);
    }
    
    public function getToken($credentials,$code) {

        $user = $this->getUser($credentials);
        
        $user->code = $code;

        return $this->reminders->createNewToken($user);
    }

    /**
     * Send the password reminder e-mail.
     *
     * @param  \Illuminate\Auth\Reminders\RemindableInterface  $user
     * @param  string    $code
     * @param  \Closure  $callback
     * @return int
     */
    public function sendReminder(RemindableInterface $user, $code, Closure $callback = null) {
        return \SMS::send($user->mobile, \Config::get('twilio.TWILIO_NUMBER'), 'Tu código de verificación Melivery es ' . $code);
    }

}

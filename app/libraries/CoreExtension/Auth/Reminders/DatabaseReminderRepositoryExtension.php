<?php

namespace libraries\CoreExtension\Auth\Reminders;

use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Auth\Reminders\DatabaseReminderRepository;
use Illuminate\Auth\Reminders\RemindableInterface;

class DatabaseReminderRepositoryExtension extends DatabaseReminderRepository {

    public function __construct(Connection $connection, $table, $hashKey, $expires = 60) {
        parent::__construct($connection, $table, $hashKey, $expires);
    }

    /**
     * Create a new reminder record and token.
     *
     * @param  \Illuminate\Auth\Reminders\RemindableInterface  $user
     * @return string
     */
    public function create(RemindableInterface $user) {
        
        $mobile = $user->mobile;

        $this->deleteExisting($user);

        // We will create a new, random token for the user so that we can SMS them
        // a secret code. Then we will insert a record in
        // the database so that we can verify the token within the actual reset.
        $user->code = \CommonEvents::getRandomCode();
        $token = $this->createNewToken($user);
        
        $this->getTable()->insert($this->getPayload($mobile, $token));

        return $user->code;
    }

    /**
     * Delete all existing reset tokens from the database.
     *
     * @param  \Illuminate\Auth\Reminders\RemindableInterface  $user
     * @return int
     */
    protected function deleteExisting(RemindableInterface $user) {
        return $this->getTable()->where('mobile', $user->mobile)->delete();
    }

    /**
     * Build the record payload for the table.
     *
     * @param  string  $mobile
     * @param  string  $token
     * @return array
     */
    protected function getPayload($mobile, $token) {
        return array('mobile' => $mobile, 'token' => $token, 'created_at' => new Carbon);
    }

    /**
     * Determine if a reminder record exists and is valid.
     *
     * @param  \Illuminate\Auth\Reminders\RemindableInterface  $user
     * @param  string  $token
     * @return bool
     */
    public function exists(RemindableInterface $user, $token) {

        $reminder = (array) $this->getTable()->where('mobile', $user->mobile)->where('token', $token)->first();

        return $reminder && !$this->reminderExpired($reminder);
    }

    /**
     * Create a new token for the user.
     *
     * @param  \Illuminate\Auth\Reminders\RemindableInterface  $user
     * @return string
     */
    public function createNewToken(RemindableInterface $user) {

        return hash_hmac('sha1', sha1($user->code.$user->mobile), $this->hashKey);
    }

}

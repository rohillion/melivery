<?php

Class Billing {

    static function createCustomer($user) {
        
        Commerce::setStripeKey(Config::get('stripe.secretkey'));

        if (!is_null($user->id_commerce)) {
            $commerce = Commerce::find($user->id_commerce);
            $commerce->trial_ends_at = Carbon\Carbon::now()->addDays(365);
            $commerce->subscription('freemium')->create(NULL, [
                'description' => $user->mobile
            ]);
            
            return $commerce->save();
        }

        return false;
    }

}

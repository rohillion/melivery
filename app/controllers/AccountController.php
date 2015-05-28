<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\User\UserForm;
use App\Service\Form\AccountController\AccountForm;
use App\Service\Form\AccountController\Request\RequestForm;
use App\Service\Form\AccountController\Reset\ResetForm;
use App\Service\Form\AccountController\Verification\VerificationForm;
use App\Service\Form\AccountController\Settings\SettingsForm;

class AccountController extends BaseController {

    protected $user;
    protected $account;
    protected $request;
    protected $reset;
    protected $verification;
    protected $settings;

    public function __construct(UserForm $user, AccountForm $account, RequestForm $request, ResetForm $reset, VerificationForm $verification, SettingsForm $settings) {
        $this->user = $user;
        $this->account = $account;
        $this->request = $request;
        $this->reset = $reset;
        $this->verification = $verification;
        $this->settings = $settings;
    }
    
    public function index() {
        return Redirect::route('account.login');
    }

    public function login() {
        return View::make("account.login");
    }

    public function doLogin() {

        $res = $this->account->login(Input::only('mobile', 'password'));

        if (!isset($res['error'])) {

            return Redirect::to($res);
        }

        return Redirect::route("account.login")
                        ->withInput(Input::except('password'))
                        ->withErrors($res['error']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function signup() {

        $data = array(
            'nan' => NULL,
            'commercial' => NULL,
            'individual' => NULL,
        );

        if (is_string(Input::get('commercial')) || Input::old('account_type') == 'commercial') {

            $data['commercial'] = 'selected';
        } else if (is_string(Input::get('individual')) || Input::old('account_type') == 'individual') {

            $data['individual'] = 'selected';
        } else {

            $data['nan'] = 'selected';
        }

        return View::make('account.signup', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function doSignup() {

        $input = Input::only('name', 'mobile', 'password', 'account_type');

        $res = $this->user->save($input, true);

        if ($res) {

            return Redirect::route('account.verification')
                            ->withSuccess(Lang::get('segment.attribute.message.success.store'))
                            ->with('status', 'success');
        }

        return Redirect::route('account.signup')
                        ->withInput()
                        ->withErrors($this->user->errors())
                        ->with('status', 'error');
    }

    public function verification() {

        $messageBag = new MessageBag(['sent' => Lang::get('account.sent'), 'resend' => Lang::get('account.resend')]);

        if (!Session::get('user.verification')) {

            $this->verification->sendVerification(Auth::user()->id);

            $messageBag = new MessageBag(['sent' => Lang::get('account.resent'), 'resend' => Lang::get('account.resend')]);
        }

        $data['speech'] = $messageBag;

        return View::make("account.verification", $data);
    }

    public function doVerification() {

        $input = Input::only('vcode');

        $res = $this->verification->verify($input);

        if (!isset($res['error'])) {

            return Redirect::to($res);
        }

        return Redirect::route('account.verification')
                        ->withInput()
                        ->withErrors($res['error'])
                        ->with('status', 'error');
    }

    public function request() {

        return View::make("account.request");
    }

    public function doRequest() {

        $input = Input::only("mobile");

        $request = $this->request->remind($input);

        if ($request) {

            return Redirect::route("account.reset")
                            ->withInput($input)
                            ->withSuccess('Hemos enviado el codigo de recuperacion de clave.');
        }

        return Redirect::route("account.request")
                        ->withInput($input)
                        ->withErrors($this->request->errors()->all());
    }

    public function reset() {
        return View::make("account.reset");
    }

    public function doReset() {

        $input = Input::only(
                        'mobile', 'password', 'password_confirmation', 'code'
        );

        $res = $this->reset->save($input);

        if ($res)
            return Redirect::route("account.login")->withSuccess($res);


        return Redirect::route("account.reset")
                        ->withInput($input)
                        ->withErrors($this->reset->errors()->all());
    }

    public function logout() {
        Auth::logout();
        Session::flush();
        return Redirect::route("account.login");
    }

    public function settings() {
        return View::make("account.settings");
    }

    public function profile() {

        $input = Input::only('name', 'mobile');

        $user = $this->settings->updateProfile($input);

        if ($user)
            return Redirect::route("account.settings")
                            ->withSuccess('Los datos han sido actualizados.');


        return Redirect::route("account.settings")
                        ->withInput($input)
                        ->withErrors($this->settings->errors()->all());
    }

    public function password() {

        $input = Input::only('password', 'newpassword', 'confirm');
        
        $user = $this->settings->updatePassword($input);

        if ($user)
            return Redirect::route("account.settings")
                            ->withSuccess('La clave ha sido actualizada.');


        return Redirect::route("account.settings")
                        ->withInput($input)
                        ->withErrors($this->settings->errors()->all());
    }
    
    public function tos() {
        return View::make("account.tos");
    }

    public function missingMethod($parameters = array()) {
        //
    }

}

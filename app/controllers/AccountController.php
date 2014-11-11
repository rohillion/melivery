<?php

use Illuminate\Support\MessageBag;
use App\Service\Form\User\UserForm;
use App\Service\Form\AccountController\AccountForm;
use App\Service\Form\AccountController\Request\RequestForm;
use App\Service\Form\AccountController\Reset\ResetForm;
use App\Service\Form\AccountController\Verification\VerificationForm;

class AccountController extends BaseController {

    protected $user;
    protected $account;
    protected $request;
    protected $reset;
    protected $verification;

    public function __construct(UserForm $user, AccountForm $account, RequestForm $request, ResetForm $reset, VerificationForm $verification) {
        $this->user = $user;
        $this->account = $account;
        $this->request = $request;
        $this->reset = $reset;
        $this->verification = $verification;
    }

    public function login() {

        return View::make("account.login");
    }

    public function doLogin() {

        $res = $this->account->login(Input::only('email', 'password'));

        if (!isset($res['error'])) {

            return Redirect::to($res);
        }

        return Redirect::route("login")
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

        $input = Input::only('name', 'email', 'password', 'account_type');

        $res = $this->user->save($input, true);

        if ($res) {

            return Redirect::route('verification')
                            ->withSuccess(Lang::get('segment.attribute.message.success.store'))
                            ->with('status', 'success');
        }

        return Redirect::route('signup')
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

        return Redirect::route('verification')
                        ->withInput()
                        ->withErrors($res['error'])
                        ->with('status', 'error');
    }

    public function request() {

        return View::make("account.request");
    }

    public function doRequest() {

        $input = Input::only("email");

        $res = $this->request->remind($input);

        if (!isset($res['error']) && $res) {

            return Redirect::route("request")
                            ->withSuccess($res['success']);
        } else {

            $error = isset($res['error']) ? $res['error'] : $this->request->errors();
        }

        return Redirect::route("request")
                        ->withInput($input)
                        ->withErrors($error);
    }

    public function reset($token = null) {

        if (is_null($token))
            App::abort(404);

        $data = [
            "token" => $token
        ];

        return View::make("account.reset", $data);
    }

    public function doReset() {

        $token = Input::get("token");

        $input = Input::only(
                        'email', 'password', 'password_confirmation', 'token'
        );

        $res = $this->reset->save($input);

        if (!isset($res['error']) && $res) {

            return Redirect::route("login")
                            ->withSuccess($res['success']);
        } else {

            $error = isset($res['error']) ? $res['error'] : $this->reset->errors();
        }

        return Redirect::to(URL::route("reset") . '/' . $token)
                        ->withInput($input)
                        ->withErrors($error);
    }

    public function logout() {
        Auth::logout();
        Session::flush();
        return Redirect::route("login");
    }

    public function missingMethod($parameters = array()) {
        //
    }

}

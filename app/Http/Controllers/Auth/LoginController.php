<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     HDVinnie
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use \Toastr;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    // Max attempts until lockout
    public const MAX_ATTEMPTS = 2;
    // Expiration time for lockout (8 hours)
    public const LOCKOUT_DURATION = 8 * 60;

    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function username()
    {
        return 'username';
    }

    private static function throttleKey(Request $request) {
        $ip = $request->ip();
        $key = "ip:tries#{$ip}";
        return $key;
    }

    protected function authenticated(Request $request, $user)
    {
        Redis::del(self::getRedisKey($request));
        if (!\App\Policy::isActivated($user)) {
            auth()->logout();
            $request->session()->flush();
            return redirect()->route('login')->with(Toastr::error('This account has not been activated and is still in validating group, Please check your email for activation link. If you did not receive the activation code, please click "forgot password" and complete the steps.', 'Whoops!', ['options']));
        }
        if (\App\Policy::isBanned($user)) {
            auth()->logout();
            $request->session()->flush();
            return redirect()->route('login')->with(Toastr::error('This account is Banned!', 'Whoops!', ['options']));
        }
        return redirect('/');
    }

    protected function hasTooManyLoginAttempts(Request $request)
    {
        return $this->limiter()->tooManyAttempts($this->throttleKey($request),
                                                 self::MAX_ATTEMPTS,
                                                 self::LOCKOUT_DURATION
        );
    }

    protected function incrementLoginAttempts(Request $request)
    {
        $this->limiter()->hit($this->throttleKey($request),
                              self::LOCKOUT_DURATION);
    }
}

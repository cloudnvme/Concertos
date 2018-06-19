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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\User;
use App\Invite;
use App\Mail\InviteUser;
use \Toastr;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use App\Policy;

class InviteController extends Controller
{
    public function invite()
    {
        $user = auth()->user();
        if (config('other.invite-only') == false) {
            Toastr::error('Invitations are Currently Disabled due to Open Registration!', 'Whoops!', ['options']);
        }
        if (!\App\Policy::canInvite($user)) {
            Toastr::error('Your Invite Rights Have Been Revoked!', 'Whoops!', ['options']);
        }
        return view('user.invite', ['user' => $user]);
    }

    public function process(Request $request)
    {
        if (!\App\Policy::canInvite(auth()->user())) {
            Toastr::error('You\\\'re not allowed to invite', 'Whoops!');
            return redirect()->route('home');
        }
        $current = new Carbon();
        $user = auth()->user();
        $invites_restricted = config('other.invites_restriced', false);
        $invite_groups = config('other.invite_groups', []);
        if ($invites_restricted && !in_array($user->group->name, $invite_groups)) {
            return redirect()->route('invite')->with(Toastr::error('Invites are currently disabled for your userclass.', 'Whoops!', ['options']));
        }
        $exists = Invite::where('email', $request->input('email'))->first();
        $member = User::where('email', $request->input('email'))->first();
        if ($exists || $member) {
            return redirect()->route('invite')->with(Toastr::error('The email address you\\\'re trying to send an invite to has already been sent one or is already in use.', 'Whoops!', ['options']));
        }

        $unlimited = Policy::hasUnlimitedInvites($user);
        if ($user->invites > 0 || $unlimited) {
            $bytes = random_bytes(32);
            $code = strtr(base64_encode($bytes), '+/', '-_');

            //create a new invite record
            $invite = Invite::create([
                'user_id' => $user->id,
                'email' => $request->input('email'),
                'code' => $code,
                'expires_on' => $current->copy()->addDays(config('other.invite_expire')),
                'custom' => $request->input('message'),
            ]);

            // send the email
            Mail::to($request->input('email'))->send(new InviteUser($invite));

            if (!$unlimited) {
                $user->invites -= 1;
                $user->save();
            }

            \LogActivity::addToLog("Member {$user->username} has sent an invite to {$invite->email} .");

            return redirect()->route('invite')->with(Toastr::success('Invite was sent successfully!', 'Yay!', ['options']));
        } else {
            return redirect()->route('invite')->with(Toastr::error('You do not have enough invites!', 'Whoops!', ['options']));
        }
    }

    public function inviteTree($id)
    {
        if (\App\Policy::isModerator(auth()->user())) {
            $user = User::findOrFail($id);
            $records = Invite::with('sender')->where('user_id', $user->id)->latest()->get();
        } else {
            $user = auth()->user();
            $records = Invite::with('sender')->where('user_id', $user->id)->latest()->get();
        }
        return view('user.invitetree', ['user' => $user, 'records' => $records]);
    }
}

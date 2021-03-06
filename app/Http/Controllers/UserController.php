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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Group;
use App\User;
use App\Peer;
use App\Torrent;
use App\Comment;
use App\Client;
use App\Post;
use App\Topic;
use App\PrivateMessage;
use App\Follow;
use App\History;
use App\Warning;
use App\Note;
use App\Mail\InviteUser;
use \Toastr;
use Image;
use Carbon\Carbon;

/**
 * User Management
 *
 *
 *
 */
class UserController extends Controller
{

    public function members()
    {
        $users = User::latest()->paginate(50);

        return view('user.members', ['users' => $users]);
    }

    public function userSearch(Request $request)
    {
        $search = $request->input('search');
        $users = User::where([
            ['username', 'like', '%' . $request->input('username') . '%'],
        ])->paginate(25);
        $users->setPath('?username=' . $request->input('username'));
        return view('user.members')->with('users', $users);
    }

    public function profile($id)
    {
        $user = User::findOrFail($id);
        $followers = Follow::where('target_id', $id)->get();
        $history = $user->history;
        $warnings = Warning::where('user_id', $id)->whereNotNull('torrent')->where('active', 1)->take(3)->get();
        $hitrun = Warning::where('user_id', $id)->latest()->paginate(10);

        return view('user.profile', ['user' => $user, 'followers' => $followers, 'history' => $history, 'warnings' => $warnings, 'hitrun' => $hitrun]);
    }

    public function editProfile(Request $request, $id)
    {
        $user = auth()->user();
        // Requetes post only
        if ($request->isMethod('POST')) {
            // Avatar
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                if (in_array($image->getClientOriginalExtension(), ['jpg', 'JPG', 'jpeg', 'bmp', 'png', 'PNG', 'tiff', 'gif', 'GIF']) && preg_match('#image/*#', $image->getMimeType())) {
                    $filename = $user->username . '.' . $image->getClientOriginalExtension();
                    $path = public_path('/files/img/' . $filename);
                    Image::make($image->getRealPath())->fit(150, 150)->save($path);
                    $user->image = $user->username . '.' . $image->getClientOriginalExtension();
                }
            }
            // Define data
            $user->title = $request->input('title');
            $user->about = $request->input('about');
            $user->signature = $request->input('signature');
            // Save the user
            $user->save();

            // Activity Log
            \LogActivity::addToLog("Member " . $user->username . " has updated there profile.");

            return redirect()->route('profile', ['id' => $user->id])->with(Toastr::success('Your Account Was Updated Successfully!', 'Yay!', ['options']));
        }

        return view('user.edit_profile', ['user' => $user]);
    }

    public function settings($id)
    {
        $user = auth()->user();
        return view('user.settings', ['user' => $user]);
    }

    public function changeSettings(Request $request, $id)
    {
        $user = auth()->user();
        if ($request->isMethod('POST')) {
            // General Settings
            $user->censor = $request->input('censor') == 'on';
            $user->chat_hidden = $request->input('chat_hidden') == 'on';

            // Style Settings
            //$user->style = (int)$request->input('theme');
            $css_url = $request->input('custom_css');
            if (isset($css_url) && filter_var($css_url, FILTER_VALIDATE_URL) === false) {
                return redirect()->route('profile', ['username' => $user->username, 'id' => $user->id])->with(Toastr::error('The URL for the external CSS stylesheet is invalid, try it again with a valid URL.', 'Whoops!', ['options']));
            } else {
                $user->custom_css = $css_url;
            }
            //$user->nav = $request->input('sidenav');

            // Privacy Settings
            $user->hidden = $request->input('onlinehide') == 'on';
            $user->private_profile = $request->input('private_profile') == 'on';
            $user->peer_hidden = $request->input('peer_hidden') == 'on';

            // Torrent Settings
            $user->show_poster = $request->input('show_poster') == 'on';
            //$user->ratings = $request->input('ratings');

            // Security Settings
            if (config('auth.TwoStepEnabled') == true) {
                $user->twostep = $request->input('twostep') == 'on';
            }
            $user->save();

            // Activity Log
            \LogActivity::addToLog("Member " . $user->username . " has changed there account settings.");
            return redirect()->route('profile', ['id' => $user->id])->with(Toastr::success('Your Account Was Updated Successfully!', 'Yay!', ['options']));
        }

        return redirect()->route('profile', ['id' => $user->id])->with(Toastr::error('Something Went Wrong!', 'Whoops!', ['options']));
    }

    protected function changePassword(Request $request)
    {
        $user = auth()->user();
        $v = validator($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required|min:6',
        ]);
        if ($v->passes()) {
            if (Hash::check($request->current_password, $user->password)) {
                $user->fill([
                    'password' => Hash::make($request->new_password)
                ])->save();
                return redirect('/')->with(Toastr::success('Your Password Has Been Reset', 'Yay!', ['options']));
            }
            return redirect()->route('profile', ['id' => $user->id])->with(Toastr::error('Your Password Was Incorrect!', 'Whoops!', ['options']));
        }

        return redirect()->route('profile', ['id' => $user->id])->with(Toastr::error('Your New Password Is To Weak!', 'Whoops!', ['options']));
    }

    protected function changeEmail(Request $request, $id)
    {
        $user = auth()->user();
        $v = validator($request->all(), [
            'current_password' => 'required',
            'new_email' => 'required',
        ]);
        if ($v->passes()) {
            if ($request->isMethod('POST')) {
                $user->email = $request->input('new_email');
                $user->save();

                // Activity Log
                \LogActivity::addToLog("Member " . $user->username . " has changed there email address on file.");
                return redirect()->route('profile', ['id' => $user->id])->with(Toastr::success('Your Email Was Updated Successfully!', 'Yay!', ['options']));
            }

            return redirect()->route('profile', ['id' => $user->id])->with(Toastr::error('Your Password Was Incorrect!', 'Whoops!', ['options']));
        }
    }

    public function changePID(Request $request, $id)
    {
        $user = auth()->user();
        if ($request->isMethod('post')) {
            $user->resetPasskey();
            $user->save();
            return redirect()->route('profile', ['id' => $user->id])->with(Toastr::success('Your PID Was Changed Successfully!', 'Yay!', ['options']));
        }

        return redirect()->route('profile', ['id' => $user->id])->with(Toastr::error('Something Went Wrong!', 'Whoops!', ['options']));
    }

    public function clients($id)
    {
        $user = auth()->user();
        $cli = Client::where('user_id', $user->id)->get();
        return view('user.clients', ['user' => $user, 'clients' => $cli]);
    }

    protected function authorizeClient(Request $request, $id)
    {
        $v = validator($request->all(), [
            'password' => 'required',
            'ip' => 'required|ipv4|unique:clients,ip',
            'client_name' => 'required|alpha_num',
        ]);

        $user = auth()->user();
        if ($v->passes()) {
            if (Hash::check($request->input('password'), $user->password)) {
                if (Client::where('user_id', $user->id)->get()->count() >= config('other.max_cli')) {
                    return redirect()->route('user_clients', ['id' => $user->id])->with(Toastr::error('Max Clients Reached!', 'Whoops!', ['options']));
                }
                $cli = new Client;
                $cli->user_id = $user->id;
                $cli->name = $request->input('client_name');
                $cli->ip = $request->input('ip');
                $cli->save();
                return redirect()->route('user_clients', ['id' => $user->id])->with(Toastr::success('Client Has Been Added!', 'Yay', ['options']));
            }
            return redirect()->route('user_clients', ['id' => $user->id])->with(Toastr::error('Password Invalid!', 'Whoops!', ['options']));
        }

        return redirect()->route('user_clients', ['id' => $user->id])->with(Toastr::error('All required values not received or IP is already registered by a member.', 'Whoops!', ['options']));
    }

    protected function removeClient(Request $request, $id)
    {
        $v = validator($request->all(), [
            'cliid' => 'required|exists:clients,id',
            'userid' => 'required|exists:users,id',
        ]);

        $user = auth()->user();
        if ($v->passes()) {
            $cli = Client::where('id', $request->input('cliid'));
            $cli->delete();
            return redirect()->route('user_clients', ['id' => $user->id])->with(Toastr::success('Client Has Been Removed!', 'Yay!', ['options']));
        }

        return redirect()->route('user_clients', ['id' => $user->id])->with(Toastr::error('Unable to remove this client.', 'Whoops!', ['options']));
    }

    public function getWarnings($id)
    {
        if (\App\Policy::isModerator(auth()->user())) {
            $user = User::findOrFail($id);
            $warnings = Warning::where('user_id', $user->id)->with(['torrenttitle', 'warneduser'])->latest('active')->paginate(25);
            $warningcount = Warning::where('user_id', $id)->count();
            return view('user.warninglog', ['warnings' => $warnings, 'warningcount' => $warningcount, 'user' => $user]);
        }

        abort(403, 'Unauthorized action.');
    }

    public function deactivateWarning($id)
    {
        if (\App\Policy::isModerator(auth()->user())) {
            $staff = auth()->user();
            $warning = Warning::findOrFail($id);
            $warning->expires_on = Carbon::now();
            $warning->active = 0;
            $warning->save();
            PrivateMessage::create(['sender_id' => $staff->id, 'reciever_id' => $warning->user_id, 'subject' => "Hit and Run Warning Deactivated", 'message' => $staff->username . " has decided to deactivate your warning for torrent " . $warning->torrent . " You lucked out! [color=red][b]THIS IS AN AUTOMATED SYSTEM MESSAGE, PLEASE DO NOT REPLY![/b][/color]"]);
            return redirect()->route('warninglog', ['id' => $warning->warneduser->id])->with(Toastr::success('Warning Was Successfully Deactivated', 'Yay!', ['options']));
        }

        abort(403, 'Unauthorized action.');
    }

    public function myUploads($id)
    {
        $user = User::findOrFail($id);
        if (\App\Policy::isModerator(auth()->user()) || auth()->user()->id == $user->id) {
            $torrents = Torrent::withAnyStatus()->sortable(['created_at' => 'desc'])->where('user_id', $user->id)->paginate(50);
            return view('user.uploads', ['user' => $user, 'torrents' => $torrents]);
        }

        abort(403, 'Unauthorized action.');
    }

    public function myActive($id)
    {
        $user = User::findOrFail($id);
        if (\App\Policy::isModerator(auth()->user()) || auth()->user()->id == $user->id) {
            $active = Peer::sortable(['created_at' => 'desc'])->where('user_id', $user->id)->with('torrent')->distinct('hash')->paginate(50);
            return view('user.active', ['user' => $user, 'active' => $active]);
        }

        abort(403, 'Unauthorized action.');
    }

    public function myHistory($id)
    {
        $user = User::findOrFail($id);
        if (\App\Policy::isModerator(auth()->user()) || auth()->user()->id == $user->id) {
            $his_upl = History::where('user_id', $id)->sum('actual_uploaded');
            $his_upl_cre = History::where('user_id', $id)->sum('uploaded');
            $his_downl = History::where('user_id', $id)->sum('actual_downloaded');
            $his_downl_cre = History::where('user_id', $id)->sum('downloaded');
            $history = History::sortable(['created_at' => 'desc'])->where('user_id', $user->id)->paginate(50);
            return view('user.history', ['user' => $user, 'history' => $history, 'his_upl' => $his_upl, 'his_upl_cre' => $his_upl_cre, 'his_downl' => $his_downl, 'his_downl_cre' => $his_downl_cre]);
        }

        abort(403, 'Unauthorized action.');
    }
}

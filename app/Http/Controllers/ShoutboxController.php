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
use App\Shoutbox;
use App\PrivateMessage;
use App\User;
use App\Helpers\LanguageCensor;
use Carbon\Carbon;
use Decoda\Decoda;
use \Toastr;

class ShoutboxController extends Controller
{
    /**
     * Send Shout
     *
     *
     */
   public function send(Request $request)
    {
		$string = $request->input('message');
        $checkSendRate = Shoutbox::where('user', auth()->user()->id)->where('created_at', '>=', Carbon::now()->subSeconds(1))->first();
        if ($checkSendRate) {
            return 'Wait 1 Seconds Between Posts Please';
        }

        if (auth()->user()->can_chat == 0) {
            return 'Your Chat Banned';
        }

        $v = validator($request->all(), [
            'message' => 'required|min:1|regex:/^[(a-zA-Z\-)]+$/u'
        ]);
        if ($v->fails()) {
            Toastr::error('There was a error with your input!', 'Error!', ['options']);
        }
        if ($request->ajax()) {
            preg_match_all('/(#\w+)/', $string, $mentions);
            $mentionIDs = [];
            foreach ($mentions[0] as $mention) {
                $findUser = User::where('username', 'LIKE', '%' . str_replace('#', '', $mention) . '%')->first();
                if (!empty($findUser->id)) {
                    $mentionIDs[] = $findUser['id'];
                }
            }
            $mentions = implode(',', $mentionIDs);
            if (! is_null($mentions)) {
                $insertMessage = Shoutbox::create(['user' => auth()->user()->id, 'message' => $string, 'mentions' => $mentions]);
            } else {
                $insertMessage = Shoutbox::create(['user' => auth()->user()->id, 'message' => $string]);
            }

            $flag = true;
            if (auth()->user()->image != null) {
                $avatar = '<img class="profile-avatar tiny pull-left" src="/files/img/' . auth()->user()->image . '">';
            } else {
                $avatar = '<img class="profile-avatar tiny pull-left" src="/img/profile.png">';
            }

            $flag = true;
            if (auth()->user()->isOnline()) {
                $online = '<i class="fa fa-circle text-green" data-toggle="tooltip" title="" data-original-title="User Is Online!"></i>';
            } else {
                $online = '<i class="fa fa-circle text-red" data-toggle="tooltip" title="" data-original-title="User Is Offline!"></i>';
            }

            $user_url = route('profile', ['id' => auth()->user()->id]);
            $data = '<li class="list-group-item">
      ' . ($flag ? $avatar : "") . '
      <h4 class="list-group-item-heading"><span class="badge-user text-bold"><i class="' . (auth()->user()->roleIcon()) . '" data-toggle="tooltip" title="" data-original-title="' . (auth()->user()->roleName()) . '"></i>&nbsp;
      <a style="color:' . (auth()->user()->roleColor()) . '; background-image:' . (auth()->user()->roleEffect()) . ';" href=\'' . $user_url . '\'>'
                . auth()->user()->username . '</a>
      ' . ($flag ? $online : "") . '
      </span>&nbsp;<span class="text-muted"><small><em>' . Carbon::now()->diffForHumans() . '</em></small></span>
      </h4>
      <p class="message-content">' . e($string) . '</p>
      </li>';

            cache()->forget('shoutbox_messages');
            return response()->json(['success' => true, 'data' => $data]);
        }
    }

    public static function getMessages($after = null)
    {
        $messages = cache()->remember('shoutbox_messages', 7200, function () {
            return Shoutbox::latest('id')->take(150)->get();
        });

        $messages = $messages->reverse();

        if ($messages->count() !== 0) {
            $next_batch = $messages->last()->id;
        }
        if ($after !== null) {
            $messages = $messages->filter(function ($value, $key) use ($after) {
                return $value->id > $after;
            });
        }
        return $messages;
    }

    public static function renderMessages($messages) {
       return view('chat.messages', ['messages' => $messages]);
    }

    /**
     * Fetch Shout
     *
     *
     */
    public function pluck(Request $request, $after = null)
    {
        if ($request->ajax()) {
            $messagesNext = self::getMessages($after);
            $data = self::renderMessages($messagesNext)->render();
            $next_batch = $messagesNext->last()->id ?? (int)$after;
            return response()->json(['success' => true, 'data' => $data, 'next_batch' => $next_batch]);
        }
    }

    /**
     * Delete Shout
     *
     * @param $id
     */
    public function deleteShout($id)
    {
        $shout = Shoutbox::find($id);
        if (\App\Policy::isModerator(auth()->user()) || auth()->user()->id == $shout->poster->id) {
            Shoutbox::where('id', $id)->delete();
            cache()->forget('shoutbox_messages');
            return redirect()->route('home')->with(Toastr::success('Shout Has Been Deleted.', 'Yay!', ['options']));
        } else {
            return redirect()->route('home')->with(Toastr::error('This is not your shout to delete.', 'Whoops!', ['options']));
        }
    }
}

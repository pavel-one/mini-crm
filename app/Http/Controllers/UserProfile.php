<?php

namespace App\Http\Controllers;

use App\User;
use App\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;
use NikitaKiselev\SendPulse\SendPulse;

class UserProfile extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
        $this->middleware('auth');
    }

    public function index()
    {
        $user = $this->user;
        if (!$user->photo) {
            $patch = public_path('/no-photo.png');

            if (file_exists($patch)) {
                $user->photo = Image::make(resource_path('/images/no-photo.png'))->heighten(130)->crop(130, 130);
                $user->photo->save($patch);
            }

            $user->photo = asset('/no-photo.png');
        }
        $pagetitle = 'Профиль';

        $data = [
            'user' => $user,
            'pagetitle' => $pagetitle,
            'topics' => false,
        ];
        $topics = $user->myMessages()->groupBy('user_from')->get();
        if ($topics) {
            $data['topics'] = $topics;
        }

        return view('profile.index', $data);
    }

    public function test()
    {
        dd('test');
    }

    public function getMessages($topic_id, Request $request)
    {
        /** @var UserMessage $toUser */
        $toUser = Auth::user();
        /** @var UserMessage $topic */
        $topic = UserMessage::where('id', $topic_id)->where('user_to', $toUser->id)->first();
        if (!$topic) {
            abort(404);
        }
        /** @var User $fromUser */
        $fromUser = $topic->fromUser()->firstOrFail();

        $query = UserMessage::where([
            ['user_to', '=', $toUser->id],
            ['user_from', '=', $fromUser->id]
        ]);

        $queryUnread = UserMessage::where([
            ['user_to', '=', $toUser->id],
            ['user_from', '=', $fromUser->id],
            ['read_user', '=', false],
        ]);
        if ($request->count) {
            return $queryUnread->count();
        }

        $unreadMessagess = $queryUnread->get();
        /** @var UserMessage $message */
        foreach ($unreadMessagess as $message) {
            $message->read();
        }

        $allMessages = $query->orWhere([
            ['user_from', '=', $toUser->id],
            ['user_to', '=', $fromUser->id],
        ])->get()->sortByDesc('created_at');

        $data = [
            'messages' => $allMessages,
            'topic' => $topic,
            'from' => $fromUser,
        ];
        $out = view('ajax.messages', $data);

        return $out;
    }

    public function newMessage(Request $request, $topic_id)
    {
        /** @var UserMessage $toUser */
        $toUser = Auth::user();
        /** @var UserMessage $topic */
        $topic = UserMessage::where('id', $topic_id)->where('user_to', $toUser->id)->first();
        if (!$topic) {
            abort(404);
        }
        /** @var User $fromUser */
        $fromUser = $topic->fromUser()->firstOrFail();

        return $fromUser->sendMessage($request->message);
    }

    public function profile($nick)
    {
        $user = User::where('nick', $nick)->firstOrFail();
        $pagetitle = $user->name;
        return view('profile.profilePage', compact('user', 'pagetitle'));
    }

    public function update(Request $request)
    {
        if (!$pass = $request->password) {
            $request->offsetUnset('password');
        } else {
            $request->request->set('password', bcrypt($pass));
        }
        $this->user->update($request->all());
        return $this->success('Данные обновлены!');
    }

    public function upload(Request $request)
    {
        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->allFiles()[0];
        $name = $file->getClientOriginalName();
        $path = $file->storeAs(
            'avatars/' . $this->user->id,
            $name,
            ['disk' => 'public']
        );
        $fullPath = diskFilePath('public', $path);
        $image = Image::make($fullPath)->fit(130, 130, function ($img) {
            $img->upsize();
        });
        $image->save($fullPath);
        $this->user->update([
            'photo' => $path
        ]);
        return $this->success('Аватарка обновлена');
    }

    public function send(Request $requset, $nick)
    {
        /** @var User $user */
        $text = $requset->text;

        if (!$text) {
            return $this->error('Не заполнено одно из полей');
        }

        $user = User::where('nick', $nick)->first();
        if (!$user) {
            return $this->error('Нет такого пользователя');
        }
        $user->sendMessage($text, $requset);

        return $this->success('Сообщение отправлено');

    }

    public function error($msg)
    {
        return response()->json(['success' => false, 'msg' => $msg]);
    }

    public function success($msg)
    {
        return response()->json(['success' => true, 'msg' => $msg]);
    }
}

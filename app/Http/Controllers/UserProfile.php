<?php

namespace App\Http\Controllers;

use App\User;
use App\UserMessage;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Image;

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
            'route' => Route::getFacadeRoot()->current()->getName()
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

    /**
     * Получение сообщений в чате
     * @param $topic_id
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
            'route' => Route::getFacadeRoot()->current()->getName()
        ];
        $out = view('ajax.messages', $data);

        return $out;
    }

    /**
     * @param Request $request
     * @param $topic_id
     * @return UserMessage
     */
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

        $paths = null;
        if ($files = $request->allFiles()) {
            $paths = [];
            /** @var UploadedFile $file */
            foreach ($files['files'] as $file) {
                $name = $file->getClientOriginalName();
                $name = str_replace(' ', '_', $name);
                $type = $file->getMimeType();
                $paths[] = [
                    'name' => $name,
                    'file' => $file->storeAs('messages/' . $fromUser->nick, $name, ['disk' => 'public']),
                    'mime' => $type,
                    'disk' => 'public',
                ];
            }
        }

        return $fromUser->sendMessage($request->message, $paths);
    }

    /**
     * Отправка view
     * @param $nick
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile($nick)
    {
        $user = User::where('nick', $nick)->firstOrFail();
        $pagetitle = $user->name;
        $data = [
            'pagetitle' => $pagetitle,
            'user' => $user,
            'route' => Route::getFacadeRoot()->current()->getName()
        ];
        return view('profile.profilePage', $data);
    }

    /**
     * Обновление профиля
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * Загрузка аватара в профиль
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(Request $request)
    {
        /** @var \Illuminate\Http\UploadedFile $file */
        $file = $request->allFiles()[0];
        $name = $file->getClientOriginalName();
        $name = str_replace(' ', '_', $name);
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

    /**
     * Отправка сообщения пользователю
     * @param Request $requset
     * @param $nick
     * @return \Illuminate\Http\JsonResponse
     */
    public function send(Request $requset, $nick)
    {
        $paths = null;
        if ($files = $requset->allFiles()) {
            $paths = [];
            /** @var UploadedFile $file */
            foreach ($files['files'] as $file) {
                $name = $file->getClientOriginalName();
                $name = str_replace(' ', '_', $name);
                $type = $file->getMimeType();
                $paths[] = [
                    'name' => $name,
                    'file' => $file->storeAs('messages/' . $nick, $name, ['disk' => 'public']),
                    'mime' => $type,
                    'disk' => 'public',
                ];
            }
        }
        /** @var User $user */
        $text = $requset->text;

        if (!$text) {
            return $this->error('Не заполнено одно из полей');
        }

        $user = User::where('nick', $nick)->first();
        if (!$user) {
            return $this->error('Нет такого пользователя');
        }
        $user->sendMessage($text, $paths);

        return $this->success('Сообщение отправлено');

    }

    /**
     * Скачивание файла прикрепленного к сообщению
     * @param $filename
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function msgDownloadFile($filename)
    {
        $name = 'messages/' . $this->user->nick . '/' . $filename;
        $path = diskFilePath('public', $name);
        if (!file_exists($path)) {
            return response('Вы не можете открыть этот файл, возможно потому что его отправляли вы');
        }
        $mime = mime_content_type($path);
        if (strripos($mime, 'image') !== FALSE) {
            $image = Storage::disk('public')->get($name);
            return response($image)->header('Content-Type', $mime);
        }
        return response()->download($path);
    }

    public function getcount()
    {
        return $this->user->unreadMessages()->count();
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

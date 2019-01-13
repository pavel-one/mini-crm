<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

//        dd($user);
        $pagetitle = 'Профиль';

        return view('profile.index', compact('user', 'pagetitle'));
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
        $image = Image::make($fullPath)->heighten(130)->crop(130, 130);
        $image->save($fullPath);
//        dd($path);
        $this->user->update([
            'photo' => $path
        ]);
        return $this->success('Аватарка обновлена');
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

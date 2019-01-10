<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class SudoController extends Controller
{
    public $user;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            if (!$this->user->sudo) {
                return ['success' => false, 'msg' => 'У вас не хватает прав'];
            }

            return $next($request);
        });
    }

    public function index()
    {
        return 'test';
    }
}

<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskPayment;
use App\UserLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AboutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $currentUser = Auth::user();
        $payments = TaskPayment::where('active', 0)
            ->orderBy('client_id', 'desc')
            ->get();
        $tasks = Task::where([
            ['time_tmp', '!=', 0],
            ['active', '!=', 1]
        ])
            ->orderBy('client_id', 'desc')
            ->get();
        $out = [
            'pagetitle' => 'Общая информация',
            'payments' => $payments,
            'Tasks' => $tasks,
            'User' => $currentUser,
            'route' => Route::getFacadeRoot()->current()->getName()
        ];
        if (!$currentUser->sudo) {
            return response()->redirectTo(route('crm'));
        }
        return view('crm.about', $out);
    }

    public function getLog()
    {
        $logs = UserLog::query()->limit(100)->orderBy('created_at', 'DESC')->get();
        if ($logs) {
            return view('api.log_chunk', ['logs' => formatLog($logs)]);
        }
        return $this->error('Не найден лог');
    }

    public function error($msg)
    {
        return ['success' => false, 'msg' => $msg];
    }

    public function success($msg)
    {
        return ['success' => true, 'msg' => $msg];
    }

}

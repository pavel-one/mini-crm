<?php

namespace App\Http\Controllers;

use App\CrmClient;
use App\TaskPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request, CrmClient $client)
    {
        if ((!$name = $request->name) || (!$price = $request->price)) {
            return $this->error('Не заполнено одно из полей');
        }
        $client->payments()->create($request->all());
        return $this->success('Успешно');
    }

    public function error($msg)
    {
        return ['success' => false, 'msg' => $msg];
    }

    public function success($msg)
    {
        return ['success' => true, 'msg' => $msg];
    }

    public function update(Request $request, CrmClient $client, TaskPayment $payment)
    {
        $user = Auth::user();
        if (!$user->sudo) {
            return $this->error('Вы не админ!');
        }
        $action = $request->action;

        switch ($action) {
            case 'success':
                $payment->update([
                    'active' => 1,
                    'pay_date' => date('Y-m-d  H:i')
                ]);
                return $this->success('Оплата успешно обновлена');
                break;
            case 'remove':
                $payment->delete();
                return $this->success('Оплата успешно обновлена');
                break;
            case 'refresh':
                $payment->update([
                    'active' => 0,
                    'pay_date' => null
                ]);
                return $this->success('Оплата успешно отменена');
                break;
        }
        return $this->error('Неизвестно');
    }
}

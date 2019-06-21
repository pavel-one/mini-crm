<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public $token;
    public $chat_id;
    public $user;
    public $message;
    public $name;

    public function sendMessage($chat_id, $message)
    {
        file_get_contents("https://api.telegram.org/bot{$this->token}/sendMessage?chat_id={$chat_id}&text={$message}");
    }

    public function hook(Request $request)
    {
        $this->token = env('TELEGRAM_TOKEN');
        $req_arr = $request->all();
        $message = $req_arr['message'];
        $this->chat_id = $message['chat']['id'];
        $this->message = $message;
        $this->name = $message['from']['first_name'];

        if (array_key_exists('entities', $message)) {
            $this->command();
            return response()->json(['success' => true]);
        } else {
            $this->messageHandler();
            return response()->json(['success' => true]);
        }
    }

    public function command()
    {
        $text = $this->message['text'];
        $text = explode(' ', $text);
        $command = str_replace('/', '', $text[0]);
        switch ($command) {
            case 'start':
                $msg = "Привет, {$this->name}. Введи /register и свой никнейм в CRM для регистрации";
                $this->sendMessage($this->chat_id, $msg);
                break;
            case 'register':
                if (count($text) <= 1) {
                    $msg = "Брат, ты не ввел свой никнейм после команды, я не экстрасенс";
                    $this->sendMessage($this->chat_id, $msg);
                    return false;
                }

                $nick = $text[1];
                /** @var User $findUser */
                $findUser = User::where('nick', $nick)->first();

                if (!$findUser) {
                    $msg = 'Прости, братишка, ты не найден в нашей базе, возможно тебя уже уволили?';
                    $this->sendMessage($this->chat_id, $msg);
                    return false;
                }

                $findUser->update([
                    'chat_id' => $this->chat_id
                ]);
//                $msg = 'Все ок, едем дальше';
//                $this->sendMessage($this->chat_id, $msg);
                $findUser->sendTelegram('Привет, это тестовое сообщение, если ты его получил, значит уведомления будут приходить исправно!');
                return false;
                break;
            default:
                $this->sendMessage($this->chat_id, 'Команда не найдена');
                break;
        }
    }

    public function messageHandler()
    {

        $this->sendMessage($this->chat_id, 'Такой функционал в меня еще не завезли');
        return true;
    }

    public function test(Request $r)
    {
//        $nick = $r->nick;
        /** @var User $findUser */
//        $findUser = User::where('nick', 'Kikel_D')->first();
//        $findUser->sendTelegram($r->msg);
        return time();
    }

}

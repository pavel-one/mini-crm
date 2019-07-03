<?php

namespace App\Http\Controllers;

use App\CrmClient;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VkController extends Controller
{
    protected $secret;
    protected $type;
    protected $object;
    protected $chat_id;

    public function valid()
    {
        if ($this->secret == env('VK_KEY')) {
            return true;
        }
        return false;
    }

    public function hook(Request $request)
    {
        $this->type = $request->post('type');
        $this->secret = $request->post('secret');
        $this->object = $request->post('object');
        $this->chat_id = $this->object['from_id'];
        if (!$this->valid()) return false;
//        Log::info(print_r($request->all(), 1));
        return $this->typeHandler();
    }

    public function typeHandler()
    {
        switch ($this->type) {
            case 'message_new':
                Log::info(print_r($this->object, 1));
                if (substr($this->object['text'], 0, 1) == '/') {
                    $this->command();
                } else {
                    $this->textCommand();
                }
                return 'ok';
                break;
            case 'confirmation':
                return env('VK_CONFIRM');
                break;
        }
    }

    public function command()
    {
        $text = $this->object['text'];
        $text = explode(' ', $text);
        $command = str_replace('/', '', $text[0]);
        switch ($command) {
            case 'Начать':
                $msg = "Привет, введи /register и свой никнейм в CRM для регистрации";
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
                    'vk_id' => $this->chat_id
                ]);
                $findUser->sendVK('Привет, это тестовое сообщение, если ты его получил, значит уведомления будут приходить исправно!');
                return false;
                break;
            case 'id':
                $this->setID($text);
                break;
            default:
                $this->sendMessage($this->chat_id, 'Команда не найдена');
                break;
        }
    }

    public function textCommand()
    {
//        $text = strtolower($this->object['text']);
        $this->sendMessage($this->chat_id, "Привет! Это помошник для нашей CRM. Я принимаю такие команды:
         /register [никнейм] - привязать свой ВК к профилю в CRM
         /id [client id] - привязать клиента пересланного сообщения к CRM");
    }

    public function setID($text)
    {
        $id = $text[1];
        $findUser = User::where('vk_id', $this->chat_id)->first();
        if (!$findUser) {
            $this->sendMessage($this->chat_id, 'Только пользователи с привязанным VK могут прикреплять клиентов');
            return false;
        }
        if (!$id) {
            $this->sendMessage($this->chat_id, 'После команды необходимо указать ID клиента');
            return false;
        }
        $client = CrmClient::where('id', $id)->first();
        if (!$client) {
            $this->sendMessage($this->chat_id, 'Я не смог найти такого клиента');
            return false;
        }

        if (!$forward = $this->object['fwd_messages'][0]) {
            $this->sendMessage($this->chat_id, 'Вы не переслали сообщение');
            return false;
        }
        $client_vk_id = $forward['from_id'];

        $request_params = [
            'user_id' => $client_vk_id,
            'access_token' => env('VK_TOKEN'),
            'fields' => 'photo_200',
            'v' => env('VK_VERSION')
        ];
        $q = 'https://api.vk.com/method/users.get?'.http_build_query($request_params);
        $info = json_decode(file_get_contents($q), true);
        Log::info(print_r($info, 1), ['query' => $q]);
        $this->sendMessage($this->chat_id, 'Что то делаем');
    }

    public function sendMessage($id, $msg)
    {
        $request_params = [
            'message' => $msg,
            'user_id' => $id,
            'access_token' => env('VK_TOKEN'),
            'random_id' => rand(0, 4294967295),
            'v' => env('VK_VERSION')
        ];
        $get_params = http_build_query($request_params);
        file_get_contents('https://api.vk.com/method/messages.send?' . $get_params);
    }
}

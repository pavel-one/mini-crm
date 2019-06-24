<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'photo', 'phone', 'nick', 'chat_id', 'last_notify'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function messagessClients()
    {
        return $this->hasMany('App\ClientChat', 'user_id', 'id');
    }

    public function messages()
    {
        return $this->hasMany('App\UserMessage', 'user_from', 'id');
    }

    public function myMessages()
    {
        return $this->hasMany('App\UserMessage', 'user_to', 'id');
    }

    public function messageTopics()
    {
        return $this->myMessages()->groupBy('user_from');
    }

    public function getChargeable()
    {
        return $this->hasMany('App\CrmClient', 'chargeable_user', 'id');
    }

    public function log()
    {
        return $this->hasMany('App\UserLog', 'user_id', 'id')->orderBy('created_at', 'DESC')->limit(100);
    }

    public function delete()
    {
        $this->tasks()->delete();
        $this->messagessClients()->delete();
        $this->messages()->delete();
        $this->myMessages()->delete();
        $this->log()->delete();

        $chargeable = $this->getChargeable()->get();
        if (count($chargeable)) {
            /** @var CrmClient $client */
            foreach ($chargeable as $client) {
                $client->update([
                    'chargeable_user' => null
                ]);
            }
        }

        return parent::delete();
    }

    public function sendMessage($msg, $files = null)
    {
        $message = new UserMessage;
        $message->user_to = $this->id;
        $message->user_from = Auth::user()->id;
        $message->text = $msg;
        if ($files) {
            $message->files = json_encode($files);
        }
        $message->save();
        $name = Auth::user()->name;
        $text = $message->text;

        if ($this->chat_id) {
            $msg = "*{$name} пишет вам в личные сообщения:* \n \n {$text} \n \n" . route('Profile');
            $this->sendTelegram($msg);
        }

        return $message;
    }

    public function unreadMessages($user_id = null)
    {
        if (!$user_id) {
            $col = UserMessage::where('user_to', $this->id)->where('read_user', false);
        } else {
            $col = UserMessage::where('user_to', $this->id)->where('read_user', false)->where('user_from', $user_id);
        }

        return $col;
    }

    public function save(array $options = [])
    {
        if ($this->nick) {
            $this->nick = str_replace(' ', '_', $this->nick);
        }
        return parent::save($options);
    }

    public function sendTelegram($msg)
    {
        $step_notify = env('STEP_NOTIFY');
        $msg = $msg . " \n _На {$step_notify} секунд уведомления заблокированы_";
        $msg = urlencode($msg);
        if (!$id = $this->chat_id) {
            return false;
        }
        $token = env('TELEGRAM_TOKEN');


        if ($this->last_notify) {
            $old_time = $this->last_notify;
            $current_time = time();
            $step = $current_time - $old_time;
            if ($step >= $step_notify) {
                $this->update([
                    'last_notify' => time()
                ]);
                file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$id}&text={$msg}&parse_mode=Markdown");
            }
        } else {
            $this->update([
                'last_notify' => time()
            ]);
            file_get_contents("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$id}&text={$msg}&parse_mode=Markdown");
        }


        return true;
    }
}

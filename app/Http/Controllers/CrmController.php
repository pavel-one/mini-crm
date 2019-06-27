<?php

namespace App\Http\Controllers;

use App\CrmClient;
use App\Events\onCreateClientEvent;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Image;

class CrmController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Листинг клиентов
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $list = CrmClient::orderBy('active', 'desc')->get();
        return view('crm.list', [
            'pagetitle' => 'Список клиентов',
            'clients' => $list,
            'user' => Auth::user(),
            'route' => Route::getFacadeRoot()->current()->getName()
        ]);
    }

    /**
     * Обновление фотографии клиента
     * @param CrmClient $client
     * @param Request $request
     * @return array
     */
    public function UpdatePhoto(CrmClient $client, Request $request)
    {
        /** @var UploadedFile $photo */
        $photo = $request->allFiles()[0];
        if (!$photo) {
            $this->error('Нет фото');
        }
        $name = $photo->getClientOriginalName();
        $name = str_replace(' ', '_', $name);

        $path = $photo->storeAs(
            'client_photo/' . $client->id,
            $name,
            ['disk' => 'public']
        );
        $fullPath = diskFilePath('public', $path);
        $photo = Image::make($fullPath)->fit(750, 300, function ($img) {
            $img->upsize();
        });
        $photo->save($fullPath);
        $client->update([
            'photo' => $path
        ]);
        return $this->success('Успешно загружено');
    }

    /**
     * @param CrmClient $client
     * @param Request $request
     * @return array
     */
    public function UpdateFiles(CrmClient $client, Request $request)
    {
        $files = $request->allFiles();
        $old_files_data = $client->files;
        if ($old_files_data) {
            $old_files_data = json_decode($old_files_data, true);
        }

        if (!$files) {
            return $this->error('Не загружены файлы');
        }
        /** @var UploadedFile $file */
        foreach ($files as $file) {
            $name = $file->getClientOriginalName();
            $name = str_replace(' ', '_', $name);
            $path = $file->storeAs(
                'files_admin/' . $client->id,
                $name,
                ['disk' => 'public']
            );
            $old_files_data[] = [
                'size' => formatSize($file->getSize()),
                'name' => $name,
                'path' => $path
            ];
        }
        $client->update([
            'files' => json_encode($old_files_data)
        ]);
        return $this->success('Успешно загружено');
    }

    /**
     * Скачивание реквизитов клиента
     * @param CrmClient $client
     * @param $filename
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    function DownloadFile(CrmClient $client, $filename)
    {
        $files = $client->files;
        if (!$files) {
            return response('Нет файлов у клиента что вы вообще пытаетесь сделать?!?!?!');
        }
        $files = json_decode($files, true);
        foreach ($files as $file) {
            if ($file['name'] == $filename) {
                return response()->download(diskFilePath('public', $file['path']));
            }
        }
        return response('Странно, но я не нашел файл с таким именем');
    }

    /**
     * Удаление реквизитов клиента
     * @param CrmClient $client
     * @param $filename
     * @return array
     */
    function RemoveFile(CrmClient $client, $filename)
    {
        $files = $client->files;
        if (!$files) {
            return $this->error('Нет файлов у клиента что вы вообще пытаетесь сделать?!?!?!');
        }
        $files = json_decode($files, true);
        foreach ($files as $key => $file) {
            if ($file['name'] == $filename) {
                Storage::disk('public')->delete($file['path']);
                unset($files[$key]);
                $client->update([
                    'files' => json_encode($files)
                ]);
                return $this->success('Файл успешно удален');
            }
        }
        return $this->error('Странно, но я не нашел файл с таким именем');
    }

    /**
     * Страница клиента
     * @param CrmClient $client
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ClientPage(CrmClient $client)
    {
        $access = $client->access()
            ->get();
        $payments = $client->payments()
            ->get();
        $messagess = $client->messagess()
            ->get()
            ->sortByDesc('created_at');
        $users = User::select('id', 'name')->get();

        $tasks = $client->tasks()
            ->orderBy('active', 'asc')
            ->orderBy('time', 'desc')
            ->get();

        $allTime = 0;
        if (count($tasks)) {
            foreach ($tasks as $task) {
                $allTime += $task->time;
            }
        }

        $files = $client->files()
            ->get();
        $fileSizes = 0;
        if (count($files)) {
            foreach ($files as $file) {
                $fileSizes += $file->size;
            }
        }

        $clientActive = $client->payments()
            ->where('active', 1)
            ->get();
        $clientPrice = 0;
        if (count($clientActive)) {
            foreach ($clientActive as $item) {
                $clientPrice += $item->price;
            }
        }

        $params = [
            'pagetitle' => $client->name,
            'client' => $client,
            'access' => false,
            'tasks' => false,
            'payments' => false,
            'files' => false,
            'messagess' => false,
            'fileSizes' => $fileSizes,
            'clientPrice' => $clientPrice,
            'allTime' => $allTime,
            'allUsers' => $users,
            'percent' => $client->getPercent(),
            'user' => Auth::user(),
            'route' => Route::getFacadeRoot()->current()->getName()
        ];

        if (count($access)) {
            $params['access'] = $access;
        }
        if (count($tasks)) {
            $params['tasks'] = $tasks;
        }
        if (count($payments)) {
            $params['payments'] = $payments;
        }
        if (count($files)) {
            $params['files'] = $files;
        }
        if (count($messagess)) {
            $params['messagess'] = $messagess;
        }

        return view('crm.page', $params);
    }

    /**
     * @param CrmClient $client
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPercent(CrmClient $client)
    {
        return response()->json($client->getPercent());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $client = CrmClient::create($request->all());

        $event = new onCreateClientEvent($client, Auth::user());
        Event::fire($event);
        return redirect()->route('CrmPage', $client->id);
    }

    /**
     * @param CrmClient $client
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function access(CrmClient $client, Request $request)
    {
        if ($request->all) {
            return $this->_accessCreates($request->all, $client);
        }

        $access = $client->access()->find($request->id);
        if (!$access) {
            return ['success' => false, 'msg' => 'Не найдено'];
        }

        $val = $request->value;
        $id = $request->id;
        $name = $request->name;

        if (!$val && $id !== 0) {
            $access->delete();
            return ['success' => true, 'msg' => 'Успешно удалено'];
        }

        $access->update([
            'name' => $name,
            'value' => $val
        ]);


        return ['success' => true, 'msg' => 'Успешно'];
    }

    /**
     * Создание доступов клиента
     * @param array $data
     * @param CrmClient $client
     * @return array
     */
    public function _accessCreates(array $data, CrmClient $client)
    {
        foreach ($data as $item) {
            $client->access()->create([
                'name' => $item['name'],
                'value' => $item['value']
            ]);
        }
        return ['success' => true, 'msg' => 'Доступы успешно созданы'];
    }

    public function update(CrmClient $client, Request $request)
    {
        $user = Auth::user();
        if (!$user->sudo) {
            return [
                'success' => false,
                'msg' => 'У вас не хватает прав',
            ];
        }

        $client->update($request->toArray());

        return [
            'success' => true,
            'msg' => 'Успешно сохранено',
            'obj' => $client->toArray()
        ];
    }

    /**
     * Удаление клиента
     * @param CrmClient $client
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(CrmClient $client)
    {
        $user = Auth::user();
        if (!$user->sudo) {
            return redirect()->route('CrmPage', $client->id);
        }
        if ($client->delete()) {
            return redirect()->route('crm');
        }
        return redirect()->route('CrmPage', $client->id);
    }

    /**
     * Функция обработки действий с клиентом
     * @param CrmClient $client
     * @param Request $request
     * @return array
     */
    public function actions(CrmClient $client, Request $request)
    {
        $user = Auth::user();
        $actions = $request->action;
        if (!$actions) {
            return $this->error('Не задано действие');
        }
        if (!$user->sudo) {
            return $this->error('Вы не имеете прав для этого действия');
        }

        switch ($actions) {
            case 'switchactive':
                $newActive = $client->active;
                if ($newActive == 1) {
                    $newActive = false;
                    $client->update([
                        'active' => $newActive
                    ]);
                } else {
                    $newActive = true;
                    $client->update([
                        'active' => $newActive,
                        'start' => date('Y-m-d'),
                    ]);
                }

                return $this->success('Успешно!');
                break;
            case 'deadline':
                if (!$date = $request->date) {
                    return $this->error('Не задана дата дедлайна');
                }
                $client->update([
                    'dead' => $request->date,
                ]);
                return $this->success('Дата дедлайна успешно установлена!');
                break;
            case 'user_chargeable':
                if (!$user_id = $request->user_id) {
                    return $this->error('Не задан пользователь');
                }
                $client->update([
                    'chargeable_user' => $user_id,
                ]);
                /** @var User $user */
                $user = $client->getChargeable()->first();
                $link = route('CrmPage', $client->id);
                $user->sendTelegram("Вы стали ответственным за клиента «{$client->name}» \n $link");
                Mail::send('emails.chargeable',
                    ['client' => $client->toArray(), 'user' => $user->toArray()],
                    function ($m) use ($user) {
                        $m->to($user->email, 'CRM SK')->subject('Ты ответственный!');
                    });
                return $this->success('Ответственный успешно задан');
                break;
        }
    }

    /**
     * Функция получения действий пользователей по карточке
     * @param CrmClient $client
     * @return array|\Illuminate\Database\Eloquent\Collection
     */
    public function getLog(CrmClient $client)
    {
        $log = $client->log()->limit(100)->get();
        if ($log) {
            return view('api.log_chunk', ['logs' => formatLog($log)]);
        }
        return $this->error('Не найден лог');
    }

    /**
     * Ответ ошибки
     * @param $msg
     * @return array
     */
    public function error($msg)
    {
        return ['success' => false, 'msg' => $msg];
    }

    /**
     * Успешный ответ
     * @param $msg
     * @return array
     */
    public function success($msg)
    {
        return ['success' => true, 'msg' => $msg];
    }

}

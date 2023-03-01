<?php

namespace App\Http\Controllers;

use App\CrmClient;
use App\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskFilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upload(Request $request, CrmClient $client)
    {
        /** @var \Illuminate\Http\UploadedFile $file */
        foreach ($request->allFiles() as $file) {
            $name = $file->getClientOriginalName();
            $path = $file->storeAs(
                'clients/' . $client->id,
                $name,
                ['disk' => 'public']
            );
            $client->files()->create([
                'name' => $name,
                'patch' => $path,
                'size' => $file->getSize(),
            ]);
        }
        return $this->success('Успешно загружено');
    }

    public function update(Request $request, TaskFile $upload)
    {
        $action = $request->action;
        switch ($action) {
            case 'file-rename':
                $newName = $request->new_name;
                $newName = str_replace(' ', '_', $newName);

                $client_id = $upload->client_id;
                $count_name = TaskFile::where('client_id', $client_id)
                    ->where('name', $newName)
                    ->count();
                if ($count_name) {
                    return $this->error('Файл с таким именем уже существует');
                }

                $upload->update(['name' => $newName]);
                return $this->success('Успешно переименовано');
                break;
            case 'remove':
                Storage::disk('public')->delete($upload->patch);
                $upload->delete();
                return $this->error('Успешно удалено');
                break;
        }
        return $this->error('Ошибка');
    }

    public function download(CrmClient $client, Request $request)
    {
        $client_id = $client->id;
        if (!$name = $request->name) {
            return redirect()->route('CrmPage', $client_id);
        }
        /** @var TaskFile $file */
        $file = $client
            ->files()
            ->where('client_id', $client_id)
            ->where('name', $name)
            ->first();
        $path = diskFilePath('public', $file->patch);
        return response()->download($path);
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

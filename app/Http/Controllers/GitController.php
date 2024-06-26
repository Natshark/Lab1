<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Cache;

class GitController extends Controller
{
    public function handleHook(Request $request)
    {
        if ($request->input('secret_key') === env('SECRET_KEY'))
        {
            $lock = Cache::lock('git-update-lock', 300);
            if ($lock->get())
            {
                try
                {
                    Log::info('Дата: ' . now() . ' IP:' . $request->ip());

                    $this->runGitCommand(['git', 'reset', '--hard'], 'Отмена всех изменений, в случае если они присутствовали');
                    $this->runGitCommand(['git', 'checkout', 'main'], 'Переключение на главную ветку проетка в git');
                    $this->runGitCommand(['git', 'pull', 'origin', 'main'], 'Обновление проекта с гита до последней актуальной версии');

                    return response()->json('Все действия выполнены успешно', 200);
                }
                catch (Exception $e)
                {
                    return response()->json($e->getMessage(), 500);
                }
                finally
                {
                    $lock->release();
                }
            }
            else
            {
                return response()->json('Выполнение обновления возможно только из одного потока');
            }
        }
        else
        {
            return response()->json('Неверный ключ', 403);
        }
    }

    private function runGitCommand(array $command, string $successMessage)
    {
        $process = new Process($command);
        $process->run();

        if (!$process->isSuccessful())
        {
            throw new ProcessFailedException($process);
        }

        Log::info($successMessage);
    }
}

<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendTaskReminderNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task_reminder:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Log::info("Schedule task reminder notification");
        $tasks = Task::with('user')
            ->where('reminder_at', '<=', now()->toDateTimeString())
            ->get();

        foreach ($tasks as $task) {
            try {
                $task->user->notify(new TaskReminderNotification($task));
                $task->update(['reminder_at' => null]);
                Log::info('Send task reminder notification: ' . $task->name);
            } catch (\Throwable $th) {
                throw $th;
                Log::error($th);
            }
        }
        Log::info("End schedule task reminder notification");

        return 0;
    }
}

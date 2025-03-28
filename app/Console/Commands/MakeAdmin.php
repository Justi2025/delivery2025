<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Khranilischa\RoliPolzovatelei;
use App\Khranilischa\UserStatus;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class MakeAdmin extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:make-admin {login}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Делает админом';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $login = $this->argument('login');
        $user = User::where(['phone' => $login]);

        if (!$user->exists()) {
            $this->error('Нет логина такого');
        } else {
            $user->update(['role' => RoliPolzovatelei::Ahmad->value, 'status' => UserStatus::Activated->value]);
            $this->info("Админ выдан для логина '$login'");
        }


    }
}

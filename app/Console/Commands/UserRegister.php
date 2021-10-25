<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;


class UserRegister extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:register';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Register new user.';

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
        $email = $this->ask('Enter email');
        $existingUser = User::where('email', $email)->first();
        if($existingUser !== null) {
            $this->line('<fg=red>User already exist.</>');
            return 1;
        }

        $password = $this->readPassword();
        $surname = $this->ask('Enter surname.');
        $name = $this->ask('Enter name.');
        $patronymic = $this->ask('Enter patronymic.');

        $user = new User();
        $user->fill([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'surname' => $surname,
                'patronymic' => $patronymic,
            ]);
        $user->save();

        $this->line(sprintf("User id=%d created successfully",$user->id));

        return 0;
    }

    private function readPassword(){
        while(true){
            $password = $this->secret('Enter password');
            $confirmation = $this->secret('Enter password again');
            if ($password === $confirmation){
                return $password;
            }else{
                $this->line('Password mismatch');
            }
        }
    }
}

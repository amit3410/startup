<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Event;
use App\Inv\Repositories\Contracts\UserInterface as InvUserRepoInterface;

class DexterCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Dexter:cron';

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
    public function __construct(InvUserRepoInterface $user)
    {
        parent::__construct();
        $this->userRepo = $user;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        \Log::info("Cron is working fine!");
     
        
        $userArr = [];
       // $userArr = $this->userRepo->find($userId, ['email', 'first_name', 'last_name']);
        
        $verifyUserArr = [];
        $verifyUserArr['name'] = 'santosh'. ' ' .'kumar';
        $verifyUserArr['email'] = 'santosh@gmail.com';
        
        Event::dispatch("user.testmsg", serialize($verifyUserArr));
       
    }
}

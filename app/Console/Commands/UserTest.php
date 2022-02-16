<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Alerta;
use App\Models\Documento;

use GuzzleHttp\Client;

class UserTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:collect {user}';

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
        $user = User::find($this->argument('user'));
        $client = new Client();

      
            $documents = Documento::where('user_id' , $user->id)->where('monitoring' , true)->get();
            foreach ($documents as $document) {
                $response = $client->request('POST', route('console.copyleaks.submit'), [
                    'headers' => [
                        'Accept'     => 'application/json',
                    ],
                    'form_params' => [
                        'title' => $document->titulo,
                        'body' => $document->contenido,
                        'console' => true,
                        'user_id' => $user->id,
                        'documento_id' => $document->id,
                        // 'debug' => true,
                    ]
                ]);
    
            }
                
            
    }
}

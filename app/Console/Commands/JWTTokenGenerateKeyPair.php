<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class JWTTokenGenerateKeyPair extends Command
{




    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:jwt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates key pair base64encoded';

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
     * @return mixed
     */
    public function handle()
    {
        $keyPair = \sodium_crypto_sign_keypair();
        $privateKey = base64_encode(\sodium_crypto_sign_secretkey($keyPair));
        $publicKey = base64_encode(\sodium_crypto_sign_publickey($keyPair));

        $path = base_path('.env');

        if (file_exists($path)) {
            file_put_contents($path, str_replace('JWT_PUBLIC='.$this->laravel['config']['jwt.public'], 'JWT_PUBLIC='.$publicKey, file_get_contents($path)));
            file_put_contents($path, str_replace('JWT_PRIVATE='.$this->laravel['config']['jwt.private'], 'JWT_PRIVATE='.$privateKey, file_get_contents($path)));
        }

    }
}
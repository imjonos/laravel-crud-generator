<?php
/**
 * CodersStudio 2019
 * https://coders.studio
 * info@coders.studio
 */

namespace CodersStudio\CRUD\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

/**
 * Class CRUDGenerate
 * @package CodersStudio\CRUD\Console\Commands
 */
class CRUDDepInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installing CRUD dependencies';

    protected $commands = [
        'npm i --save' => [
            'vee-validate@2.2.15',
            'vue-notification',
            'lodash',
            'bootstrap-vue',
            '@fortawesome/fontawesome-free',
            'vue-flatpickr-component',
            'vue2-editor',
            'vue2-dropzone',
            'vuex',
            'vue-multiselect'
            ],

    ];
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
     * @return void
     */
    public function handle(): void
    {
        echo "Installing dependencies...\n";
        foreach ($this->commands as $key => $command) {
            foreach ($command as $pr) {
                $process = new Process('cd '.base_path().' && '.$key.' '.$pr);
                $process->start();

                foreach ($process as $type => $data) {
                    if ($process::OUT === $type) {
                        echo "\nRead from stdout: ".$data;
                    } else { // $process::ERR === $type
                        echo "\nRead from stderr: ".$data;
                    }
                }
            }
        }

        echo "Writing imports...\n";
        $appjs = file_get_contents(resource_path('js/app.js'));
        if (!strpos($appjs,'require(\'./vendor/codersstudio/crud/index\');')) {
            $appjs= str_replace('const app = new Vue({', "require('./vendor/codersstudio/crud/index');\n\nconst app = new Vue({",$appjs);
            file_put_contents(resource_path('js/app.js'),$appjs);
        }
        $appscss = file_get_contents(resource_path('sass/app.scss'));
        if (!strpos($appscss,'@import \'./vendor/codersstudio/crud/index\';')) {
            $appscss= str_replace('@import \'~bootstrap/scss/bootstrap\';', "@import '~bootstrap/scss/bootstrap';\n@import './vendor/codersstudio/crud/index';\n@import '~@fortawesome/fontawesome-free/css/all.css';",$appscss);
            file_put_contents(resource_path('sass/app.scss'),$appscss);
        }
        echo "Publishing dummies...\n";
        $process = new Process('cd '.base_path().' && php artisan vendor:publish --tag=crud.views && php artisan vendor:publish --tag=crud.js && php artisan vendor:publish --tag=crud.sass');
        $process->start();
        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                echo "\nRead from stdout: ".$data;
            } else { // $process::ERR === $type
                echo "\nRead from stderr: ".$data;
            }
        }
    }
}

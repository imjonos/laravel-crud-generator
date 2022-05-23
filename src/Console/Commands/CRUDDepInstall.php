<?php
/**
 * Eugeny Nosenko 2021
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Console\Commands;

use Illuminate\Console\Command;

/**
 * Class CRUDGenerate
 * @package Nos\CRUD\Console\Commands
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
            'vue-flatpickr-component@8.x',
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
        $this->info("Installing dependencies...");
        foreach ($this->commands as $key => $command) {
            foreach ($command as $pr) {
                $output = null;
                exec('cd '.base_path().' && '.$key.' '.$pr, $output);
            }
        }

        $this->info( "Writing imports...");
        $appjs = file_get_contents(resource_path('js/app.js'));
        if (!strpos($appjs,'require(\'./vendor/nos/crud/index\');')) {
            $appjs= str_replace('const app = new Vue({', "require('./vendor/nos/crud/index');\n\nconst app = new Vue({",$appjs);
            file_put_contents(resource_path('js/app.js'),$appjs);
        }
        $appscss = file_get_contents(resource_path('sass/app.scss'));
        if (!strpos($appscss,'@import \'./vendor/nos/crud/index\';')) {
            $appscss= str_replace('@import \'~bootstrap/scss/bootstrap\';', "@import '~bootstrap/scss/bootstrap';\n@import './vendor/nos/crud/index';\n@import '~@fortawesome/fontawesome-free/css/all.css';",$appscss);
            file_put_contents(resource_path('sass/app.scss'),$appscss);
        }
        $this->info("Publishing dummies...");
        $output = null;
        exec('cd '.base_path().' && php artisan vendor:publish --tag=crud.views && php artisan vendor:publish --tag=crud.js && php artisan vendor:publish --tag=crud.sass', $output);
    }
}

<?php
/**
 * Eugeny Nosenko 2021
 * https://toprogram.ru
 * info@toprogram.ru
 */

namespace Nos\CRUD\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;


/**
 * Class CRUDGenerate
 * @package Nos\CRUD\Console\Commands
 */
class CRUDGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate  {table : Table name from DB} {--route=admin} {--force=0} {--import=0} {--export=0} {--alternative=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CRUD generate: Controller, Views, Model, Route, Request';

    /**
     * @var array
     */
    protected $viewsList = ['index', 'create', 'edit', 'show', 'table', 'filters'];

    /**
     * @var array
     */
    protected $requestsList = [
        [
            'name' => 'Index',
            'rules' => null
        ],
        [
            'name' => 'Create',
            'rules' => null
        ],
        [
            'name' => 'Edit',
            'rules' => null
        ],
        [
            'name' => 'Show',
            'rules' => null
        ],
        [
            'name' => 'Destroy',
            'rules' => null
        ],
        [
            'name' => 'MassDestroy',
            'rules' => 'massdestroy'
        ],
        [
            'name' => 'ToggleBoolean',
            'rules' => 'toggleboolean'
        ],
        [
            'name' => 'Update',
            'rules' => 'update'
        ],
        [
            'name' => 'Store',
            'rules' => 'store'
        ],
        [
            'name' => 'Export',
            'rules' => null
        ],
        [
            'name' => 'Import',
            'rules' => 'importfile'
        ]
    ];

    /**
     * @var array
     */
    protected $filedTypes = [
        "checkbox" => ["tinyint", "boolean", "bool"],
        "date" => ["date", "datetime", "timestamp"],
        "number" => ["smallint", "mediumint", "int", "bigint", "float", "double"],
        "radio" => [],
        "select" => [],
        "text" => ["char", "varchar", "string"],
        "textarea" => ["text", "tinytext", "mediumtext", "bigtext", "blob", "tinyblob", "mediumblob", "bigblob"]
    ];

    /**
     * Columns array. Ex. ['name' =>
     *                          [
     *                              'name' => 'some',
     *                              'type' => 'bigint',
     *                              'required' => true,
     *                              'unique' => false,
     *                              'foreign' => 'sometable.somefield'
     *                          ]
     *                      ]
     * @var array
     */
    protected $columns = [];

    /**
     * Column names not needed for Create/Update forms
     *
     * @var array
     */
    protected $systemColumns = ['id', 'created_at', 'updated_at', 'deleted_at', 'remember_token'];

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var int
     */
    protected $force;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var int
     */
    protected $import;

    /**
     * @var int
     */
    protected $export;

    /**
     * @var int
     */
    protected $alternative;

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
        $this->tableName = $this->argument('table');
        $this->route = $this->option('route');
        $this->force = $this->option('force');
        $this->import = $this->option('import');
        $this->export = $this->option('export');
        $this->alternative = $this->option('alternative');

        if (!Schema::hasTable($this->tableName)) {
            $this->info("Wrong table name! Aborting!");

            return;
        }
        $this->columns = $this->getColumns();
        $name = ucfirst(Str::camel($this->tableName));
        $this->info("CRUD generate start");
        $this->controller($name);
        $this->info("Controller done!");
        $this->test($name);
        $this->info("Test done!");
        $this->model($name);
        $this->info("Model done!");
        $this->repository($name);
        $this->info("Repository done!");
        $this->service($name);
        $this->info("Service done!");
        $this->request($name);
        $this->info("Request done!");
        $this->views($name);
        $this->info("Views done!");
        $this->languages($name);
        $this->info("Languages done!");
        $this->components($name);
        $this->info("VueJS done!");
        $this->routes($name);
        $this->info("Route done!");
        $this->menu($name);
        $this->info("Menu done!");
        $this->factory($name);
        $this->info("Factory done!");
        $this->seed($name);
        $this->info("Seed done!");

        if ($this->import) {
            $this->import($name);
            $this->info("Import done!");
        }
        if ($this->export) {
            $this->export($name);
            $this->info("Export done!");
        }
    }

    /**
     * Get columns list from db
     * @param string $tableName
     * @return array
     */
    protected function getColumns(string $tableName = ""): array
    {
        if (!$tableName) {
            $tableName = $this->tableName;
        }
        $indexes = collect(Schema::getConnection()->getDoctrineSchemaManager()->listTableIndexes($tableName));
        $foreign = Schema::getConnection()->getDoctrineSchemaManager()->listTableForeignKeys($tableName);
        $columns = Schema::getColumnListing($tableName);
        $result = [];
        foreach ($columns as $column) {
            $unique = false;
            foreach ($indexes as $index) {
                if (in_array($column, $index->getColumns()) && ($index->isUnique() && !$index->isPrimary())) {
                    $unique = true;
                }
            }
            $forKey = '';
            foreach ($foreign as $fkey) {
                if (in_array($column, $fkey->getLocalColumns())) {
                    $forKey = $fkey->getForeignTableName() . '.' . $fkey->getForeignColumns()[0];
                }
            }
            $result[$column] = [
                'name' => $column,
                'type' => Schema::getColumnType($tableName, $column),
                'required' => boolval(Schema::getConnection()->getDoctrineColumn($tableName, $column)->getNotnull()),
                'unique' => $unique,
                'foreign' => $forKey,
            ];

            $result[$column]['input'] = $this->getFieldInputType($result[$column]);
            if ($result[$column]['input'] == "select") {
                $result[$column]['belongsTo']['name'] = Str::camel(str_replace("_id", "", $result[$column]['name']));
                $result[$column]['belongsTo']['model'] = ucfirst(Str::camel($result[$column]['belongsTo']['name']));
            }

            if (!in_array($column, $this->systemColumns)) {
                $result[$column]['rules'] = [
                    'store' => $this->generateStoreRules($result[$column]),
                    'update' => $this->generateUpdateRules($result[$column]),
                    'toggleboolean' => $result[$column]['type'] == 'boolean' ? 'required|boolean' : []
                ];
            }

            if ($column == 'id') {
                $result[$column]['rules'] = [
                    'store' => [],
                    'update' => [],
                    'massdestroy' => 'exists:' . $tableName . ',id'
                ];
            }
        }

        return $result;
    }

    /**
     * Get field input type
     * @param array $column
     * @return string
     */
    protected function getFieldInputType(array $column = []): string
    {
        $result = "text";
        if (count($column)) {
            if ($column['name'] == "password") {
                $result = "password";
            } else {
                if (stristr($column['name'], "_id")) {
                    $result = "select";
                } else {
                    foreach ($this->filedTypes as $key => $types) {
                        if (in_array($column['type'], $types)) {
                            $result = $key;
                            break;
                        }
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param $column array Element of $this->columns
     * @return array
     */
    protected function generateStoreRules(array $column)
    {
        $result = [];
        if ($column['required']) {
            $result[] = 'required';
        } else {
            $result[] = 'nullable';
        }
        if ($column['name'] == 'email') {
            $result[] = 'email';
        }
        if ($column['name'] == 'password') {
            $result[] = 'min:7|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/';
        }
        if ($column['unique'] || $column['name'] == 'slug') {
            $result[] = 'unique:' . $this->tableName . ',' . $column['name'];
        }
        $result = $this->getTypeSpecificRules($column, $result);

        return implode('|', $result);
    }

    /**
     * Add column type specific rules
     *
     * @param $column
     * @param $rules
     * @return array
     */
    protected function getTypeSpecificRules($column, $rules)
    {
        $integerTypes = [
            'integer',
            'tinyint',
            'smallint',
            'mediumint',
            'bigint',
            'unsignedInteger',
            'unsignedTinyInteger',
            'unsignedSmallInteger',
            'unsignedMediumInteger',
            'unsignedBigInteger'
        ];
        $numericTypes = [
            'float',
            'decimal',
        ];
        $datetimeTypes = [
            'datetime',
            'date',
            'timestamp'
        ];
        $stringTypes = [
            'string',
            'varchar',
            'text'
        ];
        if (in_array($column['type'], $integerTypes)) {
            $rules[] = 'integer';
        } else {
            if (in_array($column['type'], $numericTypes)) {
                $rules[] = 'numeric';
            } else {
                if (in_array($column['type'], $datetimeTypes)) {
                    $rules[] = 'date_format:Y-m-d H:i:s';
                } else {
                    if (in_array($column['type'], $stringTypes)) {
                        $rules[] = 'string';
                    } else {
                        if ($column['type'] === 'time') {
                            $rules[] = 'date_format:H:i:s';
                        } else {
                            if ($column['type'] === 'boolean') {
                                $rules[] = 'boolean';
                            } else {
                                $rules[] = 'string';
                            }
                        }
                    }
                }
            }
        }

        return $rules;
    }

    /**
     * @param $column array Element of $this->columns
     * @return array
     */
    protected function generateUpdateRules(array $column)
    {
        $result = [];
        if ($column['required']) {
            $result[] = 'sometimes';
        } else {
            $result[] = 'nullable';
        }
        if ($column['name'] == 'email') {
            $result[] = 'email';
        }
        if ($column['name'] == 'password') {
            $result[] = 'min:7|confirmed|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9]).*$/';
        }
        if ($column['unique'] || $column['name'] == 'slug') {
            $result[] = 'unique:' . $this->tableName . ',' . $column['name'] . ',\' . $this->id .\'';
        }
        $result = $this->getTypeSpecificRules($column, $result);

        return implode('|', $result);
    }

    /**
     * Create the controller
     * @param string $name
     */
    protected function controller(string $name): void
    {
        $pathName = ($this->route) ? ucfirst($this->route) : '';
        $customPath = ($pathName) ? $pathName . '/' : '';
        $namespacePath = ($pathName) ? '\\' . $pathName : '';
        $pathViews = ($pathName) ? $pathName . '.' . $this->tableName : $this->tableName;
        $singularName = Str::singular($name);
        $fields = '';
        $selected = '';
        $uses = '';
        $with = '';
        foreach ($this->columns as $column) {
            if ($column['name'] == 'id' || !in_array(
                    $column['name'],
                    $this->systemColumns
                ) && $column['name'] != 'password') {
                $fields .= '\'' . $column['name'] . '\',' . PHP_EOL . '            ';
                $selected .= '\'' . $column['name'] . '\'' . ' => $request->get(' . '\'' . $column['name'] . '\'' . '),' . PHP_EOL . '                ';
                if ($column['input'] == "select") {
                    $with .= "'" . $column['belongsTo']['name'] . "',";
                    $uses .= "use App\\Models\\" . $column['belongsTo']['model'] . ";" . PHP_EOL;
                }
            }
        }

        if ($with) {
            $with = '->with([' . substr($with, 0, -1) . '])';
        }

        $controllerTemplate = $this->makeTemplate(
            [
                '{{modelName}}',
                '{{modelNamePluralLowerCase}}',
                '{{modelNameSingularLowerCase}}',
                '{{tableName}}',
                '{{fields}}',
                '{{selected}}',
                '{{uses}}',
                '{{with}}',
                '{{namespacePath}}'
            ],
            [
                Str::singular($name),
                strtolower($pathViews),
                strtolower($singularName),
                $this->tableName,
                $fields,
                $selected,
                $uses,
                $with,
                $namespacePath
            ],
            'Controller'
        );
        if (!file_exists(app_path("Http/Controllers/{$customPath}"))) {
            mkdir(app_path("Http/Controllers/{$customPath}"), 0755, true);
        }
        $this->writeToFile(
            app_path("Http/Controllers/{$customPath}{$singularName}Controller.php"),
            $controllerTemplate
        );
    }

    /**
     * Make template from file stub
     * @param array $templateVars
     * @param array $vars
     * @param string $stubName
     * @return string
     */
    protected function makeTemplate(array $templateVars, array $vars, string $stubName): string
    {
        //TODO добавить глобально используемые переменные
        return str_replace(
            $templateVars,
            $vars,
            $this->getStub($stubName)
        );
    }

    /**
     * Read the template
     * @param $type
     * @return false|string
     */
    protected function getStub(string $type)
    {
        return File::get(__DIR__ . "/../../../resources/stubs/$type.stub");
    }

    /**
     * Write to file
     * @param string $path
     * @param string $content
     * @param bool $overwrite
     */
    protected function writeToFile(string $path, string $content, $overwrite = false): void
    {
        if ($this->force == 1 || !file_exists($path) || $overwrite) {
            File::put($path, $content);
        } elseif ($this->alternative) {
            $name = basename($path);
            File::put(str_replace($name, 'Alternative' . $name, $path), $content);
        }
    }

    /**
     * Create the test
     * @param string $name
     */
    protected function test(string $name): void
    {
        $pathName = ($this->route) ? ucfirst($this->route) : '';
        $customPath = ($pathName) ? strtolower($pathName) . '.' : '';
        $singularName = Str::singular($name);

        if (!file_exists($path = base_path('/tests'))) {
            mkdir($path, 0775, true);
        }

        if (!file_exists($path = base_path('/tests/Feature'))) {
            mkdir($path, 0775, true);
        }

        if (!file_exists($path = base_path('/tests/Feature/Admin'))) {
            mkdir($path, 0775, true);
        }

        $TestCase = $this->makeTemplate([], [], 'TestCase');
        $this->writeToFile(base_path("/tests/TestCase.php"), $TestCase);

        $fieldsIndex = '';
        $fieldsStore = '';
        $unset = '';
        $fieldForUpdate = '';
        foreach ($this->columns as $column) {
            if (!in_array($column['name'], $this->systemColumns)) {
                if ($column['name'] !== 'password') {
                    $fieldsIndex .= '\'' . $column['name'] . '\',' . PHP_EOL . '                    ';
                }

                if ($column['name'] !== 'email_verified_at' && $column['name'] !== 'password') {
                    if (!$fieldForUpdate && $column['type'] == 'string') {
                        $fieldForUpdate = $column['name'];
                    }
                }
            }
        }

        foreach ($this->columns as $column) {
            if (!in_array($column['name'], $this->systemColumns) && $column['input'] !== 'select') {
                //TODO remake to "ifelse" and leave one concatenation outside the logical module
                $fieldsStore .= '\'' . $column['name'] . '\' => $this->faker->';
                //the beginning of the variable part
                $fieldsStore .= ($column['unique'] ? 'unique()->' : '');
                $fieldsStore .= ($column['type'] === 'int' ? 'randomDigit' : '');
                $fieldsStore .= ($column['type'] === 'datetime' ? 'dateTime' : '');
                $fieldsStore .= ($column['type'] === 'boolean' ? 'boolean()' : '');
                $fieldsStore .= ($column['type'] === 'text' ? 'word' : '');
                $fieldsStore .= ($column['name'] === 'name' ? 'name' : '');
                $fieldsStore .= ($column['name'] === 'email' ? 'email' : '');
                $fieldsStore .= ($column['name'] === 'password' ? 'password' : '');
                $fieldsStore .= ($column['name'] === 'title' ? 'title' : '');
                //end of the variable part
                $fieldsStore .= (substr($fieldsStore, -2) == '->' ? 'word' : '');
                $fieldsStore .= ',' . PHP_EOL . '        ';
            }

            if ($column['name'] === 'password') {
                $unset = 'unset($data["password_confirmation"]);';
                $fieldsStore .= '\'password_confirmation\' => \'test4pass\',' . PHP_EOL . '            ';
            }

            if ($column['input'] === 'select') {
                $fieldsStore .= '\'' . $column['name'] . '\' => \\App\\Models\\' . $column['belongsTo']['model'] . '::first()->id,' . PHP_EOL . '        ';
            }

            if ($column['name'] === 'remember_token') {
                $fieldsStore .= '\'remember_token\' => Str::random(10),' . PHP_EOL . '        ';
            }
        }

        $testTemplate = $this->makeTemplate(
            [
                '{{tableName}}',
                '{{PluralName}}',
                '{{pluralName}}',
                '{{ModelName}}',
                '{{singularName}}',
                '{{fieldsIndex}}',
                '{{fieldsStore}}',
                '{{unset_password_confirmation}}',
                '{{fieldForUpdate}}',
                '{{customPath}}'
            ],
            [
                $this->tableName,
                $name,
                strtolower($name),
                $singularName,
                strtolower($singularName),
                $fieldsIndex,
                $fieldsStore,
                $unset,
                $fieldForUpdate,
                $customPath
            ],
            'Test'
        );

        $this->writeToFile(base_path("/tests/Feature/Admin/{$singularName}ControllerTest.php"), $testTemplate);
    }

    /**
     * Create the model
     * @param string $name
     */
    protected function model(string $name): void
    {
        $hiddenFields = ['password', 'remember_token'];
        $name = Str::singular($name);
        $relationsTemplate = "";
        $scopesTemplate = "";
        $sortable = '[' . PHP_EOL;
        $fillable = '[' . PHP_EOL;
        $hidden = '[' . PHP_EOL;
        $eq = config('crud.filters.eq');
        $date = config('crud.filters.date');
        foreach ($this->columns as $column) {
            if ($column['input'] == "select") {
                $relationsTemplate .= $this->makeTemplate(
                    ['{{nameOfRelation}}', '{{modelNameOfRelation}}', '{{name}}'],
                    [$column['belongsTo']['name'], $column['belongsTo']['model'], $name],
                    'models/relations/belongsTo'
                );
            }
            if ($column['name'] == 'id' || !in_array(
                    $column['name'],
                    $this->systemColumns
                ) && $column['name'] != 'password') {
                if (in_array($column['type'], $eq)) {
                    $scopesTemplate .= $this->makeTemplate(['{{name}}', '{{camelName}}'],
                        [$column['name'], ucfirst(Str::camel($column['name']))],
                        'models/scopes/equals');
                } elseif (in_array($column['type'], $date)) {
                    $scopesTemplate .= $this->makeTemplate(['{{name}}', '{{camelName}}'],
                        [$column['name'], ucfirst(Str::camel($column['name']))],
                        'models/scopes/from');
                    $scopesTemplate .= $this->makeTemplate(['{{name}}', '{{camelName}}'],
                        [$column['name'], ucfirst(Str::camel($column['name']))],
                        'models/scopes/to');
                } else {
                    $scopesTemplate .= $this->makeTemplate(['{{name}}', '{{camelName}}'],
                        [$column['name'], ucfirst(Str::camel($column['name']))],
                        'models/scopes/like');
                }
                $sortable .= '                            \'' . $column['name'] . '\'' . ',' . PHP_EOL;
            }
            if (!in_array($column['name'], $this->systemColumns)) {
                $fillable .= '                            \'' . $column['name'] . '\',' . PHP_EOL;
            }
            if (in_array($column['name'], $hiddenFields)) {
                $hidden .= '                            \'' . $column['name'] . '\',' . PHP_EOL;
            }
        }
        $sortable .= '                            ]';
        $fillable .= '                            ]';
        $hidden .= '                            ]';
        if ($name != 'User') {
            $stub = 'Model';
        } else {
            $stub = 'UserModel';
        }
        $modelTemplate = $this->makeTemplate(
            ['{{modelName}}', '{{Relations}}', '{{fillable}}', '{{sortable}}', '{{hidden}}', '{{Scopes}}'],
            [$name, $relationsTemplate, $fillable, $sortable, $hidden, $scopesTemplate],
            $stub
        );
        $this->writeToFile(app_path("/Models/{$name}.php"), $modelTemplate);
    }

    /**
     * Create the repository
     * @param string $name
     */
    protected function repository(string $name): void
    {
        $name = Str::singular($name);
        if (!file_exists($path = app_path('/Interfaces'))) {
            mkdir($path, 0755, true);
        }

        if (!file_exists($path = app_path("Interfaces/Repositories" . $name))) {
            mkdir($path, 0755, true);
        }

        if (!file_exists($path = app_path('/Repositories'))) {
            mkdir($path, 0755, true);
        }

        $template = $this->makeTemplate(
            [
                '{{modelName}}',
            ],
            [
                $name,
            ],
            'RepositoryInterface'
        );
        $this->writeToFile(
            app_path("Interfaces/Repositories/{$name}RepositoryInterface.php"),
            $template
        );

        $template = $this->makeTemplate(
            [
                '{{modelName}}',
            ],
            [
                $name,
            ],
            'Repository'
        );
        $this->writeToFile(
            app_path("Repositories/{$name}Repository.php"),
            $template
        );
    }

    /**
     * Create the service
     * @param string $name
     */
    protected function service(string $name): void
    {
        $name = Str::singular($name);
        if (!file_exists($path = app_path('/Services'))) {
            mkdir($path, 0755, true);
        }

        $template = $this->makeTemplate(
            [
                '{{modelName}}',
            ],
            [
                $name,
            ],
            'Service'
        );
        $this->writeToFile(
            app_path("Services/{$name}Service.php"),
            $template
        );
    }

    /**
     * Create the request
     * @param string $name
     */
    protected function request(string $name): void
    {
        /*TODO Добавить проверку ролей "return Gate::allows('crud.product.create');"*/
        //TODO Рассмотреть вариант выноса формирования $namespacePath за метод для исключения дублирования
        $pathName = ($this->route) ? ucfirst($this->route) : '';
        $customPath = ($pathName) ? $pathName . '/' : '';
        $namespacePath = ($pathName) ? '\\' . $pathName : '';
        $name = Str::singular($name);
        if (!file_exists($path = app_path('/Http/Requests'))) {
            mkdir($path, 0755, true);
        }

        if (!file_exists($path = app_path("Http/Requests/{$customPath}" . $name))) {
            mkdir($path, 0755, true);
        }

        foreach ($this->requestsList as $request) {
            $rules = '[' . PHP_EOL;
            if ($request['rules']) {
                foreach ($this->columns as $column) {
                    if (!empty($column['rules'])
                        && !empty($column['rules'][$request['rules']])
                        && !($request['rules'] == 'toggleboolean'
                            && strpos($rules, 'column_name'))) {
                        $rules .= '            \'';
                        if ($request['rules'] == 'massdestroy') {
                            $rules .= 'selected.*';
                        } else {
                            if ($request['rules'] == 'toggleboolean') {
                                $rules .= 'value';
                            } else {
                                $rules .= $column['name'];
                            }
                        }
                        $rules .= '\' => \'' . $column['rules'][$request['rules']] . '\',' . PHP_EOL;
                        if ($request['rules'] == 'toggleboolean') {
                            $rules .= '            \'column_name\' => \'required|string\',' . PHP_EOL;
                        }
                    }
                }
            }
            $rules .= '        ]';
            $requestTemplate = $this->makeTemplate(
                [
                    '{{modelName}}',
                    '{{requestName}}',
                    '{{rules}}',
                    '{{namespacePath}}'
                ],
                [
                    $name,
                    $request['name'],
                    $rules,
                    $namespacePath
                ],
                'Request'
            );
            $this->writeToFile(
                app_path("Http/Requests/{$customPath}$name/{$request['name']}Request.php"),
                $requestTemplate
            );
        }
    }

    /**
     * Create views
     * @param $name
     */
    protected function views(string $name): void
    {
        $name = strtolower($name);
        $singularName = Str::singular($this->tableName);
        $pathName = ($this->route) ? ucfirst($this->route) : '';
        $customPath = ($pathName) ? strtolower($pathName) . '/' : '';
        $pathViews = ($pathName) ? $pathName . '.' . $this->tableName : $this->tableName;

        if (!file_exists($path = resource_path("/views/{$customPath}" . $this->tableName))) {
            mkdir($path, 0755, true);
        }

        foreach ($this->viewsList as $view) {
            if ($view == "create" || $view == "edit") {
                $fieldsTemplate = "";

                //TODO добавить типы для select и radio
                foreach ($this->columns as $column) {
                    if (!in_array($column['name'], $this->systemColumns)) {
                        $modelName = "";
                        if ($column['input'] == "select") {
                            $modelName = $column['belongsTo']['model'];
                        }
                        $fieldsTemplate .= $this->makeTemplate(
                            [
                                '{{ColumnName}}',
                                '{{Required}}',
                                '{{Model}}',
                            ],
                            [
                                $column['name'],
                                ($column['required']) ? "1" : "0",
                                $modelName
                            ],
                            "views/fields/{$column['input']}.blade"
                        );
                        if ($column['name'] == 'password') {
                            $fieldsTemplate .= $this->makeTemplate(
                                [
                                    '{{ColumnName}}',
                                    '{{Required}}',
                                    '{{Model}}'
                                ],
                                [
                                    'password_confirmation',
                                    "1",
                                    $modelName
                                ],
                                "views/fields/{$column['input']}.blade"
                            );
                        }
                    }
                }
                $formTemplate = $this->makeTemplate(
                    [
                        '{{Fields}}'
                    ],
                    [
                        $fieldsTemplate
                    ],
                    "views/form.blade"
                );
                $requestTemplate = $this->makeTemplate(
                    [
                        '{{Form}}',
                        '{{SingularName}}',
                        '{{SingularNameKebab}}',
                        '{{PluralName}}',
                    ],
                    [
                        $formTemplate,
                        $singularName,
                        str_replace('_', '-', $singularName),
                        $this->tableName
                    ],
                    "views/" . $view . ".blade"
                );
            } else {
                if ($view == "table") {
                    $ThTemplate = '';
                    $TdTemplate = '';
                    foreach ($this->columns as $column) {
                        if ($column['name'] == 'id' || !in_array(
                                $column['name'],
                                $this->systemColumns
                            ) && $column['name'] != 'password') {
                            $ThTemplate .= $this->makeTemplate(
                                [
                                    '{{modelNameSingularLowerCase}}',
                                    '{{Name}}'
                                ],
                                [
                                    $singularName,
                                    $column['name']
                                ],
                                "views/table/th.blade"
                            );
                            $stub = $column['type'] == 'boolean' ? 'views/table/tdToggle.blade' : 'views/table/td.blade';
                            $columnName = $column['name'];
                            if (isset($column['belongsTo']['name'])) {
                                $columnName = $column['belongsTo']['name'] . ".name";
                            }
                            $TdTemplate .= $this->makeTemplate(
                                [
                                    '{{ColumnName}}',
                                    '{{ColumnType}}',
                                ],
                                [
                                    $columnName,
                                    $column['type'],
                                ],
                                $stub
                            );
                        }
                    }
                    $requestTemplate = $this->makeTemplate(
                        [
                            '{{Th}}',
                            '{{Td}}',
                            '{{PluralName}}',
                            '{{prefix}}'
                        ],
                        [
                            $ThTemplate,
                            $TdTemplate,
                            $this->tableName,
                            !empty($this->route) ? $this->route . '/' : ''
                        ],
                        "views/table.blade"
                    );
                } else {
                    if ($view == "filters") {
                        $filters = $this->makeFiltersTemplate($name);
                        $requestTemplate = $this->makeTemplate(
                            [
                                '{{Filters}}',
                                '{{Model}}'
                            ],
                            [
                                $filters,
                                'App\\MOdels\\' . str_replace('_', '', Str::ucfirst($singularName))
                                //TODO может это из контроллера лучше присылать?
                            ],
                            "views/filter/filters.blade"
                        );
                    } else {
                        $requestTemplate = $this->makeTemplate(
                            [
                                '{{modelName}}',
                                '{{SingularName}}',
                                '{{SingularNameKebab}}',
                                '{{PluralName}}'
                            ],
                            [
                                strtolower($pathViews),
                                $singularName,
                                str_replace('_', '-', $singularName),
                                $this->tableName
                            ],
                            "views/" . $view . ".blade"
                        );
                    }
                }
            }
            $this->writeToFile(
                resource_path("/views/{$customPath}{$this->tableName}/{$view}.blade.php"),
                $requestTemplate
            );
        }
    }

    /**
     * Create filter template
     * @param string $name
     * @return string
     */
    protected function makeFiltersTemplate($name): string
    {
        $name = strtolower($name);
        $singularName = Str::singular($this->tableName);
        $filters = "";
        foreach ($this->columns as $column) {
            if (($column['name'] == 'id' || !in_array(
                        $column['name'],
                        $this->systemColumns
                    )) && $column['input'] != 'password') {
                $modelName = "";
                if ($column['input'] == "textarea") {
                    $column['input'] = "text";
                } else {
                    if ($column['input'] == "select") {
                        $modelName = $column['belongsTo']['model'];
                    }
                }
                $postfix = '';
                if (in_array($column['type'], config('crud.filters.date'))) {
                    $postfix = '_from';
                }
                $field = $this->makeTemplate(
                    [
                        '{{ColumnName}}',
                        '{{Required}}',
                        '{{Model}}',
                        '{{SingularName}}'
                    ],
                    [
                        $column['name'] . $postfix,
                        0,
                        $modelName,
                        $singularName
                    ],
                    "views/fields/{$column['input']}.blade"
                );
                $filters .= $this->makeTemplate(
                    [
                        '{{Field}}',
                    ],
                    [
                        $field,
                    ],
                    "views/filter/filter.blade"
                );
                if ($postfix) {
                    $postfix = '_to';
                    $field = $this->makeTemplate(
                        [
                            '{{ColumnName}}',
                            '{{Required}}',
                            '{{Model}}',
                            '{{SingularName}}'
                        ],
                        [
                            $column['name'] . $postfix,
                            0,
                            $modelName,
                            $singularName
                        ],
                        "views/fields/{$column['input']}.blade"
                    );
                    $filters .= $this->makeTemplate(
                        [
                            '{{Field}}',
                        ],
                        [
                            $field,
                        ],
                        "views/filter/filter.blade"
                    );
                }
            }
        }

        return $filters;
    }

    /**
     * Create the languages files
     * @param string $name
     */
    protected function languages(string $name): void
    {
        $singularName = Str::singular($this->tableName);
        $lowerSingularName = strtolower($singularName);
        $columnsWithTranslations = '';
        $languages = ['en', 'ru'];
        $n = 0;
        foreach ($this->columns as $key => $column) {
            $space = "";
            if ($n != 0) {
                $space = "            ";
            }
            $columnsWithTranslations .= $space . "'" . $column['name'] . "' => '" . str_replace(
                    '_',
                    ' ',
                    ucfirst($column['name'])
                ) . "'";
            if ($n != (count($this->columns) - 1)) {
                $columnsWithTranslations .= ",\n";
            }
            $n++;
        }

        $languagesColumnsTemplate = $this->makeTemplate(
            [
                '{{Name}}',
                '{{lowerSingularName}}',
                '{{Columns}}'
            ],
            [
                $name,
                $lowerSingularName,
                $columnsWithTranslations
            ],
            "lang/crud"
        );

        foreach ($languages as $language) {
            $languagesTemplate = null;
            $languagePath = resource_path("lang/{$language}");
            $languagePathToFile = $languagePath . "/crud.php";

            if (!file_exists($languagePath)) {
                mkdir($languagePath, 0755, true);
            }

            if (file_exists($languagePathToFile)) {
                $languagesTemplate = File::get($languagePathToFile);
            } else {
                $languagesTemplate = $this->makeTemplate([], [], "lang/{$language}/main");
            }

            if (!stristr($languagesTemplate, "'" . $lowerSingularName . "' => [")) {
                $resultLanguageFileContent = str_replace(
                    "/** Section for new languages **/",
                    $languagesColumnsTemplate,
                    $languagesTemplate
                );
                $this->writeToFile($languagePathToFile, $resultLanguageFileContent, true);
            }
        }
    }

    /**
     * Generate components
     * @param string $name
     */
    protected function components(string $name): void
    {
        $pathName = ($this->route) ? ucfirst($this->route) : '';
        $customPath = ($pathName) ? strtolower($pathName) . '/' : '';
        $componentFilePath = base_path('resources/js/vendor/nos/crud/index.js');
        $name = strtolower($name);
        $singularName = $this->tableName;
        $componentFile = File::get($componentFilePath);
        $pluralName = $this->route ? $this->route . "/" . $this->tableName : $this->tableName;
        if (!file_exists($path = resource_path("js/components/{$customPath}" . $singularName))) {
            mkdir($path, 0755, true);
        }
        $components = [
            'index',
            'edit',
            'create',
        ];
        //TODO добавить подключение VUEX
        foreach ($components as $component) {
            $template = $this->makeTemplate(
                [
                    '{{SingularName}}',
                    '{{PluralName}}'
                ],
                [
                    str_replace('_', '-', Str::singular($this->tableName)),
                    $pluralName
                ],
                "js/components/$component"
            );
            $this->writeToFile(resource_path("js/components/{$customPath}{$singularName}/{$component}.js"), $template);
            $pathToComponent = "require('../../../components/{$customPath}{$singularName}/{$component}.js')";
            if (!stristr($componentFile, $pathToComponent)) {
                File::prepend(
                    $componentFilePath,
                    $pathToComponent . ";\n"
                );
            }
        }
    }

    /**
     * Create the routes
     * @param string $name
     */
    protected function routes(string $name): void
    {
        $pathName = ($this->route) ? ucfirst($this->route) : '';
        $namespacePath = ($pathName) ? $pathName . '\\' : '';
        $routesFile = File::get(base_path('routes/web.php'));
        $singular = $namespacePath . Str::singular($name);
        $singularLowerCase = strtolower(Str::singular($name));
        $lowerCase = $this->tableName;
        $route = $this->route . "/" . $this->tableName;
        $controllerFullPath = '\\App\Http\\Controllers\\' . $singular . 'Controller::class';

        $routes = [
            "Route::pattern('{$singularLowerCase}', '[0-9]+');",
            "Route::resource('" . $route . "', $controllerFullPath);",
            "Route::post('" . $route . "/massdestroy', [$controllerFullPath, 'massDestroy'])->name('{$lowerCase}.massdestroy');",
            "Route::put('" . $route . "/{" . $singularLowerCase . "}/toggleboolean', [$controllerFullPath, 'toggleBoolean'])->name('{$lowerCase}.toggleboolean');",
        ];
        if ($this->export) {
            $routes[] = "Route::get('" . $route . "/export', [$controllerFullPath, 'export'])->name('{$lowerCase}.export');";
        }
        if ($this->import) {
            $routes[] = "Route::post('" . $route . "/import', [$controllerFullPath, 'import'])->name('{$lowerCase }.import');";
        }


        foreach ($routes as $route) {
            if (!stristr($routesFile, $route)) {
                File::append(base_path('routes/web.php'), $route . PHP_EOL);
            }
        }
    }

    /**
     * Create the menu
     * @param string $name
     */
    protected function menu(string $name): void
    {
        $singularName = Str::singular($this->tableName);
        $lowerSingularName = strtolower($singularName);
        $lowerTableName = strtolower($this->tableName);
        $menuBladeFilePath = base_path('/resources/views/vendor/nos/crud/layouts/menu.blade.php');
        if (!file_exists($menuBladeFilePath)) {
            $this->info("Menu template file not found!");

            return;
        }

        $menuFile = File::get($menuBladeFilePath);
        $route = $this->tableName . ".index";
        if (!stristr($menuFile, $route)) {
            $menuTemplate = $this->makeTemplate(
                [
                    '{{Route}}',
                    '{{lowerSingularName}}',
                    '{{lowerTableName}}'
                ],
                [
                    $route,
                    $lowerSingularName,
                    $lowerTableName
                ],
                "views/layout/menu.blade"
            );

            File::append($menuBladeFilePath, $menuTemplate);
        }
    }

    /**
     * Create the factory
     * @param string $name
     * @param array $columns
     */
    protected function factory(string $name, array $columns = []): void
    {
        $singularName = Str::singular($name);

        if (!file_exists($path = base_path('/database'))) {
            mkdir($path, 0775, true);
        }

        if (!file_exists($path = base_path('/database/factories'))) {
            mkdir($path, 0775, true);
        }

        $return = '';

        foreach ($this->columns as $column) {
            if (!in_array($column['name'], $this->systemColumns) && $column['input'] !== 'select') {
                //TODO remake to "ifelse" and leave one concatenation outside the logical module
                $return .= '\'' . $column['name'] . '\' => $this->faker->';
                //the beginning of the variable part
                $return .= ($column['unique'] ? 'unique()->' : '');
                $return .= ($column['type'] === 'int' ? 'randomDigit' : '');
                $return .= ($column['type'] === 'datetime' ? 'dateTime' : '');
                $return .= ($column['type'] === 'boolean' ? 'boolean()' : '');
                $return .= ($column['type'] === 'text' ? 'word' : '');
                $return .= ($column['name'] === 'name' ? 'name' : '');
                $return .= ($column['name'] === 'email' ? 'email' : '');
                $return .= ($column['name'] === 'password' ? 'password' : '');
                $return .= ($column['name'] === 'title' ? 'title' : '');
                //end of the variable part
                $return .= (substr($return, -2) == '->' ? 'word' : '');
                $return .= ',' . PHP_EOL . '        ';
            }

            if ($column['input'] === 'select') {
                $return .= '\'' . $column['name'] . '\' => \\App\\Models\\' . $column['belongsTo']['model'] . '::first()->id,' . PHP_EOL . '        ';
            }

            if ($column['name'] === 'remember_token') {
                $return .= '\'remember_token\' => Str::random(10),' . PHP_EOL . '        ';
            }
        }

        $factoryTemplate = $this->makeTemplate(
            [
                '{{modelName}}',
                '{{Name}}',
                '{{return}}'
            ],
            [
                $singularName,
                $name,
                $return
            ],
            'Factory'
        );

        $this->writeToFile(base_path("/database/factories/{$singularName}Factory.php"), $factoryTemplate);
    }

    /**
     * Create the seed
     * @param string $name
     */
    protected function seed(string $name): void
    {
        if (!file_exists($path = base_path('/database'))) {
            mkdir($path, 0775, true);
        }

        if (!file_exists($path = base_path('/database/seeders'))) {
            mkdir($path, 0775, true);
        }

        $callSeeder = '';

        foreach ($this->columns as $column) {
            if ($column['foreign']) {
                $select = explode(".", $column['foreign']);
                $callSeeder .= '$this->call(' . ucfirst($select[0]) . 'TableSeeder::class);' . PHP_EOL . '        ';
                $columnsForFactory = $this->getColumns($select[0]);
                $this->factory(ucfirst($select[0]), $columnsForFactory);
            }
        };

        $seedTemplate = $this->makeTemplate(
            [
                '{{modelName}}',
                '{{Name}}',
                '{{callSeeder}}'
            ],
            [
                Str::singular($name),
                $name,
                $callSeeder
            ],
            'Seed'
        );

        $this->writeToFile(base_path("/database/seeders/{$name}TableSeeder.php"), $seedTemplate);
    }

    /**
     * Import the request
     * @param string $name
     */
    protected function import(string $name): void
    {
        $name = ucfirst(Str::singular($name));
        $modelNamePlural = Str::plural($name);
        $path = app_path('/Imports');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $columns = '';
        $n = 0;
        foreach ($this->columns as $key => $column) {
            if (in_array($column['name'], ['created_at', 'updated_at', 'deleted_at', 'remember_token'])) {
                continue;
            }
            if ($n != 0) {
                $columns .= '            ';
            }
            $columns .= '\'' . $column['name'] . '\' => $row[' . $n . ']';
            if ($n != count($this->columns)) {
                $columns .= ',' . PHP_EOL;
            }
            $n++;
        }

        $requestTemplate = $this->makeTemplate([
            '{{modelName}}',
            '{{modelNamePlural}}',
            '{{columns}}'
        ], [
            $name,
            $modelNamePlural,
            $columns
        ], 'Import');
        $this->writeToFile($path . "/" . $modelNamePlural . "Import.php", $requestTemplate);
    }

    /**
     * Import the request
     * @param string $name
     */
    protected function export(string $name): void
    {
        $name = ucfirst(Str::singular($name));
        $modelNamePlural = Str::plural($name);
        $path = app_path('/Exports');
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $requestTemplate = $this->makeTemplate([
            '{{modelName}}',
            '{{modelNamePlural}}',
        ], [
            $name,
            $modelNamePlural
        ], 'Export');
        $this->writeToFile($path . "/" . $modelNamePlural . "Export.php", $requestTemplate);
    }
}

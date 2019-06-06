<?php

namespace PHPTool\LaravelPlusMake\Commands;

use Illuminate\Console\Command;
use PHPTool\LaravelPlusMake\Controllers\FrameworkController;

/**
 * Class Framework
 *
 * @package PHPTool\LaravelPlusMake\Commands
 */
class Framework extends Command
{
    /**
     * @var string
     */
    protected $signature = 'make:framework
                            {framework_name : framework name}
                            {--basis : only basis framework}
                            {--delete : delete framework}
                            {--D : delete framework}
                            {--NonMapModel : non auto mapping model}
                            ';

    /**
     * @var string
     */
    protected $description = 'Command description';

    /**
     * @var array
     */
    private $frameworks = [
        'Controller',
        'Repository',
        'Service',
        'Presenter',
        'Transformer',
        'Formatter',
    ];
    /**
     * @var array
     */
    private $base_frameworks = [
        'Repository',
        'Service',
    ];

    /**
     * Framework constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function handle()
    {
        $framework_name = $this->argument('framework_name');
        list($basis, $is_delete, $non_map_model) = $this->initOption();
        $frameworks = $this->frameworks;
        if (true === $basis) {
            $frameworks = $this->base_frameworks;
        }
        $bar = $this->output->createProgressBar(count($frameworks));
        foreach ($frameworks as $framework) {
            (new FrameworkController())->handle($framework, $framework_name, $is_delete);
            $bar->advance();
        }
        $bar->finish();
        $msg = 'create';
        if ($is_delete) {
            $msg = 'delete';
        }
        $this->info(PHP_EOL . " {$msg} framework \e[31m{$framework_name}\e[0m \e[32msuccess");
    }

    /**
     * @return array
     */
    private function initOption(): array
    {
        $basis = $this->option('basis');
        $is_delete = $this->option('delete');
        $is_delete or $is_delete = $this->option('D');
        $non_map_model = $this->option('NonMapModel');
        return array($basis, $is_delete, $non_map_model);
    }
}

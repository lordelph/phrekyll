<?php
/**
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *          http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @category    Phrekyll
 * @package     Phrekyll\Runner
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrekyll\Runner;

use Symfony\Component\Yaml\Yaml;
use Phrekyll\Runner\CommandLine\Parser;
use Phrekyll\Runner\CommandLine\Command;
use Phrekyll\Outputter\DefaultOutputter as Outputter;

/**
 * CLI version of framework invoker.
 *
 * @category    Phrekyll
 * @package     Phrekyll\Runner
 * @author      Victor Farazdagi
 */
class CommandLine implements \Phrekyll\Runner
{
    /**
     * System paths
     */
    private $paths = array();

    /**
     * @var \Console_CommandLine
     */
    private $parser;

    /**
     * @var \Console_CommandLine_Result
     */
    private $result;

    /**
     * Contents of phrekyll.yml is loaded into this attribute on startup
     */
    private $config;

    /**
     * @var \Phrekyll\Autoloader
     */
    private $loader;

    /**
     * @var \Phrekyll\Outputter
     */
    private $outputter;

    /**
     * Create runner
     *
     * @param \Phrekyll\Autoloader $loader Instance of auto-loader
     * @param array $paths Folder paths
     */
    public function __construct($loader)
    {
        $this->paths = $loader->getPaths();
        $this->loader = $loader;
        $this->outputter = new Outputter;

        // load main config
        $yaml=file_get_contents($this->paths['configs'] . 'phrekyll.yml');
        $this->config = Yaml::parse($yaml);
    }

    /**
     * Process the request
     *
     * @param array $params Runner options
     *
     * @return void
     */
    public function run($params = null)
    {
        $this->parser = new Parser($this->paths);

        try {
            $argc = ($params === null) ? null : count($params);
            $this->result = $this->parser->parse($argc, $params);
        } catch (\Exception $e) {
            $this->parser->displayError($e->getMessage());
        }

        $this->parse();
    }

    /**
     * Inject alternative outputter here, useful for tests
     */
    public function setOutputter(\Phrekyll\Outputter $outputter)
    {
        $this->outputter=$outputter;
    }

    /**
     * Parse input and invoke necessary processor callback
     *
     * @return void
     */
    private function parse()
    {
        $opts = $this->result->options;
        $commandName = $this->result->command_name;
        $command = null;
        $optionSet = $argumentSet = false;

        // special treatment for -h --help main command options
        if ($opts['help'] === true) {
            $commandName = 'help';
        }

        if ($commandName) {
            $configFile = $this->paths['configs'] . 'commands/' . $commandName . '.yml';
            $command = new Command($configFile);
        }

        // check if any option is set
        // basically check for --version -v --help -h options
        foreach ($opts as $name => $value) {
            if ($value === true) {
                $optionSet = true;
                break;
            }
        }

        // fire up subcommand
        if (isset($command['callback'])) {
            $this->invoke($command['callback'], $command);
        }

        if ($commandName === false && $optionSet === false && $argumentSet === false) {
            $this->outputter->stdout("Type 'phrekyll help' for usage.\n");
        }
    }

    /**
     * Invoke callback
     */
    private function invoke($callback, $data)
    {
        list($class, $method) = $callback;
        $class = 'Phrekyll\\Runner\\CommandLine\\Callback\\' . $class;


        $runner = new $class;
        $data['paths'] = $this->paths; // inject paths
        $runner
            ->setOutputter($this->outputter)
            ->setParseResult($this->result)
            ->setConfig($data);
        $callback = array($runner, $method);
        if (is_callable($callback)) {
            call_user_func($callback);
        }
    }
}

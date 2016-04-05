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
 * @package     Phrekyll\Runner\CommandLine\Callback
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace PhrekyllTest\Runner\CommandLine\Callback;

use Phrekyll\Runner\CommandLine\Callback\Single as Callback;
use Phrekyll\Runner\CommandLine as Runner;
use Phrekyll\Runner\CommandLine\Parser;
use Phrekyll\Outputter\TestOutputter as Outputter;

/**
 * @category    Phrekyll
 * @package     Phrekyll\Runner\CommandLine\Callback
 * @author      Victor Farazdagi
 */
class SingleTest extends \PHPUnit_Framework_TestCase
{
    private $phr;
    private $runner;
    private $outputter;
    private $parser;

    public function setUp()
    {
        $this->phr = realpath(__DIR__ . '/../../../../../bin/phrekyll.php');
        // paths
        $base = realpath(dirname(__FILE__) . '/../../../../../') . '/';
        $paths = array(
            'app'       => $base,
            'bin'       => $base . 'bin/',
            'lib'       => $base . 'library/',
            'configs'   => $base . 'configs/',
            'skeleton'  => $base . 'skeleton/',
        );

        // purge project directory
        $this->removeProjectDirectory();

        $this->outputter = new Outputter($this);
        $runner = new Callback();
        $data['paths'] = $paths; // inject paths
        $runner
            ->setOutputter($this->outputter)
            ->setConfig($data);
        $this->runner = $runner;

        // setup parser
        $this->parser = new Parser($paths);
    }

    public function tearDown()
    {
        // purge project directory
        $this->removeProjectDirectory();
    }

    public function testProjectCompile()
    {
        $out = $this->outputter;

        $path = dirname(__FILE__) . '/project/subpath';
        mkdir($path);

        // initialize project
        $this->assertFalse(is_dir($path . '/.phrekyll'));
        $this->assertFalse(is_dir($path . '/.phrekyll/entries'));
        $this->assertFalse(is_readable($path . '/.phrekyll/config.yml'));
        `{$this->phr} init {$path}/.phrekyll`;
        $this->assertTrue(is_dir($path . '/.phrekyll'));
        $this->assertTrue(is_dir($path . '/.phrekyll/entries'));
        $this->assertTrue(is_readable($path . '/.phrekyll/config.yml'));

        $result = $this->getParseResult("{$this->phr} single about.twig {$path} {$path}");

        $this->runner
            ->setOutputter($out)
            ->setParseResult($result)
            ->execute();

        $out->assertInLogs("[OK]      Source directory located: {$path}/.phrekyll");
        $out->assertInLogs("[OK]      Destination directory located: {$path}/");

        $this->assertFileExists($path . '/about/index.html');
        $this->assertFileNotExists($path . '/index.html');
    }

    public function testProjectCompileNotStandardFolder()
    {
        $out = $this->outputter;

        $path = dirname(__FILE__) . '/project/subpath';
        mkdir($path);

        // initialize project
        $this->assertFalse(is_dir($path . '/src'));
        $this->assertFalse(is_dir($path . '/src/entries'));
        $this->assertFalse(is_readable($path . '/src/config.yml'));
        `{$this->phr} init {$path}/src`;
        mkdir($path . '/htdocs');
        $this->assertTrue(is_dir($path . '/src'));
        $this->assertTrue(is_dir($path . '/src/entries'));
        $this->assertTrue(is_readable($path . '/src/config.yml'));

        $result = $this->getParseResult("{$this->phr} single about.twig {$path}/src {$path}/htdocs");

        $this->runner
            ->setOutputter($out)
            ->setParseResult($result)
            ->execute();

        $out->assertInLogs("[OK]      Source directory located: {$path}/src");
        $out->assertInLogs("[OK]      Destination directory located: {$path}/htdocs");

        $this->assertFileExists($path . '/htdocs/about/index.html');
        $this->assertFileNotExists($path . '/htdocs/index.html');
    }


    private function getParseResult($cmd)
    {
        $args = explode(' ', $cmd);
        return $this->parser->parse(count($args), $args);
    }

    private function removeProjectDirectory()
    {
        $path = dirname(__FILE__) . '/project/subpath';

        if (is_dir($path)) {
            `rm -rf {$path}`;
        }

        $path .= '/.phrekyll';
        if (is_dir($path)) {
            `rm -rf {$path}`;
        }
    }
}

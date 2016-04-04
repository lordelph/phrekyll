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
use Phrekyll\Runner\CommandLine\Callback\Clobber as Callback,
    Phrekyll\Runner\CommandLine as Runner,
    Phrekyll\Runner\CommandLine\Parser,
    Phrekyll\Outputter\TestOutputter as Outputter;

/**
 * @category    Phrekyll
 * @package     Phrekyll\Runner\CommandLine\Callback
 * @author      Victor Farazdagi
 */
class ClobberTest
    extends \PHPUnit_Framework_TestCase
{
    private $runner;
    private $outputter;
    private $parser;

    public function setUp()
    {
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

    public function testClobberYesWithExplicitPath()
    {
        $out = $this->outputter;

        $path = dirname(__FILE__) . '/project';
        mkdir($path . '/.phrekyll');
        touch($path . '/.phrekyll/config.yml');

        $this->assertTrue(is_dir($path . '/.phrekyll'));
        $this->assertTrue(is_readable($path . '/.phrekyll/config.yml'));

        $result = $this->getParseResult("phr clobber {$path}/.phrekyll");

        $this->runner
            ->setUnitTestData('yes')
            ->setParseResult($result)
            ->execute();

        $out->assertInLogs('Purging project data');
        $out->assertInLogs("Located project folder: {$path}/.phrekyll");
        $out->assertInLogs("[DELETED]  {$path}/.phrekyll");

        $this->assertFalse(file_exists($path . '/.phrekyll'));
        $this->assertFalse(is_readable($path . '/.phrekyll/config.yml'));
    }

    public function testClobberNoWithExplicitPath()
    {
        $out = $this->outputter;

        $path = dirname(__FILE__) . '/project';
        mkdir($path . '/.phrekyll');
        touch($path . '/.phrekyll/config.yml');


        $result = $this->getParseResult("phr clobber {$path}/.phrekyll");

        $this->assertTrue(is_dir($path . '/.phrekyll'));
        $this->assertTrue(is_readable($path . '/.phrekyll/config.yml'));

        $this->runner
            ->setUnitTestData('no')
            ->setParseResult($result)
            ->execute();

        $out->assertInLogs('Purging project data');
        $out->assertInLogs("Located project folder: {$path}/.phrekyll");
        $out->assertInLogs("[FAIL]     Aborted..");

        $this->assertTrue(is_dir($path . '/.phrekyll'));
        $this->assertTrue(is_readable($path . '/.phrekyll/config.yml'));
    }

    public function testClobberYesWithImplicitPath()
    {
        $out = $this->outputter;

        $path = dirname(__FILE__) . '/project';
        $this->assertTrue(chdir($path));
        mkdir($path . '/.phrekyll');
        touch($path . '/.phrekyll/config.yml');


        $result = $this->getParseResult("phr clobber");

        $this->assertTrue(is_dir($path . '/.phrekyll'));
        $this->assertTrue(is_readable($path . '/.phrekyll/config.yml'));

        $this->runner
            ->setUnitTestData('yes')
            ->setParseResult($result)
            ->execute();

        $out->assertInLogs('Purging project data');
        $out->assertInLogs("Located project folder: {$path}/.phrekyll");
        $out->assertInLogs("[DELETED]  {$path}/.phrekyll");

        $this->assertFalse(file_exists($path . '/.phrekyll'));
        $this->assertFalse(is_readable($path . '/.phrekyll/config.yml'));
    }

    public function testClobberYesWithNonAbsolutePath()
    {
        $out = $this->outputter;

        $path = dirname(__FILE__) . '/project';
        $this->assertTrue(chdir($path . '/../'));
        mkdir($path . '/.phrekyll');
        touch($path . '/.phrekyll/config.yml');


        $result = $this->getParseResult("phr clobber project/.phrekyll");

        $this->assertTrue(is_dir($path . '/.phrekyll'));
        $this->assertTrue(is_readable($path . '/.phrekyll/config.yml'));

        $this->runner
            ->setUnitTestData('yes')
            ->setParseResult($result)
            ->execute();

        $out->assertInLogs('Purging project data');
        $out->assertInLogs("Located project folder: {$path}/.phrekyll");
        $out->assertInLogs("[DELETED]  {$path}/.phrekyll");

        $this->assertFalse(file_exists($path . '/.phrekyll'));
        $this->assertFalse(is_readable($path . '/.phrekyll/config.yml'));
    }

    private function getParseResult($cmd)
    {
        $args = explode(' ', $cmd);
        return $this->parser->parse(count($args), $args);
    }


    private function removeProjectDirectory()
    {
        $path = dirname(__FILE__) . '/project';
        chmod($path, 0777);

        $path .= '/.phrekyll';
        if (is_dir($path)) {
            `rm -rf {$path}`;
        }
    }
}

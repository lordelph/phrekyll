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
 * @category    Phrozn
 * @package     Phrozn\Runner\CommandLine
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace PhroznTest\Runner;

use Phrozn\Runner\CommandLine as Runner;
use Phrozn\Autoloader as Loader;
use Phrozn\Outputter\PlainOutputter as Outputter;

/**
 * @category    Phrozn
 * @package     Phrozn\Runner\CommandLine\Callback
 * @author      Victor Farazdagi
 */
class CommandLineTest extends \PHPUnit_Framework_TestCase
{
    private $phr;
    private $outputter;
    private $runner;

    public function setUp()
    {
        $this->phr = realpath(__DIR__ . '/../../../bin/phrozn.php');
        $this->fout = tmpfile();

        $this->outputter = new Outputter($this->fout, null);

        require_once 'Phrozn/Autoloader.php';
        $loader = Loader::getInstance();
        $this->runner = new Runner($loader);

        $this->runner->setOutputter($this->outputter, $this->outputter);

    }

    public function tearDown()
    {
        fclose($this->fout);
    }

    public function testRunHelpUpdate()
    {
        $this->runner->run(array(
            $this->phr,
            'help',
            'update',
        ));

        $rendered = $this->getTempFileContents();
        $rendered = $this->trimFirstLine($rendered);

        $this->assertOutput('phr-help-update.out', $rendered);
    }

    public function testRunHUpdate()
    {
        $this->runner->run(array(
            $this->phr,
            '-h',
        ));

        $rendered = $this->getTempFileContents();
        $rendered = $this->trimFirstLine($rendered);

        $this->assertOutput('phr-help.out', $rendered);
    }

    /**
     * @group cur
     */
    public function testRunWithNoArgs()
    {
        $this->runner->run(array(
            $this->phr,
        ));

        $rendered = $this->getTempFileContents();
        $this->assertOutput('phr-no-params.out', $rendered);
    }

    private function assertOutput($expectedFile, $actual)
    {
        $path = dirname(__FILE__) . '/output/'.$expectedFile;
        $expected=$this->cleanOutput(file_get_contents($path));
        $actual=$this->cleanOutput($actual);
        $this->assertSame($expected, $actual);
    }

    private function getParseResult($cmd)
    {
        $args = explode(' ', $cmd);
        return $this->parser->parse(count($args), $args);
    }

    private function getTempFileContents()
    {
        rewind($this->fout);
        return trim(fread($this->fout, 8096));
    }

    private function trimFirstLine($str)
    {
        return trim(substr($str, strpos($str, "\n") + 1));
    }

    /**
     * This lets us cope with minor variations in white space
     */
    private function cleanOutput($str)
    {
        //eliminate leading white space
        $str=preg_replace('/^\s+/m', '', $str);

        //eliminate all empty lines
        $str=preg_replace('/\n{2,}/', "\n", $str);

        return trim($str);
    }
}

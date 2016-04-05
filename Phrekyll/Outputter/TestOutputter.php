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
 * @package     Phrekyll\Outputter
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrekyll\Outputter;

use Phrekyll\Outputter\Console\Color;

/**
 * Test outputter
 *
 * @category    Phrekyll
 * @package     Phrekyll\Outputter
 * @author      Victor Farazdagi
 */
class TestOutputter implements \Phrekyll\Outputter
{
    /**
     * Output lines
     * @var array
     */
    private $lines = array();

    /**
     * Reference to current test case
     * @var \PHPUnit_Framework_TestCase
     */
    private $testCase;

    public function __construct($testCase)
    {
        $this->testCase = $testCase;
    }

    /**
     * Add line to output
     *
     * @param string $msg Line to add
     * @param string $status Output status
     *
     * @return \Phrekyll\Outputter
     */
    public function stdout($msg, $status = self::STATUS_OK)
    {
        $msg = Color::strip(Color::convert($msg));
        $this->lines[] = trim($msg);
        return $this;
    }

    /**
     * Writes the message $msg to STDERR.
     *
     * @param string $msg The message to output
     * @param string $status Output status
     *
     * @return \Phrekyll\Outputter
     */
    public function stderr($msg, $status = self::STATUS_FAIL)
    {
        $msg = Color::strip(Color::convert($msg));
        $this->lines[] = trim($msg);
        return $this;
    }

    /**
     * Get all outputted lines
     *
     * @return array
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * Asserts that the log buffer contains specified message
     *
     * @param string $expected Message subsctring
     * @param string $errorMsg The error message to display.
     *
     * @return void
     */
    public function assertInLogs($expected, $errorMsg = "Expected to find '%s' in logs:\n\n%s")
    {
        foreach ($this->getLines() as $log) {
            if (false !== stripos($log, $expected)) {
                $this->testCase->assertEquals(1, 1); // increase number of positive assertions
                return;
            }
        }
        $this->testCase->fail(sprintf($errorMsg, $expected, var_export($this->getLines(), true)));
    }

    /**
     * Asserts that the log buffer does NOT contain specified message
     *
     * @param string $expected Message substring
     * @param string $errorMsg The error message to display.
     *
     * @return void
     */
    public function assertNotInLogs($expected, $errorMsg = "Unexpected string '%s' found in logs:\n\n%s")
    {
        foreach ($this->getLines() as $log) {
            if (false !== stripos($log, $expected)) {
                $this->testCase->fail(sprintf($errorMsg, $expected, var_export($this->getLines(), true)));
            }
        }

        $this->testCase->assertEquals(1, 1); // increase number of positive assertions
    }

    /**
     * Cleanup previous logs
     *
     * @return void
     */
    public function resetLogs()
    {
        $this->lines = array();
    }
}

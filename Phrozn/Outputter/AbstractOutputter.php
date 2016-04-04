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
 * @package     Phrozn\Outputter
 * @author      Paul Dixon
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrozn\Outputter;

use Phrozn\Outputter\Console\Color;

/**
 * Abstract outputter provides a useful base for other
 * outputters by providing overridable stdout and stderr
 * handles and basic ouput methods
 *
 * @category    Phrozn
 * @package     Phrozn\Outputter
 * @author      Paul Dixon
 */
abstract class AbstractOutputter implements \Phrozn\Outputter
{
    protected $stdout;
    protected $stderr;

    public function __construct($stdout = null, $stderr = null)
    {
        $this->stdout=defined('STDOUT') ? STDOUT : null;
        if (!is_null($stdout)) {
            $this->stdout=$stdout;
        }
        $this->stderr=defined('STDERR') ? STDERR : null;
        if (!is_null($stderr)) {
            $this->stderr=$stderr;
        }
    }
    /**
     * Writes the message $msg to STDOUT.
     *
     * @param string $msg The message to output
     * @param string $status Output status
     *
     * @return \Phrozn\Outputter
     */
    public function stdout($msg, $status = self::STATUS_OK)
    {
        if ($this->stdout) {
            fwrite($this->stdout, $msg);
        } else {
            echo $msg;
        }
        return $this;
    }

    /**
     * Writes the message $msg to STDERR.
     *
     * @param string $msg The message to output
     * @param string $status Output status
     *
     * @return \Phrozn\Outputter
     */
    public function stderr($msg, $status = self::STATUS_FAIL)
    {
        if ($this->stderr) {
            fwrite($this->stderr, $msg);
        } else {
            echo $msg;
        }
        return $this;
    }
}

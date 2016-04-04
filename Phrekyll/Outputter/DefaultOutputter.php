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
 * Default outputter
 *
 * @category    Phrekyll
 * @package     Phrekyll\Outputter
 * @author      Victor Farazdagi
 */
class DefaultOutputter extends AbstractOutputter
{
    /**
     * Writes the message $msg to STDOUT.
     *
     * @param string $msg The message to output
     * @param string $status Output status
     *
     * @return \Phrekyll\Outputter
     */
    public function stdout($msg, $status = self::STATUS_OK)
    {
        $msg = Color::convert($status . $msg . "\n");
        return parent::stdout($msg, $status);
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
        $msg = Color::convert($status . $msg . "\n");
        return parent::stderr($msg, $status);
    }
}

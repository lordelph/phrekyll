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
 * @package     Phrekyll\Runner\CommandLine
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrekyll\Runner\CommandLine;

use Phrekyll\Has;

/**
 * Subcommand interface
 *
 * @category    Phrekyll
 * @package     Phrekyll\Runner\CommandLine\Callback
 * @author      Victor Farazdagi
 */
interface Callback extends
    Has\Config,
    Has\Outputter
{
    /**
     * Executes the callback action
     *
     * @return string
     */
    public function execute();

    /**
     * Result object of CLI input parsing
     *
     * @param Console_CommandLine_Result $result Parser's result
     *
     * @return Phrekyll\Runner\CommandLine\Callback
     */
    public function setParseResult($result);

    /**
     * Get parsed result object
     *
     * @return Console_CommandLine_Result
     */
    public function getParseResult();
}

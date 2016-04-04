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
 * @package     Phrekyll\Has
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrekyll\Has;

/**
 * Entity has outputter propety
 *
 * @category    Phrekyll
 * @package     Phrekyll\Has
 * @author      Victor Farazdagi
 */
interface Outputter
{
    /**
     * Set outputter
     *
     * @param \Phrekyll\Outputter $outputter Outputter instance
     *
     * @return \Phrekyll\Has\Outputter
     */
    public function setOutputter($outputter);

    /**
     * Get outputter instance
     *
     * @return \Phrekyll\Outputter
     */
    public function getOutputter();
}

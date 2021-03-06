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
 * @package     Phrekyll\Registry
 * @subpackage  Has
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrekyll\Registry\Has;

/**
 * Has registry container.
 *
 * @category    Phrekyll
 * @package     Phrekyll\Registry
 * @subpackage  Has
 * @author      Victor Farazdagi
 */
interface Container
{
    /**
     * Set registry container.
     *
     * @param \Phrekyll\Registry\Container $container Registry container
     *
     * @return \Phrekyll\Has\Container
     */
    public function setContainer(\Phrekyll\Registry\Container $container = null);

    /**
     * Get registry container.
     *
     * @return \Phrekyll\Has\Container
     */
    public function getContainer();
}

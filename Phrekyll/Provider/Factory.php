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
 * @package     Phrekyll\Provider
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrekyll\Provider;
use Phrekyll\Has;

/**
 * Provider producing factory
 *
 * @category    Phrekyll
 * @package     Phrekyll\Provider
 * @author      Victor Farazdagi
 */
class Factory
{
    /**
     * Create provider instance
     *
     * @param string $type Provider typ to initialize
     * @param mixed $data Provider data
     *
     * return \Phrekyll\Provider
     */
    public function create($type, $data)
    {
        // try to see if we have user defined plugin
        $class = 'PhrekyllPlugin\\Provider\\' . ucfirst($type);
        if (!class_exists($class)) {
            $class = 'Phrekyll\\Provider\\' . ucfirst($type);
            if (!class_exists($class)) {
                throw new \RuntimeException("Provider of type '{$type}' not found..");
            }
        }
        $object = new $class;
        $object->setConfig($data);
        return $object;
    }

}

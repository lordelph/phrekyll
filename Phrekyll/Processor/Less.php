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
 * @package     Phrekyll\Processor
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrekyll\Processor;
use Phrekyll\Autoloader as Loader;

/**
 * LESS styles processor
 *
 * @category    Phrekyll
 * @package     Phrekyll\Processor
 * @author      Victor Farazdagi
 */
class Less
    extends Base
    implements \Phrekyll\Processor
{
    /**
     * Reference to LESS compiler
     * @var \lessc
     */
    protected $lessc;

    /**
     * If configuration options are passes then twig environment
     * is initialized right away
     *
     * @param array $options Processor options
     *
     * @return \Phrekyll\Processor\Less
     */
    public function __construct($options = array())
    {
        if (count($options)) {
            $this->setConfig($options)
                 ->getEnvironment();
        }
    }

    /**
     * Parse the incoming template
     *
     * @param string $tpl Source template content
     * @param array $vars List of variables passed to template engine
     *
     * @return string Processed template
     */
    public function render($tpl, $vars = array())
    {
        return $this->getEnvironment()
                    ->parse($tpl);
    }

    /**
     * Get LESS compiler (if undefined, set it with path for @import directives)
     *
     * @param boolean $reset
     */
    protected function getEnvironment($reset = false)
    {
        if ($reset === true || null === $this->lessc) {
            $this->lessc = new \lessc;
            $this->lessc->addImportDir($this->getConfigFor('phr_template_dir'));
        }

        return $this->lessc;
    }
}

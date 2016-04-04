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
 * @package     Phrekyll\Site\View
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrekyll\Site\View;

use Phrekyll\Site;
use Phrekyll\Site\View\OutputPath\Plain as OutputFile;
use Phrekyll\Processor\Plain as Processor;

/**
 * Plain View without any text transformations
 *
 * @category    Phrekyll
 * @package     Phrekyll\Site\View
 * @author      Victor Farazdagi
 */
class Plain extends Base implements Site\View
{
    /**
     * Initialize view
     *
     * @param string $inputFile Path to view source file
     * @param string $outputDir File destination path
     *
     * @return \Phrekyll\Site\View
     */
    public function __construct($inputFile = null, $outputDir = null)
    {
        parent::__construct($inputFile, $outputDir);

        $this->addProcessor(new Processor());
    }

    /**
     * Render view. Twig views are rendered within layout.
     *
     * @param array $vars List of variables passed to text processors
     *
     * @return string
     */
    public function render($vars = array())
    {
        return parent::render($vars);
    }

    /**
     * Get output file path
     *
     * @return string
     */
    public function getOutputFile()
    {
        if (!$this->outputFile) {
            $path = new OutputFile($this);
            $this->setOutputFile($path->get());
        }

        return $this->outputFile;
    }
}

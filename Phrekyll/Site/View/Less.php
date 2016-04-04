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
use Phrekyll\Site\View\OutputPath\Style as OutputFile;
use Phrekyll\Processor\Less as Processor;

/**
 * LESS View
 *
 * @category    Phrekyll
 * @package     Phrekyll\Site\View
 * @author      Victor Farazdagi
 */
class Less extends Base implements Site\View
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

        $options = array();
        if (null !== $inputFile) {
            $options = array(
                'phr_template_filename' => basename($inputFile),
                'phr_template_dir'      => dirname($inputFile),
            );
        }
        $this->addProcessor(new Processor($options));
    }

    /**
     * Set input file path. Overriden to update processor options.
     *
     * @param string $file Path to file
     *
     * @return \Phrekyll\Site\View
     */
    public function setInputFile($path)
    {
        parent::setInputFile($path);
        $processors = $this->getProcessors();
        if (count($processors)) {
            $options = array(
                'phr_template_filename' => basename($path),
                'phr_template_dir'      => dirname($path),
            );
            $processor = array_pop($processors);
            $processor->setConfig($options);
        }
        return $this;
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

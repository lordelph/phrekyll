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
 * @subpackage  Dao
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrekyll\Registry\Dao;

use Phrekyll\Registry\Dao;
use Phrekyll\Path\Project as ProjectPath;

/**
 * Base implementaion of Registry DAO.
 *
 * @category    Phrekyll
 * @package     Phrekyll\Registry
 * @subpackage  Dao
 * @author      Victor Farazdagi
 */
abstract class Base implements Dao
{
    /**
     * Registry container
     * @var \Phrekyll\Registry\Container
     */
    private $container;

    /**
     * @var string
     */
    private $projectPath;

    /**
     * Output path. ".registry" by default.
     * @var string
     */
    private $outputFile = '.registry';

    /**
     * Initialize DAO object
     *
     * @param \Phrekyll\Registry\Container $container Registry container
     *
     * @return void
     */
    public function __construct(\Phrekyll\Registry\Container $container = null)
    {
        $this->setContainer($container);
    }

    /**
     * Set registry container.
     *
     * @param \Phrekyll\Registry\Container $container Registry container
     *
     * @return \Phrekyll\Has\Container
     */
    public function setContainer(\Phrekyll\Registry\Container $container = null)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * Get registry container.
     *
     * @return \Phrekyll\Has\Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Set project path.
     *
     * @param string $path Project path.
     *
     * @return \Phrekyll\Has\ProjectPath
     */
    public function setProjectPath($path)
    {
        if (!($path instanceof \Phrekyll\Path\Project)) {
            $path = new ProjectPath($path);
        }
        $this->projectPath = $path->get(); // calculate path

        return $this;
    }

    /**
     * Get project path.
     *
     * @return string
     */
    public function getProjectPath()
    {
        return $this->projectPath;
    }

    /**
     * Set output file path
     *
     * @param string $path File path
     *
     * @return \Phrekyll\Has\OutputFile
     */
    public function setOutputFile($path)
    {
        $this->outputFile = $path;
        return $this;
    }

    /**
     * Get output file path
     *
     * @return string
     */
    public function getOutputFile()
    {
        return $this->outputFile;
    }
}

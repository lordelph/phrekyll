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
 * @package     Phrekyll\Site
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrekyll\Site;
use Phrekyll\Site\View,
    Phrekyll\Outputter\DefaultOutputter as Outputter,
    Symfony\Component\Yaml\Yaml;

/**
 * Base implementation of Phrekyll Site
 *
 * @category    Phrekyll
 * @package     Phrekyll\Site
 * @author      Victor Farazdagi
 */
abstract class Base
    implements \Phrekyll\Site
{
    /**
     * List of views to process
     * @var array
     */
    private $views = array();

    /**
     * Input directory path.
     * Generally is a path with Phrekyll project or Phrekyll project directory itself.
     * @var string
     */
    private $inputDir;

    /**
     * Site output directory path
     * @var string
     */
    private $outputDir;

    /**
     * Phrekyll outputter
     * @var \Phrekyll\Outputter
     */
    private $outputter;

    /**
     * Loaded content of site/config.yml
     * @var array
     */
    private $siteConfig;

    /**
     * Initialize site object
     *
     * @param string $inputDir Input directory path
     * @param string $outputDir Output directory path
     * @param array  $config Configuration options with which site generation should run
     *
     * @return void
     */
    public function __construct($inputDir = null, $outputDir = null, $config = null)
    {
        $this
            ->setInputDir($inputDir)
            ->setOutputDir($outputDir)
            ->setSiteConfig($config);
    }

    /**
     * Set input directory path
     *
     * @param string $path Directory path
     *
     * @return \Phrekyll\Site
     */
    public function setInputDir($path)
    {
        $this->inputDir = $path;
        return $this;
    }

    /**
     * Get input directory path
     *
     * @return string
     */
    public function getInputDir()
    {
        return $this->inputDir;
    }

    /**
     * Set output directory path
     *
     * @param string $path Directory path
     *
     * @return \Phrekyll\Site
     */
    public function setOutputDir($path)
    {
        $this->outputDir = $path;
        return $this;
    }

    /**
     * Get output directory path
     *
     * @return string
     */
    public function getOutputDir()
    {
        // override output directory using site config file
        $config = $this->getSiteConfig();
        if (isset($config['site']['output'])) {
            $this->setOutputDir($config['site']['output']);
        }
        return $this->outputDir;
    }

    /**
     * Return list of queued views ready to be processed
     *
     * @return array
     */
    public function getQueue()
    {
        return $this->views;
    }

    /**
     * Adds view to the list of queued views
     *
     * @return \Phrekyll\Site
     */
    protected function addView($view)
    {
        $this->views[] = $view;
        return $this;
    }

    /**
     * Set outputter
     *
     * @param \Phrekyll\Outputter $outputter Outputter instance
     *
     * @return \Phrekyll\Site
     */
    public function setOutputter($outputter)
    {
        $this->outputter = $outputter;
        return $this;
    }

    /**
     * Get outputter instance
     *
     * @return \Phrekyll\Outputter
     */
    public function getOutputter()
    {
        if (null === $this->outputter) {
            $this->outputter = new Outputter;
        }
        return $this->outputter;
    }

    /**
     * Set site configuration
     *
     * @param array $config Array of options
     *
     * @return \Phrekyll\Has\SiteConfig
     */
    public function setSiteConfig($config)
    {
        $this->siteConfig = $config;
        return $this;
    }

    /**
     * Get site configuration
     *
     * @return array
     */
    public function getSiteConfig()
    {
        if (null === $this->siteConfig) {
            $configFile = realpath($this->getProjectDir() . '/config.yml');
            $yaml=file_get_contents($configFile);
            $this->siteConfig = Yaml::parse($yaml);
        }
        return $this->siteConfig;
    }

    /**
     * Create list of views to be created
     *
     * @return \Phrekyll\Site
     */
    protected function buildQueue()
    {
        // guess the base path with Phrekyll project
        $projectDir = $this->getProjectDir();
        $outputDir = $this->getOutputDir();
        $config = $this->getSiteConfig();

        // configure skip files options
        $skipToken = '-!SKIP!-';

        $folders = array(
            'entries', 'styles', 'scripts'
        );
        foreach ($folders as $folder) {
            $dir = new \RecursiveDirectoryIterator($projectDir . '/' . $folder);
            $it = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::SELF_FIRST);
            foreach ($it as $item) {
                $baseName = $item->getBaseName();
                if (isset($config['skip'])) {
                    $baseName = preg_replace($config['skip'], array_fill(0, count($config['skip']), $skipToken), $baseName);
                    if (strpos($baseName, $skipToken) !== false) {
                        continue;
                    }
                }
                if ($item->isFile()) {
                    try {
                        $factory = new View\Factory($item->getRealPath());
                        $factory->setInputRootDir($projectDir);
                        $view = $factory->create();
                        $view
                            ->setSiteConfig($this->getSiteConfig())
                            ->setOutputDir($outputDir);
                        $this->views[] = $view;
                    } catch (\Exception $e) {
                        $this->getOutputter()
                             ->stderr(str_replace($projectDir, '', $item->getRealPath()) . ': ' . $e->getMessage());
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Guess directory with Phrekyll project using input directory
     *
     * @return string
     */
    protected function getProjectDir()
    {
        $dir = rtrim($this->getInputDir(), '/');
        if (is_dir($dir . '/.phrekyll')) {
            $dir .= '/.phrekyll/';
        }

        // see if we have entries folder present
        if (!is_dir($dir . '/entries')) {
            throw new \RuntimeException('Entries folder not found');
        }

        return $dir;
    }

}

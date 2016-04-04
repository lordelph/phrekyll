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

namespace Phrekyll\Runner\CommandLine\Callback;

use Phrekyll\Outputter\Console\Color;
use Symfony\Component\Yaml\Yaml;
use Phrekyll\Runner\CommandLine;
use Phrekyll\Site\DefaultSite as Site;

/**
 * phrekyll up command
 *
 * @category    Phrekyll
 * @package     Phrekyll\Runner\CommandLine
 * @author      Victor Farazdagi
 */
class Up extends Base implements CommandLine\Callback
{
    /**
     * Executes the callback action
     *
     * @return string
     */
    public function execute()
    {
        try {
            $this->updateProject();
        } catch (\Exception $e) {
            $this->out(self::STATUS_FAIL . $e->getMessage());
            $this->out($this->getFooter());
        }
    }

    private function autoloadPlugins($plugins)
    {
        $loader = \Phrekyll\Autoloader::getInstance()->getLoader();

        $plugins .= '/plugins/';
        if (is_dir($plugins)) {
            $loader->add('PhrekyllPlugin', $plugins);
        }

    }

    private function updateProject()
    {
        list($in, $out) = $this->getPaths();

        $this->autoloadPlugins($in);

        ob_start();
        $this->out($this->getHeader());
        $this->out("Starting static site compilation.\n");

        $proceed = true;
        if (!is_dir($in)) {
            $this->out(
                self::STATUS_FAIL . "Source directory '{$in}' not found."
            );
            $proceed = false;
        } else {
            $this->out(self::STATUS_OK . "Source directory located: {$in}");
        }
        if (!is_dir($out)) {
            $this->out(
                self::STATUS_FAIL . "Destination directory '{$out}' not found."
            );
            $proceed = false;
        } else {
            $this->out(self::STATUS_OK . "Destination directory located: {$out}");
        }

        if ($proceed === false) {
            $this->out($this->getFooter());
            return;
        }

        $site = new Site($in, $out);
        $site
            ->setOutputter($this->getOutputter())
            ->compile();

        $this->out($this->getFooter());

        ob_end_clean();
    }

    private function getPaths()
    {
        $in = $out = null;

        $in  = $this->getPathArgument('in');
        $out = $this->getPathArgument('out');

        if (file_exists($in . DIRECTORY_SEPARATOR . '/.phrekyll')) {
            return array(
                $in . '/.phrekyll/',
                $out . '/'
            );
        } else {
            return array(
                $in . '/',
                $out . '/'
            );
        }
    }
}

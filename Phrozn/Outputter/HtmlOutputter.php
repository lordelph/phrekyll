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
 * @category    Phrozn
 * @package     Phrozn\Outputter
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace Phrozn\Outputter;

use Phrozn\Outputter;

/**
 * HTML outputter
 * This is a very simple implementation,
 * it basically only does the following :
 * - output <br> tags before linebreaks
 * - removes %X color directives
 *
 * @category    Phrozn
 * @package     Phrozn\Outputter
 * @author      Victor Farazdagi
 * @author      Antoine Goutenoir
 */
class HtmlOutputter extends AbstractOutputter
{
    /**
     * Writes the message $msg to STDOUT.
     *
     * @param string $msg The message to output
     * @param string $status Ignored
     *
     * @return Outputter
     */
    public function stdout($msg, $status = self::STATUS_OK)
    {
        $msg = $this->removeColors($msg);
        $msg = $this->replaceEOLs($msg);
        return parent::stdout($msg, $status);
    }

    /**
     * Writes the message $msg to STDERR.
     *
     * @param string $msg The message to output
     * @param string $status Output status
     *
     * @return Outputter
     */
    public function stderr($msg, $status = self::STATUS_FAIL)
    {
        $msg = "<strong>".$msg."</strong>";
        $msg = $this->removeColors($msg);
        $msg = $this->replaceEOLs($msg);
        return parent::stderr($msg, $status);
    }

    private function removeColors($msg)
    {
        return preg_replace("!%[a-z0-9]!i", "", $msg);
    }

    protected function replaceEOLs($msg)
    {
        return nl2br($msg, false) . "<br>\n";
    }
}

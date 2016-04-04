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

namespace PhroznTest\Outputter;
use Phrozn\Outputter\DefaultOutputter as Outputter;

/**
 * @category    Phrozn
 * @package     Phrozn\Outputter
 * @author      Victor Farazdagi
 */
class DefaultOutputterTest
    extends \PHPUnit_Framework_TestCase
{
    public function testStdOut()
    {
        $fp = tmpfile();

        $outputter = new Outputter($fp, null);
        $outputter->stdout('sending output', '');

        rewind($fp);
        $contents = fread($fp, 8096);

        fclose($fp);

        $this->assertSame('sending output', trim($contents));
    }

    public function testStdErr()
    {
        $fp = tmpfile();

        $outputter = new Outputter(null, $fp);
        $outputter->stderr('sending output', '');

        rewind($fp);
        $contents = fread($fp, 8096);

        fclose($fp);

        $this->assertSame('sending output', trim($contents));
    }
}

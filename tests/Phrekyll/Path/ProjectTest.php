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
 * @package     Phrekyll
 * @author      Victor Farazdagi
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */

namespace PhrekyllTest;
use Phrekyll\Path,
    Phrekyll\Path\Project as ProjectPath,
    \PHPUnit_Framework_TestCase as TestCase;

/**
 * @category    Phrekyll
 * @package     Phrekyll
 * @author      Victor Farazdagi
 */
class ProjectTest
    extends TestCase
{
    public function testPathCalculation()
    {
        $basePath = dirname(__FILE__) . '/project/';
        $path = new ProjectPath($basePath);
        $this->assertSame($basePath . '.phrekyll', $path->get());

        $this->assertSame(
            $basePath . '.phrekyll',
            $path->set($basePath . 'sub')->get());
        $this->assertSame(
            $basePath . '.phrekyll',
            $path->set($basePath . 'sub/')->get());
        $this->assertSame(
            $basePath . '.phrekyll',
            $path->set($basePath . 'sub/folder')->get());
        $this->assertSame(
            $basePath . '.phrekyll',
            $path->set($basePath . 'sub/folder/')->get());
        $this->assertSame(
            $basePath . 'sub/folder/subsub/.phrekyll',
            $path->set($basePath . 'sub/folder/subsub')->get());
        $this->assertSame(
            $basePath . 'sub/folder/subsub/.phrekyll',
            $path->set($basePath . 'sub/folder/subsub/')->get());
        $this->assertSame(
            $basePath . 'sub/folder/subsub/.phrekyll',
            $path->set($basePath . 'sub/folder/subsub/.phrekyll')->get());
        $this->assertSame(
            $basePath . 'sub/folder/subsub/.phrekyll',
            $path->set($basePath . 'sub/folder/subsub/.phrekyll/')->get());

        $this->assertSame('', $path->set("/var")->get());
    }

    public function testPathToString()
    {
        $basePath = dirname(__FILE__) . '/project/';
        $path = new ProjectPath($basePath);
        $this->assertSame($basePath . '.phrekyll', '' . $path);

        $this->assertSame($basePath . '.phrekyll', $path->set($basePath . 'sub') . '');
        $this->assertSame($basePath . '.phrekyll', $path->set($basePath . 'sub/') . '');
        $this->assertSame($basePath . '.phrekyll', $path->set($basePath . 'sub/folder') . '');
        $this->assertSame($basePath . '.phrekyll', $path->set($basePath . 'sub/folder/') . '');
        $this->assertSame($basePath . 'sub/folder/subsub/.phrekyll', $path->set($basePath . 'sub/folder/subsub') . '');
        $this->assertSame($basePath . 'sub/folder/subsub/.phrekyll', $path->set($basePath . 'sub/folder/subsub/') . '');
        $this->assertSame($basePath . 'sub/folder/subsub/.phrekyll', $path->set($basePath . 'sub/folder/subsub/.phrekyll') . '');
        $this->assertSame($basePath . 'sub/folder/subsub/.phrekyll', $path->set($basePath . 'sub/folder/subsub/.phrekyll/') . '');

        $this->assertSame('', (string)$path->set("/var"));
    }

    public function testPathNotSetException()
    {
        $this->setExpectedException('RuntimeException', 'Path not set');
        $path = new ProjectPath();
        $path->get();
    }
}


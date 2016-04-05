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

namespace PhrekyllTest\Site\View;

use Phrekyll\Site\View\Factory as Factory;
use Phrekyll\Site\View;

/**
 * @category    Phrekyll
 * @package     Phrekyll\Site\View
 * @author      Victor Farazdagi
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function testImplicitHtmlFileCreation()
    {
        $path = dirname(__FILE__) . '/../project/.phrekyll/entries/';
        $input = $path . '2011-02-24-factory-test.twig';
        $output = realpath($path . '/../site');
        $factory = new Factory();
        $view = $factory
                    ->setInputFile($input)
                    ->create();

        $this->assertInstanceOf('\Phrekyll\Site\View\Twig', $view);
    }

    public function testSourceFileCanNotBeRead()
    {
        $path = dirname(__FILE__) . '/../project/.phrekyll/entries/';
        $input = $path . 'not-found.twig';

        $this->setExpectedException(
            'RuntimeException',
            "View source file cannot be read: {$input}"
        );

        $factory = new Factory();
        $view = $factory
                    ->setInputFile($input)
                    ->create();
    }

    public function testNonExistantProcessor()
    {

        $path = dirname(__FILE__) . '/../project/.phrekyll/entries/';
        $input = $path . '2011-02-24-wrong-file-type.wrong';

        $factory = new Factory();
        $view = $factory
                    ->setInputFile($input)
                    ->create();
        $this->assertInstanceOf('\Phrekyll\Site\View\Plain', $view);
    }
}

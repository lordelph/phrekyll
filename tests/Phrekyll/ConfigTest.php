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
use Phrekyll\Config;

/**
 * @category    Phrekyll
 * @package     Phrekyll
 * @author      Victor Farazdagi
 */
class ConfigTest
    extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {}

    public function testInitialization()
    {
        $config = new Config(dirname(__FILE__) . '/../../configs/');
        $this->assertTrue(isset($config['phrekyll']));
        $this->assertTrue(isset($config['phrekyll']['author']));
        $this->assertSame('Paul Dixon', $config['phrekyll']['author']);

        $config['phrekyll'] = 'updated';
        $this->assertSame('updated', $config['phrekyll']);
        unset($config['phrekyll']);
        $this->assertFalse(isset($config['phrekyll']));


        $this->assertTrue(isset($config['paths']));
        $this->assertTrue(isset($config['paths']['skeleton']));
        $this->assertTrue(isset($config['paths']['library']));
        $this->assertTrue(isset($config['paths']['configs']));
    }

    public function testLoadFile()
    {
        $config = new Config(dirname(__FILE__) . '/../../configs/phrekyll.yml');
        $this->assertInstanceOf('\Phrekyll\Config', $config);
        $this->assertTrue(isset($config['phrekyll']['command']['name']));
    }


}

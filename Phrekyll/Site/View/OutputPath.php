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
use Phrekyll\Has;

/**
 * Output path builder for different types of views
 *
 * @category    Phrekyll
 * @package     Phrekyll\Site\View
 * @author      Victor Farazdagi
 */
interface OutputPath
    extends
        Has\View
{
    /**
     * Initialize path builder
     *
     * @param \Phrekyll\Site\View View for which output path needs to be determined
     *
     * @return void
     */
    public function __construct(\Phrekyll\Site\View $view);

    /**
     * Get calculated path
     *
     * @return string
     */
    public function get();
}

<?php

/**
 * PHP wrapper for the Scalarium API
 *
 * PHP Version 5
 *
 * @category EasyBib
 * @package  EasyBib_Services_Scalarium
 * @author   Ulf Härnhammar <ulfharn@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @version  SVN: $Id$
 * @link     http://www.easybib.com
 */

namespace EasyBib\Services\Scalarium;

use \EasyBib\Services\Scalarium;
use \EasyBib\Services\Scalarium\Applications;

/**
 * Deployments
 *
 * This class lets you retrieve information about deployments.
 *
 * @category EasyBib
 * @package  EasyBib_Services_Scalarium
 * @author   Ulf Härnhammar <ulfharn@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @version  Release: @package_version@
 * @link     http://www.easybib.com
 */
class Deployments extends Scalarium
{
    /**
     * Retrieves all application deployments from their API. Note that this
     * is an expensive operation that will call the API several times.
     *
     * @return array (parsed JSON)
     */
    public function getDeployments()
    {
        $deployments = array();
        $applications = new Applications(
            $this->endpoint, $this->accept, $this->token
        );
        $applicationsData = $applications->getApplications();
        if (is_array($applicationsData)) {
            foreach ($applicationsData as $oneApplication) {
                $deployments[ $oneApplication['id'] ] = $applications->
                    getDeploymentsByApplication($oneApplication['id']);
            }
        }
        return $deployments;
    }
}


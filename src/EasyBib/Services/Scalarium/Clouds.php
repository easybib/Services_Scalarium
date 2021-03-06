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

/**
 * Clouds
 *
 * This class lets you retrieve information about clouds.
 *
 * @category EasyBib
 * @package  EasyBib_Services_Scalarium
 * @author   Ulf Härnhammar <ulfharn@gmail.com>
 * @license  http://opensource.org/licenses/bsd-license.php New BSD License
 * @version  Release: @package_version@
 * @link     http://www.easybib.com
 */
class Clouds extends Scalarium
{
    /**
     * Retrieves all clouds from their API.
     *
     * @return array parsed JSON
     */
    public function getClouds()
    {
        return $this->retrieveAPIParseJSON('clouds');
    }


    /**
     * Retrieves all applications from their API.
     *
     * @return array parsed JSON
     */
    public function getApplications()
    {
        $applications = new Applications($this->token);
        return $applications->getApplications();
    }


    /**
     * Retrieves all applications in one cloud from their API.
     *
     * @param string $cloudID ID for the cloud
     *
     * @return array parsed JSON
     *
     * @throws \InvalidArgumentException when $cloudID is empty
     */
    public function getApplicationsInCloud($cloudID)
    {
        if (empty($cloudID)) {
            throw new \InvalidArgumentException("cloudID can't be empty");
        }
        $applicationsData = $this->getApplications();
        $applicationsInCloud = array();
        if (is_array($applicationsData)) {
            foreach ($applicationsData as $oneApplication) {
                if ($oneApplication['cluster_id'] == $cloudID) {
                    $applicationsInCloud[] = $oneApplication;
                }
            }
        }
        return $applicationsInCloud;
    }


    /**
     * Deploys to a cloud using a certain command and some given data.
     *
     * @param string $cloudID ID for the cloud
     * @param string $command the command to use
     * @param array  $data    other data to use
     *
     * @return string JSON data from Scalarium
     *
     * @throws \InvalidArgumentException when $cloudID or $command is
     *                                   empty
     * @throws \RuntimeException when the deployment doesn't work
     */
    public function deployToCloud($cloudID, $command, array $data)
    {
        if (empty($cloudID)) {
            throw new \InvalidArgumentException("cloudID can't be empty");
        }

        if (empty($command)) {
            throw new \InvalidArgumentException("command can't be empty");
        }

        $data['command'] = $command;
        $dataJSON = json_encode($data);

        try {
            return $this->retrieveAPI(
                "clouds/$cloudID/deploy",
                \HTTP_Request2::METHOD_POST,
                $dataJSON
            );
        } catch (\RuntimeException $e) {
            throw new \RuntimeException(
                'error occurred in retrieveAPI()', 0, $e
            );
        }
    }
}


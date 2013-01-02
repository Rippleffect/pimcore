<?php
    /**
     * Pimcore
     *
     * LICENSE
     *
     * This source file is subject to the new BSD license that is bundled
     * with this package in the file LICENSE.txt.
     * It is also available through the world-wide-web at this URL:
     * http://www.pimcore.org/license
     *
     * @category   Pimcore
     * @package    Document
     * @copyright  Copyright (c) 2009-2010 elements.at New Media Solutions GmbH (http://www.elements.at)
     * @license    http://www.pimcore.org/license     New BSD License
     */

class Tool_ContentAnalysis_Service extends Pimcore_Model_Abstract {

    public function getAggregatedOverviewData($data = null) {
        if(!$data) {
            $data = $this->getOverviewData();
        }

        $aggregated = array(
            "error" => 0,
            "warning" => 0,
            "notice" => 0
        );

        return $aggregated;
    }
}

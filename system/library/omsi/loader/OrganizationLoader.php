<?php
require_once dirname(__FILE__) . '/BaseLoader.php';

class OrganizationLoader extends BaseLoader {

    public function getOrganizationMetadata() {
        $url = URL_BASE . URL_GET_ORGANIZATION;
        $resultArray = parent::load($url);
        return $resultArray['rows'][0]['meta'];
    }
}

?>
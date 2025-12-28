<?php

class ContentService
{
        private $apiUrl;

        public function __construct()
        {
                $this->apiUrl = "http://content-service:8000"; 
        }

        public function getCourseDetails(int $externalId)
        {
                $url = $this->apiUrl . "/courses/" . $externalId;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 2);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200 && $response) {
                        return json_decode($response, true);
                }

                return null;
        }
}

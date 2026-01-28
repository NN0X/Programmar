<?php

class ContentService
{
        private $apiUrl;
        private $apiKey;

        public function __construct()
        {
                $this->apiUrl = getenv('CONTENT_SERVICE_URL') ?: 'http://content-service:8000';
                $this->apiKey = getenv('CONTENT_SERVICE_KEY') ?: 'default_secret_key';
        }

        private function makeRequest(string $endpoint)
        {
                $url = $this->apiUrl . $endpoint;

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'X-API-KEY: ' . $this->apiKey
                ]);

                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200 && $response) {
                        return json_decode($response, true);
                }

                return null;
        }

        public function getCourseDetails(int $externalId)
        {
                return $this->makeRequest("/courses/" . $externalId);
        }

        public function getLesson(int $courseId, int $lessonNum)
        {
                return $this->makeRequest("/courses/" . $courseId . "/lessons/" . $lessonNum);
        }
}

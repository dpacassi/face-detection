<?php
/**
 * @file
 * Custom client implementation of the Microsoft Azure Face API.
 */

class MicrosoftFaceAPI {

  protected $subscriptionKey;
  protected $apiEndpoint;

  public function __construct($subscriptionKey, $apiEndpoint='https://westeurope.api.cognitive.microsoft.com/face/v1.0/') {
    $this->subscriptionKey = $subscriptionKey;
    $this->apiEndpoint = $apiEndpoint;
  }

  public function getApiUrl() {
    return $this->apiEndpoint . 'detect';
  }

  public function detectFaces(\FaceDetection\FaceDetectionImage $image) {
    $headers = [
      'Content-Type: application/octet-stream',
      'Ocp-Apim-Subscription-Key: ' . $this->subscriptionKey,
    ];
    $params = [
      'returnFaceId' => 'false',
      'returnFaceLandmarks' => 'false',
    ];
    $query = http_build_query($params);
    $url = $this->getApiUrl() . '?' . $query;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents($image->getImage()));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, TRUE);
  }
}

<?php
/**
 * @file
 * Custom client implementation of the IBM Watson Visual Recognition API.
 */

class VisualRecognitionV3 {

  protected $apiKey;
  protected $apiEndpoint;

  public function __construct($apiKey, $apiEndpoint='https://gateway.watsonplatform.net/visual-recognition/api/v3/detect_faces') {
    $this->apiKey = $apiKey;
    $this->apiEndpoint = $apiEndpoint;
  }

  public function getApiUrl() {
    return $this->apiEndpoint . '?version=2018-03-19';
  }

  public function detectFaces(\FaceDetection\FaceDetectionImage $image) {
    $cfile = new CURLFile($image->getImage(), $image->getMimeType(), $image->getFilename());
    $data = ['images_file' => $cfile];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->getApiUrl());
    curl_setopt($ch, CURLOPT_USERPWD, 'apikey:' . $this->apiKey);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result, TRUE);
  }
}

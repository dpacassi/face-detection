<?php
/**
 * @file
 * Use IBM Watson Visual Recognition to detect faces in our dataset.
 */

// Include our Composer packages.
require __DIR__ . '/vendor/autoload.php';

// Include custom classes.
require_once __DIR__ . '/../../FaceDetectionClient.php';
require_once __DIR__ . '/../../FaceDetectionImage.php';
require_once __DIR__ . '/VisualRecognitionV3.php';

// Init our FaceDetectionClient class.
$app = new FaceDetection\FaceDetectionClient(basename(__DIR__), 'IBM', [31, 112, 193]);

// Initialize the Amazon Rekognition client.
$client = new VisualRecognitionV3(getenv('IBM_WATSON_API_KEY'));

// Load our dataset.
$images = $app->loadImages();

// Detect faces in our dataset.
foreach ($images as &$image) {
  $app->startTimer();
  $faces = $client->detectFaces($image);
  $image->setProcessingTime($app->stopTimer());

  if (!empty($faces) && !empty($faces['images'][0]['faces'])) {
    foreach ($faces['images'][0]['faces'] as $face) {
      $x1 = $face['face_location']['left'];
      $y1 = $face['face_location']['top'];
      $x2 = $face['face_location']['left'] + $face['face_location']['width'];
      $y2 = $face['face_location']['top'] + $face['face_location']['height'];

      $image->drawBoundingBox($x1, $y1, $x2, $y2);
      $image->increaseDetectedFaceCount();
    }
  }

  // Save our image.
  $image->save();
}

// Add analytical data to our CSV file.
$app->exportCSV();

print 'Finished parsing dataset, found [' . $app->getTotalDetectedFaceCount() . '] faces.' . "\n";

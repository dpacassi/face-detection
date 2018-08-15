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
require_once __DIR__ . '/MicrosoftFaceAPI.php';

// Init our FaceDetectionClient class.
$app = new FaceDetection\FaceDetectionClient(basename(__DIR__), 'Microsoft', [127, 186, 0]);

// Initialize the Amazon Rekognition client.
$client = new MicrosoftFaceAPI(getenv('MICROSOFT_AZURE_SUBSCRIPTION_KEY'));

// Load our dataset.
$images = $app->loadImages();

// Detect faces in our dataset.
foreach ($images as &$image) {
  $app->startTimer();
  $faces = $client->detectFaces($image);
  $image->setProcessingTime($app->stopTimer());

  if (!empty($faces)) {
    foreach ($faces as $face) {
      if (!empty($face['faceRectangle'])) {
        $x1 = $face['faceRectangle']['left'];
        $y1 = $face['faceRectangle']['top'];
        $x2 = $face['faceRectangle']['left'] + $face['faceRectangle']['width'];
        $y2 = $face['faceRectangle']['top'] + $face['faceRectangle']['height'];

        $image->drawBoundingBox($x1, $y1, $x2, $y2);
        $image->increaseDetectedFaceCount();
      }
    }
  }

  // Save our image.
  $image->save();
}

// Add analytical data to our CSV file.
$app->exportCSV();

print 'Finished parsing dataset, found [' . $app->getTotalDetectedFaceCount() . '] faces.' . "\n";

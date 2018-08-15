<?php
/**
 * @file
 * Use Google Cloud Vision API to detect faces in our dataset.
 */

// Include our Composer packages.
require __DIR__ . '/vendor/autoload.php';

// Include custom classes.
require_once __DIR__ . '/../../FaceDetectionClient.php';
require_once __DIR__ . '/../../FaceDetectionImage.php';

// Init our FaceDetectionClient class.
$app = new FaceDetection\FaceDetectionClient(basename(__DIR__), 'Google', [234, 67, 53]);

// Initialize the Google Cloud Vision API client.
$client = new Google\Cloud\Vision\V1\ImageAnnotatorClient();

// Load our dataset.
$images = $app->loadImages();

// Detect faces in our dataset.
foreach ($images as &$image) {
  $app->startTimer();
  $response = $client->faceDetection(file_get_contents($image->getImage()));
  $image->setProcessingTime($app->stopTimer());
  $faces = $response->getFaceAnnotations();

  if (!empty($faces)) {
    foreach ($faces as $face) {
      $vertices = $face->getBoundingPoly()->getVertices();

      if ($vertices) {
        $x1 = $vertices[0]->getX();
        $y1 = $vertices[0]->getY();
        $x2 = $vertices[2]->getX();
        $y2 = $vertices[2]->getY();

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

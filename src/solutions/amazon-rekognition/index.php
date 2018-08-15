<?php
/**
 * @file
 * Use Amazon Rekognition to detect faces in our dataset.
 */

// Include our Composer packages.
require __DIR__ . '/vendor/autoload.php';

// Include custom classes.
require_once __DIR__ . '/../../FaceDetectionClient.php';
require_once __DIR__ . '/../../FaceDetectionImage.php';

// Init our FaceDetectionClient class.
$app = new FaceDetection\FaceDetectionClient(basename(__DIR__), 'Amazon', [255, 153, 0]);

// Initialize the Amazon Rekognition client.
$client = new Aws\Rekognition\RekognitionClient([
  'version' => 'latest',
  'region'  => getenv('AWS_DEFAULT_REGION'),
]);

// Load our dataset.
$images = $app->loadImages();

// Detect faces in our dataset.
foreach ($images as &$image) {
  $app->startTimer();
  $labels = $client->detectFaces([
    'Image' => [
      'Bytes' => file_get_contents($image->getImage()),
    ]
  ]);
  $image->setProcessingTime($app->stopTimer());

  if (!empty($labels->get('FaceDetails'))) {
    $face_details = $labels->get('FaceDetails');

    foreach ($face_details as $face_detail) {
      // Draw bounding boxes.
      $image->drawBoundingBoxFromPercentage($face_detail['BoundingBox']);
      $image->increaseDetectedFaceCount();
    }
  }

  // Save our image.
  $image->save();
}

// Add analytical data to our CSV file.
$app->exportCSV();

print 'Finished parsing dataset, found [' . $app->getTotalDetectedFaceCount() . '] faces.' . "\n";

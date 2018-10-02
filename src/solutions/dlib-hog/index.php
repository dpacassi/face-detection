<?php
/**
 * @file
 * Use dlib's get_frontal_face_detector() to detect faces in our dataset.
 */

// Include our Composer packages.
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
  require __DIR__ . '/vendor/autoload.php';
}

// Include custom classes.
require_once __DIR__ . '/../../FaceDetectionClient.php';
require_once __DIR__ . '/../../FaceDetectionImage.php';
require_once __DIR__ . '/../../FaceDetectionShell.php';

// Init our FaceDetectionClient class.
$app = new FaceDetection\FaceDetectionClient(basename(__DIR__), 'Dlib - HOG', [0, 255, 0]);

// Initialize our client.
$cli = getenv('PYTHON_CLI');
$cli = $cli !== FALSE ? $cli : 'python';
$client = new FaceDetection\FaceDetectionShell($cli . ' detect_faces.py');

// Load our dataset.
$images = $app->loadImages();

// Detect faces in our dataset.
foreach ($images as &$image) {
  $app->startTimer();
  $faces = $client->detectFaces($image);
  $image->setProcessingTime($app->stopTimer());

  if (!empty($faces)) {
    foreach ($faces as $face) {
      $x1 = $face['left'];
      $y1 = $face['top'];
      $x2 = $face['right'];
      $y2 = $face['bottom'];
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

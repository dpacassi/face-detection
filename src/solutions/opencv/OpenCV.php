<?php
/**
 * @file
 * A custom PHP client which runs our OpenCV script.
 */

class OpenCV {

  public function detectFaces(\FaceDetection\FaceDetectionImage $image) {
    $result = shell_exec('python opencv.py ' . $image->getImage());

    return json_decode($result, TRUE);
  }
}

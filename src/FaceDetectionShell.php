<?php

namespace FaceDetection;

/**
 * Helper class to execute shell commands to find faces.
 */
class FaceDetectionShell {

  protected $shellCommand;

  /**
   * Constructs a FaceDetectionShell object.
   *
   * @param string $shellCommand
   *   The shell command to execute to find faces in a given image.
   */
  public function __construct($shellCommand) {
    $this->shellCommand = $shellCommand;
  }

  public function detectFaces(FaceDetectionImage $image) {
    $result = shell_exec($this->shellCommand . ' ' . $image->getImage());

    // Remove unwanted log messages from the shell output.
    $result = trim(str_replace('[ INFO:0] Initialize OpenCL runtime...', '', $result));

    return json_decode($result, TRUE);
  }

  public function detectFacesFromList(FaceDetectionImage $image) {
    $faces = [];
    $result = shell_exec($this->shellCommand . ' ' . $image->getImage());
    $result = trim($result);

    if (!empty($result)) {
      $faces_in_image = preg_split('/\r\n|\r|\n/', $result);

      foreach ($faces_in_image as $face_in_image) {
        $face_in_image = explode(',', $face_in_image);

        $faces[] = [
          'left' => $face_in_image[4],
          'top' => $face_in_image[1],
          'right' => $face_in_image[2],
          'bottom' => $face_in_image[3],
        ];
      }
    }

    return $faces;
  }
}

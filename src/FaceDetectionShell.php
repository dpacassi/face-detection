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

    return json_decode($result, TRUE);
  }
}

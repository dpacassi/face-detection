<?php

namespace FaceDetection;

/**
 * Represents an image object with image specific information as well as functions
 * to draw bounding boxes and add metadata information to the image.
 */
class FaceDetectionImage {

  /**
   * The image's mime type.
   *
   * @var string
   */
  protected $mimeType;

  /**
   * The image identifier for this image.
   *
   * @var resource
   * @see \imagecreatefromjpeg()
   * @see \imagecreatefrompng()
   */
  protected $canvas;

  /**
   * The image filename including the full path.
   *
   * @var string
   */
  protected $image;

  /**
   * Defines the full path to where processed images will be saved.
   *
   * @var string
   */
  protected $outputDir;

  /**
   * Defines the directory name where to save images with bounding boxes only.
   *
   * @var string
   */
  protected $rawDir;

  /**
   * The vendor name to use on the printed image metadata.
   *
   * @var string
   */
  protected $vendorName;

  /**
   * The allocated color identifier for this image's text color.
   *
   * @var int
   * @see \imagecolorallocate()
   */
  protected $textColor;

  /**
   * The expected face count for this image or NULL if not defined.
   *
   * @var int|null
   */
  protected $expectedFaceCount;

  /**
   * The full path to this image.
   *
   * @var string
   */
  protected $path;

  /**
   * This image's filename without its path.
   *
   * @var string
   */
  protected $filename;

  /**
   * The detected face count for this image.
   *
   * @var int
   */
  protected $detectedFaceCount;

  /**
   * The processing time that the image's vendor needed to detect faces.
   *
   * @var int
   */
  protected $processingTime;

  /**
   * The allocated color identifier for this image's black color.
   *
   * @var int
   */
  protected $black;

  /**
   * Constructs a FaceDetectionImage object.
   *
   * @param $image
   *   The image filename including the full path.
   * @param $outputDir
   *   The full path to where processed images will be saved.
   * @param string $rawDir
   *   The directory name where to save images with bounding boxes only.
   * @param $vendorName
   *   The vendor name to use on the printed image metadata.
   * @param $textColor
   *   An array containing the RGB integers for the color of the text
   *   and bounding boxes to use.
   * @param null $expectedFaceCount
   *   The expected face count for this image.
   *
   * @throws \Exception
   *   If the image mime type is not supported.
   */
  public function __construct($image, $outputDir, $rawDir, $vendorName, $textColor, $expectedFaceCount=NULL) {
    $this->mimeType = image_type_to_mime_type(exif_imagetype($image));

    switch ($this->mimeType) {
      case 'image/jpeg':
        $this->canvas = imagecreatefromjpeg($image);
        break;

      case 'image/png':
        $this->canvas = imagecreatefrompng($image);
        imagesavealpha($this->canvas, TRUE);
        break;

      default:
        throw new \Exception('Unsupported image mime type: ' . $this->mimeType);
        break;
    }

    $this->image = $image;
    $this->outputDir = $outputDir;
    $this->rawDir = $rawDir;
    $this->vendorName = $vendorName;
    $this->textColor = is_array($textColor) ? imagecolorallocate($this->canvas , $textColor[0], $textColor[1], $textColor[2]) : $textColor;
    $this->expectedFaceCount = $expectedFaceCount;

    $this->path = dirname($image);
    $this->filename = basename($image);
    $this->detectedFaceCount = 0;
    $this->processingTime = 0;
    $this->black = imagecolorallocate($this->canvas, 0, 0, 0);
  }

  /**
   * Returns the image filename including the full path.
   *
   * @return string
   *   The image filename including the full path.
   */
  public function getImage() {
    return $this->image;
  }

  /**
   * Returns the full path to this image.
   *
   * @return string
   *   The full path to this image.
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * Returns this image's filename without its path.
   *
   * @return string
   *  This image's filename without its path.
   */
  public function getFilename() {
    return $this->filename;
  }

  /**
   * Returns the image's mime type.
   *
   * @return string
   *   The image's mime type.
   */
  public function getMimeType() {
    return $this->mimeType;
  }

  /**
   * Returns the processing time that the image's vendor needed to detect faces.
   *
   * @return int
   *   The processing time that the image's vendor needed to detect faces.
   */
  public function getProcessingTime() {
    return $this->processingTime;
  }

  /**
   * Sets the processing time that the image's vendor needed to detect faces.
   *
   * @param $processingTime
   *   The processing time that the image's vendor needed to detect faces.
   */
  public function setProcessingTime($processingTime) {
    $this->processingTime = $processingTime;
  }

  /**
   * Returns the detected face count for this image.
   *
   * @return int
   *   The detected face count for this image.
   */
  public function getDetectedFaceCount() {
    return $this->detectedFaceCount;
  }

  /**
   * Sets the detected face count for this image.
   *
   * @param $detectedFaceCount
   *  The detected face count for this image.
   */
  public function setDetectedFaceCount($detectedFaceCount) {
    $this->detectedFaceCount = $detectedFaceCount;
  }

  /**
   * Increases the detected face count for this image by 1.
   */
  public function increaseDetectedFaceCount() {
    $this->detectedFaceCount++;
  }

  /**
   * Returns the expected face count for this image.
   *
   * @return int|string
   *   The expected face count for this image or '-' if not defined.
   */
  public function getExpectedFaceCount() {
    if (empty($this->expectedFaceCount)) {
      return '-';
    }

    return $this->expectedFaceCount;
  }

  /**
   * Returns the success rate with two decimal points.
   *
   * @return int|string
   *   The success rate with two decimal points or '-' if the
   *   expected face count was not defined.
   */
  public function getSuccessRate() {
    if ($this->getExpectedFaceCount() === '-') {
      return '-';
    }

    return number_format($this->getDetectedFaceCount() / $this->getExpectedFaceCount() * 100, 2);
  }

  /**
   * Draws a bounding box to this image's canvas.
   *
   * @param $x1
   *   Upper left x coordinate.
   * @param $y1
   *   Upper left y coordinate 0, 0 is the top left corner of the image.
   * @param $x2
   *   Bottom right x coordinate.
   * @param $y2
   *   Bottom right y coordinate.
   *
   * @see \imagerectangle()
   */
  public function drawBoundingBox($x1, $y1, $x2, $y2) {
    imagesetthickness($this->canvas, 5);
    imagerectangle($this->canvas, $x1, $y1, $x2, $y2, $this->textColor);
  }

  /**
   * Draws a bounding box to this image's canvas defined by ratio values.
   *
   * @param $boundingBox
   *   An array containing following keys.
   *   Height: Height of the bounding box as a ratio of the overall image height.
   *   Left: Left coordinate of the bounding box as a ratio of overall image width.
   *   Top: Top coordinate of the bounding box as a ratio of overall image height.
   *   Width: Width of the bounding box as a ratio of the overall image width.
   */
  public function drawBoundingBoxFromPercentage($boundingBox) {
    // Get the image dimensions.
    $size = getimagesize($this->getImage());
    $imageWidth = $size[0];
    $imageHeight = $size[1];

    // Calculate bounding boxes.
    $x1 = round($imageWidth * $boundingBox['Left']);
    $y1 = round($imageHeight * $boundingBox['Top']);
    $x2 = $x1 + round($imageWidth * $boundingBox['Width']);
    $y2 = $y1 + round($imageHeight * $boundingBox['Height']);

    // Draw bounding box.
    $this->drawBoundingBox($x1, $y1, $x2, $y2);
  }

  /**
   * Saves the image's canvas to the defined output directory including
   * the image's metadata.
   */
  public function save() {
    // Save the image without metadata first.
    switch ($this->mimeType) {
      case 'image/jpeg':
        imagejpeg($this->canvas, $this->outputDir . $this->rawDir . $this->getFilename());
        break;

      case 'image/png':
        imagepng($this->canvas, $this->outputDir . $this->rawDir . $this->getFilename());
        break;
    }

    // Add image information to canvas.
    $boundingBox = imagettfbbox(16, 0, $this->getPath() . '/../fonts/OpenSans-Regular.ttf', 'Processing time (ms): ' . $this->getProcessingTime());

    // Only append a % if we have a real value.
    $successRate = $this->getSuccessRate();
    $successRate = $successRate === '-' ? $successRate : $successRate . ' %';

    // Draw the rectangle and add the image metadata information.
    imagefilledrectangle($this->canvas, 40, 15, 40 + $boundingBox[2] + 20, 185 + $boundingBox[3] + 5, $this->black);
    imagettftext($this->canvas, 24, 0, 50, 50, $this->textColor, $this->getPath() . '/../fonts/OpenSans-SemiBold.ttf', $this->vendorName);
    imagettftext($this->canvas, 16, 0, 50, 85, $this->textColor, $this->getPath() . '/../fonts/OpenSans-Regular.ttf', 'Image: ' . $this->getFilename());
    imagettftext($this->canvas, 16, 0, 50, 110, $this->textColor, $this->getPath() . '/../fonts/OpenSans-Regular.ttf', 'Success rate: ' . $successRate);
    imagettftext($this->canvas, 16, 0, 50, 135, $this->textColor, $this->getPath() . '/../fonts/OpenSans-Regular.ttf', 'Faces detected: ' . $this->getDetectedFaceCount());
    imagettftext($this->canvas, 16, 0, 50, 160, $this->textColor, $this->getPath() . '/../fonts/OpenSans-Regular.ttf', 'Faces expected: ' . $this->getExpectedFaceCount());
    imagettftext($this->canvas, 16, 0, 50, 185, $this->textColor, $this->getPath() . '/../fonts/OpenSans-Regular.ttf', 'Processing time: ' . $this->getProcessingTime() . ' ms');

    switch ($this->mimeType) {
      case 'image/jpeg':
        imagejpeg($this->canvas, $this->outputDir . $this->getFilename());
        break;

      case 'image/png':
        imagepng($this->canvas, $this->outputDir . $this->getFilename());
        break;
    }

    imagedestroy($this->canvas);
  }
}

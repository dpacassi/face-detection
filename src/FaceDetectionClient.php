<?php

namespace FaceDetection;

/**
 * Represents a client with vendor specific data and functions to process the
 * defined image dataset as well as exporting the results into a CSV file.
 */
class FaceDetectionClient {

  /**
   * Defines the full path to where processed images will be saved.
   *
   * @var string
   */
  protected $outputDir;

  /**
   * The vendor name to use on the printed image metadata.
   *
   * @var string
   */
  protected $vendorName;

  /**
   * An array containing the RGB integers for the color of the text
   * and bounding boxes to use.
   *
   * @var array
   */
  protected $textColor;

  /**
   * Defines the full path to our image dataset to be processed.
   *
   * @var string
   */
  protected $datasetDir;

  /**
   * Defines the directory name where to save images with bounding boxes only.
   *
   * @var string
   */
  protected $rawDir;

  /**
   * Our image dataset represented as an array of \FaceDetectionImage objects.
   *
   * @var \FaceDetection\FaceDetectionImage[]
   */
  protected $images;

  /**
   * Helper to measure how long a run took.
   *
   * @var int
   */
  protected $startTime;

  /**
   * Helper which contains the expected face counts per image.
   *
   * @var array
   */
  protected $expectedFaceCounts;

  /**
   * Constructs a FaceDetectionClient object.
   *
   * @param string $outputDir
   *   The full path to where processed images will be saved.
   * @param null $vendorName
   *   The vendor name to use on the printed image metadata.
   * @param array $textColor
   *   An array containing the RGB integers for the color of the text
   *   and bounding boxes to use.
   * @param string $datasetDir
   *   The full path to the image dataset to be processed.
   * @param string $rawDir
   *   The directory name where to save images with bounding boxes only.
   */
  public function __construct($outputDir, $vendorName=NULL, $textColor=[255, 0, 0], $datasetDir=__DIR__ . '/../dataset/', $rawDir='raw/') {
    $this->outputDir = __DIR__ . '/../dataset-output/' . $outputDir . '/';
    $this->vendorName = empty($vendorName) ? dirname($outputDir) : $vendorName;
    $this->textColor = $textColor;
    $this->datasetDir = $datasetDir;
    $this->rawDir = $rawDir;

    $this->images = [];
    $this->startTime = 0;
    $this->expectedFaceCounts = [];

    // Ensure the output directories exists.
    $this->createDirectory($this->outputDir);
    $this->createDirectory($this->outputDir . $this->rawDir);

    // Parse face counts file.
    $this->parseFaceCounts();
  }

  /**
   * Makes sure that the given directory exists.
   *
   * @param $directory
   *   The directory to create (if not existing yet).
   */
  protected function createDirectory($directory) {
    if (!file_exists($directory)) {
      mkdir($directory, 0755, TRUE);
    }
  }

  /**
   * Parses the face_counts.ini file located in the specified dataset directory
   * and saves the result in a local array.
   */
  protected function parseFaceCounts() {
    if (file_exists($this->datasetDir . 'face_counts.ini')) {
      $this->expectedFaceCounts = parse_ini_file($this->datasetDir . 'face_counts.ini', TRUE);
    }
  }

  /**
   * Checks if an expected face count was defined for a given filename.
   * The expected face count number is read from the face_counts.ini file.
   *
   * @param $filename
   *   The image filename for which to check if an expected face count has been
   *   defined.
   *
   * @return int|null
   *   The expected face count for this filename defined in the face_counts.ini
   *   file or NULL otherwise.
   *
   * @see \FaceDetectionClient::parseFaceCounts()
   *   How the face_counts.ini file is being read.
   */
  protected function getFaceCountForFilename($filename) {
    if (array_key_exists($filename, $this->expectedFaceCounts)) {
      return $this->expectedFaceCounts[$filename];
    }

    return NULL;
  }

  /**
   * Returns the CSV header row columns according to the images array
   * defined in the object.
   *
   * @return array
   *   The header row column names as an array.
   */
  protected function getCSVHeaders() {
    $headers = ['Vendor'];

    foreach ($this->images as &$image) {
      $headers[] = $image->getFilename() . ' (Success rate)';
      $headers[] = $image->getFilename() . ' (Faces detected)';
      $headers[] = $image->getFilename() . ' (Faces expected)';
      $headers[] = $image->getFilename() . ' (Time)';
    }

    $headers[] = 'Total success rate';
    $headers[] = 'Total faces detected';
    $headers[] = 'Total faces expected';
    $headers[] = 'Total processing time (ms)';
    $headers[] = 'Average processing time (ms)';

    return $headers;
  }

  /**
   * Returns the false positives CSV header row columns according to
   * the images array defined in the object.
   *
   * @return array
   *   The false positives header row column names as an array.
   */
  protected function getFalsePositivesCSVHeaders() {
    $headers = ['Vendor'];

    foreach ($this->images as &$image) {
      $headers[] = $image->getFilename() . ' (Faces detected)';
      $headers[] = $image->getFilename() . ' (False positives)';
      $headers[] = $image->getFilename() . ' (False positives %)';
    }

    $headers[] = 'Total faces detected';
    $headers[] = 'Total false positives';
    $headers[] = 'Average false positives';

    return $headers;
  }

  /**
   * Loops over all images to get the total count of faces detected.
   *
   * @return int
   *   The total count of all faces detected.
   */
  public function getTotalDetectedFaceCount() {
    $totalFaceCount = 0;

    foreach ($this->images as &$image) {
      $totalFaceCount += $image->getDetectedFaceCount();
    }

    return $totalFaceCount;
  }

  /**
   * Loops over all images to get the total count of faces expected.
   *
   * @return int
   *   The total count of all faces expected.
   */
  public function getTotalExpectedFaceCount() {
    $totalExpectedFaceCount = 0;

    foreach ($this->images as &$image) {
      $totalExpectedFaceCount += $image->getExpectedFaceCount();
    }

    return $totalExpectedFaceCount;
  }

  /**
   * Loops over all images to get the total processing time.
   *
   * @return int
   *   The total processing time.
   */
  public function getTotalProcessingTime() {
    $totalProcessingTime = 0;

    foreach ($this->images as &$image) {
      $totalProcessingTime += $image->getProcessingTime();
    }

    return $totalProcessingTime;
  }

  /**
   * Loops over all images to get the total success rate.
   *
   * @return int
   *   The success rate with two decimal points.
   */
  public function getTotalSuccessRate() {
    $totalDetectedFaceCount = 0;
    $totalExpectedFaceCount = 0;

    foreach ($this->images as &$image) {
      if ($image->getExpectedFaceCount() !== '-') {
        $totalDetectedFaceCount += $image->getDetectedFaceCount();
        $totalExpectedFaceCount += $image->getExpectedFaceCount();
      }
    }

    return number_format($totalDetectedFaceCount / $totalExpectedFaceCount * 100, 2);
  }

  /**
   * Lists all images in the defined dataset directory and creates
   * FaceDetectionImage objects from them.
   *
   * @return \FaceDetection\FaceDetectionImage[]
   *   An array of FaceDetectionImage objects.
   */
  public function loadImages() {
    $filenames = glob($this->datasetDir . '*.{jpg,jpeg,png}', GLOB_BRACE);

    foreach ($filenames as &$fullFilePath) {
      $filename = basename($fullFilePath);

      try {
        $this->images[] = new FaceDetectionImage($fullFilePath, $this->outputDir, $this->rawDir, $this->vendorName, $this->textColor, $this->getFaceCountForFilename($filename));
      } catch (\Exception $e) {
        print $e->getMessage();
      }
    }

    return $this->images;
  }

  /**
   * Writes analytical results to a CSV file.
   *
   * If the CSV file already exists, it will only append it's data into a new row.
   * In case the CSV file doesn't exist yet, it will create the CSV file
   * including the CSV header row.
   */
  public function exportCSV() {
    $columns = [$this->vendorName];

    foreach ($this->images as &$image) {
      $columns[] = $image->getSuccessRate();
      $columns[] = $image->getDetectedFaceCount();
      $columns[] = $image->getExpectedFaceCount();
      $columns[] = $image->getProcessingTime();
    }

    $columns[] = $this->getTotalSuccessRate();
    $columns[] = $this->getTotalDetectedFaceCount();
    $columns[] = $this->getTotalExpectedFaceCount();
    $columns[] = $this->getTotalProcessingTime();
    $columns[] = $this->getTotalProcessingTime() / count($this->images);

    if (file_exists($this->outputDir . '../results.csv')) {
      $fp = fopen($this->outputDir . '../results.csv', 'a');
    }
    else {
      $fp = fopen($this->outputDir . '../results.csv', 'w');
      fputcsv($fp, $this->getCSVHeaders());
    }

    fputcsv($fp, $columns);
    fclose($fp);
  }

  /**
   * Prepares the a false positives CSV file with a header row but no data.
   *
   * As for now, the data has to be entered manually to the CSV.
   * This might change in the future.
   */
  public function exportFalsePositivesCSVHeaders() {
    $fp = fopen($this->outputDir . '../false_positives.csv', 'w');
    fputcsv($fp, $this->getFalsePositivesCSVHeaders());
    fclose($fp);
  }

  /**
   * Sets the start time to just now for tracking process duration.
   */
  public function startTimer() {
    $this->startTime = round(microtime(TRUE) * 1000);
  }

  /**
   * Stops the timer and returns the difference to the start time.
   *
   * @return float|int
   *   The duration since the start time.
   */
  public function stopTimer() {
    return round(microtime(TRUE) * 1000) - $this->startTime;
  }
}

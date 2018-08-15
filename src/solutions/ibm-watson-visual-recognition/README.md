# IBM Watson Visual Recognition
Unfortunately IBM doesn't provide a PHP SDK for their Visual Recognition API.  
We use a custom `VisualRecognitionV3` class witch sends cURL requests to their API.
 
This code example requires [Composer](https://getcomposer.org/) to be installed locally.

## Installation
1. Run `composer install`
2. Copy the `.env.example` file to `.env` and and fill in your IBM Watson API key

## Running the face detection
Simply run `php index.php`, your images with face detection will be saved in `/dataset-output/ibm-watson-visual-recognition`.

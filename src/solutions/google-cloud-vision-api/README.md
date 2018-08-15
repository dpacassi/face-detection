# Google Cloud Vision API
You can read the complete documentation on the Google Cloud Vision API and the provided SDK's on [cloud.google.com](https://cloud.google.com/vision/docs/face-tutorial).  
This code example uses their PHP SDK and requires [Composer](https://getcomposer.org/) to be installed locally.

## Installation
1. Run `composer install`
2. Copy the `.env.example` file to `.env` and and fill in the path to your Google application credentials

## Running the face detection
Simply run `php index.php`, your images with face detection will be saved in `/dataset-output/google-cloud-vision-api`.

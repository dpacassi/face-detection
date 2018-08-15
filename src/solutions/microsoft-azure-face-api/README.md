# Microsoft Face API
Unfortunately Microsoft doesn't provide a PHP SDK for their Face API.  
We use a custom `MicrosoftFaceAPI` class witch sends cURL requests to their API.
 
This code example requires [Composer](https://getcomposer.org/) to be installed locally.

## Installation
1. Run `composer install`
2. Copy the `.env.example` file to `.env` and and fill in your Microsoft Face API subscription key

## Running the face detection
Simply run `php index.php`, your images with face detection will be saved in `/dataset-output/microsoft-azure-face-api`.

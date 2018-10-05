# Face detection - An overview and comparison of different solutions
This repository holds the implementation of the face detection solutions listed in
my blog posts.
- [Part 1: SaaS vendors](https://www.liip.ch/en/blog/face-detection-an-overview-and-comparison-of-different-solutions-part1)
- Part 2: Open source options

## How does this work?
All face detection implementations are stored in the `/src/solutions` folder.
If you want to run the face detection code yourself, read the relevant `README.md` file located
in each solution's folder.

### Custom classes
To add meta information to the images and generate a CSV export, following two classes have been written:
- [FaceDetectionClient](src/FaceDetectionClient.php)
- [FaceDetectionImage](src/FaceDetectionImage.php)

In order to execute shell commands to find faces, following class has been written:
- [FaceDetectionShell](src/FaceDetectionShell.php)

## Where's what?
- Dataset with images to be processed: `/dataset`
- Information about how many faces to expect per image in the dataset (to calculate the success rate): `/dataset/face_counts.ini`
- Processed datasets with metadata on the images: `/dataset-output`
- CSV file with analytical data: `/dataset-output/results.csv`
- Implementation of each solution: `/src/solutions/<solution>`
- Custom classes: `/src/solutions`

**Important:** The `dataset-solutions` folder has been added to `.gitignore` in order not to blow up the Git repository size.  
In case you're interested to see the processed images used in my blog posts, you can download the complete set
from our [file server](https://file.ac/ULMGq4AH8jg/).

## Installation
As stated above, the code for each face detection solution can be found in the `/src/solutions` folder.
If you want to run the code locally, please read the corresponding `README.md` file located in the
`/src/solutions` sub folders.

### SaaS vendors
- [Amazon Rekognition](src/solutions/amazon-rekognition)
- [Google Cloud Vision API](src/solutions/google-cloud-vision-api)
- [IBM Watson Visual Recognition](src/solutions/ibm-watson-visual-recognition)
- [Microsoft Face API](src/solutions/microsoft-azure-face-api)

### Open source options
- [Ageitgey - Face Recognition](src/solutions/ageitgey-face_recognition)
- [Dlib - CNN](src/solutions/dlib-cnn)
- [Dlib - HOG](src/solutions/dlib-hog)
- [OpenCV - Deep learning](src/solutions/opencv-deep-learning)
- [OpenCV - Haar](src/solutions/opencv-haar)
- [OpenCV - Haar (tweaked)](src/solutions/opencv-haar-tweaked)
- [OpenCV - LBP](src/solutions/opencv-lbp)

## CSV Export
Each solution run checks if a [results.csv](dataset-output/results.csv) file exists.
If so, it will attach it's data to this CSV file. If not, it will create the file with the corresponding headers.

## Dataset
The face detection solutions will try to find faces in all images in the `/dataset` directory.  
You can extend the images or replace them with your own, as you wish.  
**Important:** Only JPG and PNG images are supported for now!

All images currently stored in the `/dataset` directory were downloaded from [pexels.com](https://www.pexels.com/),
many thanks to the contributors and photographers of the images and also to Pexels!

### Downloaded images
- [9746](https://www.pexels.com/photo/selfie-family-generation-father-9746/)
- [9816](https://www.pexels.com/photo/people-crowd-walking-9816/)
- [34692](https://www.pexels.com/photo/crowd-music-musician-street-performer-34692/)
- [109919](https://www.pexels.com/photo/people-brasil-guys-avpaulista-109919/)
- [163087](https://www.pexels.com/photo/couple-standing-next-to-each-other-163087/)
- [167637](https://www.pexels.com/photo/man-in-black-crew-neck-shirt-holding-a-black-electric-guitar-167637/)
- [211050](https://www.pexels.com/photo/man-sitting-next-to-couple-of-person-walking-on-the-street-during-daytime-211050/)
- [233129](https://www.pexels.com/photo/architecture-buildings-business-establishment-city-233129/)
- [267885](https://www.pexels.com/photo/accomplishment-ceremony-education-graduation-267885/)
- [280002](https://www.pexels.com/photo/army-authority-drill-instructor-group-280002/)
- [307847](https://www.pexels.com/photo/portrait-of-man-on-city-street-307847/)
- [325521](https://www.pexels.com/photo/group-of-people-enjoying-music-concert-325521/)
- [356147](https://www.pexels.com/photo/adult-anger-art-black-background-356147/)
- [403448](https://www.pexels.com/photo/adult-black-and-white-close-up-dandelion-403448/)
- [428364](https://www.pexels.com/photo/adult-businessman-close-up-corporate-428364/)
- [546162](https://www.pexels.com/photo/beautiful-creative-daylight-enjoyment-546162/)
- [687501](https://www.pexels.com/photo/monochrome-photography-of-a-person-687501/)
- [708392](https://www.pexels.com/photo/group-of-people-having-fun-together-under-the-sun-708392/)
- [711009](https://www.pexels.com/photo/group-of-people-reading-book-sitting-on-chair-711009/)
- [745045](https://www.pexels.com/photo/group-of-people-sitting-on-white-mat-on-grass-field-745045/)
- [761963](https://www.pexels.com/photo/photography-of-woman-listening-to-music-761963/)
- [787961](https://www.pexels.com/photo/photo-of-women-wearing-masks-787961/)
- [837306](https://www.pexels.com/photo/shallow-focus-photography-of-man-wearing-eyeglasses-837306/)
- [840996](https://www.pexels.com/photo/man-in-white-dress-shirt-sitting-on-black-rolling-chair-while-facing-black-computer-set-and-smiling-840996/)
- [889545](https://www.pexels.com/photo/group-of-people-on-road-with-assorted-color-smokes-889545/)
- [914181](https://www.pexels.com/photo/people-gathered-in-room-having-a-party-914181/)
- [923657](https://www.pexels.com/photo/four-men-sitting-on-platform-923657/)
- [933964](https://www.pexels.com/photo/group-of-friends-hanging-out-933964/)
- [948199](https://www.pexels.com/photo/woman-wearing-red-shirt-drinking-948199/)
- [1116302](https://www.pexels.com/photo/group-of-people-forming-star-using-their-hands-1116302/)
- [1117256](https://www.pexels.com/photo/crowd-of-people-gathering-during-golden-hour-1117256/)
- [1181562](https://www.pexels.com/photo/woman-in-gray-formal-coat-sitting-near-black-full-glass-panel-window-1181562/)
- [1185440](https://www.pexels.com/photo/group-of-people-standing-waiting-outside-the-bar-1185440/)

## Contribution
Want to extend the listed solutions or simply enhance existing code?  
I'm happy to receive and accept pull requests!

## License
```
MIT License

Copyright (c) 2018 David Pacassi Torrico

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

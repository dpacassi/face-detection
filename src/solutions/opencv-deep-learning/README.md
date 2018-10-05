# OpenCV - Deep learning
Detect faces using [OpenCV's deep neural network module](https://github.com/opencv/opencv/wiki/Deep-Learning-in-OpenCV).  

## Requirements
- OpenCV 3.3+
- Python

## Installation
There are lots of different ways on how to install OpenCV and it's Python module.  
The easiest way would be to install it through the **unofficial** [Python package](https://pypi.org/project/opencv-python/) using Pip.  
You can also compile OpenCV on your machine yourself, there are many tutorials covering this topic already.
For OSX you can e.g. try out [this tutorial](https://www.learnopencv.com/install-opencv3-on-macos/).

### OpenCV Deep Neural Network
In addition to OpenCV, you'll also need a network model. While you're able to train your own,
you can also use pre-trained models.  

The pre-trained model in this repository consists of following files:
- [deploy.prototxt](https://github.com/opencv/opencv/blob/master/samples/dnn/face_detector/deploy.prototxt) - The `.prototxt` file with text description of the network architecture
- [res10_300x300_ssd_iter_140000.caffemodel](https://github.com/opencv/opencv_3rdparty/tree/dnn_samples_face_detector_20170830) - The pre-trained face detector DNN model 

## Running the face detection
Simply run `php index.php`, your images with face detection will be saved in `/dataset-output/opencv-deep-learning`.

### Note
The PHP script uses by default the `python` command line program.  
If you want to use another command line program (e.g. `python3`),
simply run `composer install` and copy the `.env.example` file to `.env` and customize the command line program.

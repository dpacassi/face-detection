# Dlib - CNN
Detect faces using [Dlib's pre-trained deep learning model](http://dlib.net/python/index.html#dlib.cnn_face_detection_model_v1).  

## Requirements
- C++
- CMake
- Python

## Installation
Compile the [Dlib library](http://dlib.net/compile.htm) in your environment.  
Since we use a Python script to detect faces, you will also need
to compile the [Dlib Python API](https://github.com/davisking/dlib#compiling-dlib-python-api).

The PHP script uses the `python` command line program.  
If you want to use another command line program (e.g. `python3`),
simply run `composer install` and copy the `.env.example` file to `.env` and customize the command line program.

## Running the face detection
Simply run `php index.php`, your images with face detection will be saved in `/dataset-output/dlib-cnn`.

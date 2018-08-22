import json
import sys
import cv2
import os
from skimage import io
import logging
import warnings

# Suppress any output to stdout.
devnull = open('/dev/null', 'w')
default_stdout = os.dup(sys.stdout.fileno())
os.dup2(devnull.fileno(), 1)

faces_result = []

# Take the image file name from the command line.
file_name = sys.argv[1]

# Load the cascade classifier training file for haarcascade.
haar_face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_alt.xml')

image = io.imread(file_name)

# Convert the image to grayscale mode as the OpenCV face detector expects images that way.
image = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

# Run the face detector on the image data.
detected_faces = haar_face_cascade.detectMultiScale(image, scaleFactor=1.1, minNeighbors=10, minSize=(25, 25));

# Loop through each detected face.
for (x, y, w, h) in detected_faces:
  faces_result.append({
    "left": int(x),
    "top": int(y),
    "right": int(x + w),
    "bottom": int(y + h)
  })

# Reactivate output to stdout.
os.dup2(default_stdout, 1)

print(json.dumps(faces_result))

# Suppress any output to stdout.
os.dup2(devnull.fileno(), 1)

import json
import sys
import cv2
from skimage import io

faces_result = []

# Take the image file name from the command line.
file_name = sys.argv[1]

# Load the cascade classifier training file for haarcascade.
haar_face_cascade = cv2.CascadeClassifier('data/haarcascade_frontalface_alt.xml')

# Load the image.
image = io.imread(file_name)

# Convert the image to grayscale mode as the OpenCV face detector expects images that way.
image = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)

# Run the face detector on the image data.
detected_faces = haar_face_cascade.detectMultiScale(image);

# Loop through each detected face.
for (x, y, w, h) in detected_faces:
  faces_result.append({
    "left": int(x),
    "top": int(y),
    "right": int(x + w),
    "bottom": int(y + h)
  })

print(json.dumps(faces_result))

import json
import sys
import dlib
from skimage import io

faces_result = []

# Take the image file name from the command line.
file_name = sys.argv[1]

# Create a Histogram of Oriented (HOG) & linear classifier based face detector using the dlib class.
face_detector = dlib.get_frontal_face_detector()

# Load the image into an array.
image = io.imread(file_name)

# Run the HOG face detector on the image data.
# The result will be the bounding boxes of the faces in our image.
detected_faces = face_detector(image, 1)

# Loop through each detected face.
for i, face_rect in enumerate(detected_faces):
  faces_result.append({
    "left": face_rect.left(),
    "top": face_rect.top(),
    "right": face_rect.right(),
    "bottom": face_rect.bottom()
  })

print(json.dumps(faces_result))

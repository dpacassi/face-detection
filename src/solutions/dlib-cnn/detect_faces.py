import json
import sys
import dlib
from skimage import io


def main():
    faces_result = []

    # Take the image file name from the command line.
    file_name = sys.argv[1]

    # Initialize a CNN based face detector with an existing model.
    face_detector = dlib.cnn_face_detection_model_v1('data/mmod_human_face_detector.dat')

    # Load the image.
    image = io.imread(file_name)

    # Run the CNN face detector on the image data.
    detected_faces = face_detector(image, 1)

    # Loop through each detected face.
    for face in detected_faces:
        faces_result.append({
            "left": face.rect.left(),
            "top": face.rect.top(),
            "right": face.rect.right(),
            "bottom": face.rect.bottom()
        })

    print(json.dumps(faces_result))


if __name__=="__main__":
    main()

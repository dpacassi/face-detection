import json
import sys
import cv2
import numpy as np


def main(minmum_confidence):
    faces_result = []

    # Take the image file name from the command line.
    file_name = sys.argv[1]

    # Load the image, resize to 300x300 pixels and normalize it.
    image = cv2.imread(file_name)
    (h, w) = image.shape[:2]
    blob = cv2.dnn.blobFromImage(image)

    # Load our serialized model file.
    net = cv2.dnn.readNetFromCaffe('data/deploy.prototxt.txt', 'data/res10_300x300_ssd_iter_140000.caffemodel')

    # Run the face detector on the image data.
    net.setInput(blob)
    detections = net.forward()

    # Loop through each detected face.
    for i in range(0, detections.shape[2]):
        confidence = detections[0, 0, i, 2]

        if confidence > minmum_confidence:
            box = detections[0, 0, i, 3:7] * np.array([w, h, w, h])
            (startX, startY, endX, endY) = box.astype('int')
            faces_result.append({
                "left": int(startX),
                "top": int(startY),
                "right": int(endX),
                "bottom": int(endY)
            })

    print(json.dumps(faces_result))


if __name__=="__main__":
    main(0.9)

import json
import sys
import cv2
import numpy as np


def main(minmum_confidence):
    faces_result = []

    # Take the image file name from the command line.
    file_name = sys.argv[1]

    # Load the image.
    image = cv2.imread(file_name)
    (h, w) = image.shape[:2]
    blob = cv2.dnn.blobFromImage(image, 1.0, (w, h), (104.0, 177.0, 123.0))

    # Load our serialized model file.
    net = cv2.dnn.readNetFromCaffe('data/deploy.prototxt', 'data/res10_300x300_ssd_iter_140000.caffemodel')

    # Run the face detector on the image data.
    net.setInput(blob)
    detections = net.forward()

    # Loop through each detected face.
    for i in range(0, detections.shape[2]):
        confidence = detections[0, 0, i, 2]

        if confidence > minmum_confidence:
            box = detections[0, 0, i, 3:7] * np.array([w, h, w, h])
            (startX, startY, endX, endY) = box.astype('int')

            if startX > w or endX > w or startY > h or endY > h:
                # This boundary box is outside of the image, ignore it.
                continue

            faces_result.append({
                "left": int(startX),
                "top": int(startY),
                "right": int(endX),
                "bottom": int(endY)
            })

    print(json.dumps(faces_result))


if __name__=="__main__":
    main(0.9)

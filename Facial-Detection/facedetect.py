import cv2
count = 0

face_cascade = cv2.CascadeClassifier('haarcascade_frontalface_default.xml')

img = cv2.imread('test.jpg')

gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)

faces = face_cascade.detectMultiScale(gray, 1.1, 4)

for (x, y, w, h) in faces:
    cv2.rectangle(img, (x, y), (x+w, y+h), (255, 0, 0), 2)
    count += 1

cv2.imshow('img', img)
scount = str(count)
f = open("nfaces.txt", "w+")
f.write(scount)
f.close()
print(count)
cv2.waitKey()
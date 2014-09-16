import numpy as np
import cv2,cv
import sys

difference = cv2.imread("1_l.png")

row=1
col=2
difference[row,col,1]=255
cv2.imwrite('vision.png',difference)

sys.exit()


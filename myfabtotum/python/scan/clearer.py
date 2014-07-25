import cv2
import sys
import cv

#img = cv2.imread("0.png")
img_l = cv2.imread("0_l.png")

hue_min=228
hue_max=255

sat_min=30
sat_max=100

val_min=50
val_max=100

channels = {'hue': None,'saturation': None,'value': None,'laser': None,}

def threshold_image(channel):
	if channel == "hue":
		minimum = hue_min
		maximum = hue_max
	elif channel == "saturation":
		minimum = sat_min
		maximum = sat_max
	elif channel == "value":
		minimum = val_min
		maximum = val_max

	(t, img) = cv2.threshold(channels[channel],minimum,maximum,cv2.THRESH_BINARY | cv2.THRESH_OTSU) 
	# Replace this channel with the threshold'ed image
	channels[channel] = img

hsv_img = cv2.cvtColor(img_l, cv.CV_BGR2HSV)

#cv2.imwrite('hsv.png',hsv_image)

# split the video frame into color channels
h, s, v = cv2.split(hsv_img)

channels['hue'] = h
channels['saturation'] = s
channels['value'] = v

# Threshold ranges of HSV components; storing the results in place
threshold_image("hue")
threshold_image("saturation")
threshold_image("value")

# Perform an AND on HSV components to identify the laser!
channels['laser'] = cv2.bitwise_and(channels['hue'],channels['value'])

cv2.imwrite('laser.png',channels['laser'])

hsv_image = cv2.merge([channels['hue'],channels['saturation'],channels['value']])		
		
cv2.imwrite('res.png',hsv_image)
print "done"

sys.exit()
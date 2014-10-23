# -*- coding: utf-8 -*-

"""
Created: 2011-01-26
Last Modified: 2011-02-14
Authors: Omar Abo-Namous, Andreas Poesch, Moritz Krauss
Original from https://raw.githubusercontent.com/toomuchcookies/Python-Geometry
"""


from math import pi, sqrt, acos, cos, sin
import numpy as np
import numbers
import scipy.linalg as la

precision_epsilon = 6            ## precision 10^-(precision_epsilon) is considered zero (e.g. for matching points)

class Point:
    def __init__(self, x, y, z):
        """ initializing constructor
            x,y,z -- point coordinates or, respictively, vector subelements
        """
        self.x = float(x)
        self.y = float(y)
        self.z = float(z)
    
    def abs(self):
        """ float a = absolute length of vector ||v||
        
        """
        return sqrt(self.x*self.x + self.y*self.y + self.z*self.z)
    
    def dist(self, other):
        """ float d = distance self point to other point ||other - self||
            
            other -- another Point 
        """
        temp = other - self
        return temp.abs()
        
    def normalized(self):
        """ Point n = self normalized
        
            returns a normalized (length 1.0) vector with same direction as self
        """
        if self.abs() == 0.0:
            raise ValueError, 'Cannot normalize Null-vector'
   
        N = self / self.abs();
        return N
        
    
    def cross(self, other):
        """ Point c = self x other
        
            other -- another Point
        """
        return Point(self.y*other.z - self.z*other.y, self.z*other.x - self.x*other.z, self.x*other.y - self.y*other.x)
    
    def dot(self, other):
        """ float d = self * other
        
            other -- another point
        """
        return (self.x*other.x + self.y*other.y + self.z*other.z)
        
    def transform(self, T):
        """ Point t = T * self,  
        
            T -- transformation matrix (expected to be a 4x4 matrix)
        """
        nx = T[0,0] * self.x + T[0,1] * self.y + T[0,2] * self.z + T[0,3]
        ny = T[1,0] * self.x + T[1,1] * self.y + T[1,2] * self.z + T[1,3]
        nz = T[2,0] * self.x + T[2,1] * self.y + T[2,2] * self.z + T[2,3]
        nh = T[3,0] * self.x + T[3,1] * self.y + T[3,2] * self.z + T[3,3]
        
        return Point(nx/nh, ny/nh ,nz/nh)

    def transformNoTranslation(self, T):
        """ Point t = T * self,  
        
            T -- transformation matrix (expected to be a 4x4 matrix)
            
            The translation part (4th column vector is omitted)
        """
        nx = T[0,0] * self.x + T[0,1] * self.y + T[0,2] * self.z
        ny = T[1,0] * self.x + T[1,1] * self.y + T[1,2] * self.z
        nz = T[2,0] * self.x + T[2,1] * self.y + T[2,2] * self.z
        
        return Point(nx, ny ,nz)
        
    def near(self, other):
        """ BOOL return true if self is approximately (global precision setting) other
        """
        if round(self.dist(other), precision_epsilon) == 0.0:
            return True
        else:
            return False
        
    def __eq__(self,other):
        """ BOOL equals
            true when self == other
            
            other -- point to compare with
        """
        if self.x==other.x and self.y==other.y and self.z==other.z:
            return True
        else:
            return False
    
    def __ne__(self,other):
        """ BOOL equals not
            true when self != other
            
            other -- point to compare with
        """
        if self==other:
            return false
        else:
            return true
    
    def __sub__(self,other):
        """ Point difference = self - other
            
            other -- point/vector to substract
        """
        return Point(self.x - other.x, self.y - other.y, self.z - other.z)
    
    def __add__(self,other):
        """ Point sum = self + other
            
            other -- point/vector to add
        """
        return Point(self.x + other.x, self.y + other.y, self.z + other.z)
    
    def __neg__(self):
        """ Point n = -self 
            inverse of self
        """
        return Point(-self.x, -self.y, -self.z)
    
    def __mul__(self, other):
        """    m = self * other (scale)
        
            other -- float/int to multiply with
        """
        if type(other) == float or type(other) == int:
            return Point(self.x * other, self.y * other, self.z * other)
        else:
            raise TypeError, 'The arguments passed must be Numerical'

    def __rmul__(self, other):
        """ Point rm = self * other 
        
            other -- float/int to multiply with
        """
        if type(other) == float or type(other) == int:
            return Point(self.x * other, self.y * other, self.z * other)
        else:
            raise TypeError, 'The arguments passed must be Numerical'
            
    def __div__(self, other):
        """ Point d = self / other
        
            other -- float/int to divide by
        """
        if type(other) == float or type(other) == int:
            return Point(self.x/other, self.y/other, self.z/other)
        else:
            raise TypeError, 'The arguments passed must be Numerical'
    
    def __gt__(self, other):
        """ return true if ||self|| > ||other||
        """
        if isinstance(other, Point):
            return (self.abs() > other.abs())
        elif type(other) == numbers.Real:
            return (self.abs() > other)

    def __lt__(self, other):
        """ return true if ||self|| < ||other||
        """
        if isinstance(other, Point):
            return (self.abs() < other.abs())
        elif type(other) == numbers.Real:
            return (self.abs() < other)
            
    def __repr__(self):
        """ Printable output values
        """
        return "Point(" + repr(self.x) + ", " + repr(self.y) + ", " + repr(self.z) + ")"
        
    def __getitem__(self, other):
        """
            
        """
        values = {0:self.x,1:self.y,2:self.z}
        return values.get(other)
    
    def aslist(self):
        """ return elements in a list
        """
        return [self.x, self.y, self.z]
    
    def asarray(self):
        """    return elements as an array
        """
        return np.array([self.x,self.y,self.z])
        

class Line:
    def __init__(self, P, vec=Point(0,0,0)):
        """ initializing constructor
            
            P -- either list of two or more points where first element is point contained by line, second element is directional vector
            vec -- if P is just a single point (not a list of points) then vec shall represent the line's directional vector, defaults to (0,0,0)
            
            The normal vector vec is normalized in length
        """
        if type(P) == list and len(P) > 1:
            if isinstance(P[0],Point) and isinstance(P[1],Point):
                self.P = P[0]
                self.vec = P[1] - P[0]
            else:
                raise TypeError, 'Line expects a list of two Points or a Point a directional Point'
        elif isinstance(P,Point) and isinstance(vec, Point) and vec.abs() > 0:
            self.P = P
            self.vec = vec
        else:
            raise TypeError, 'Line expects a {list of two Points} or {a Point a directional vector}'
        
        veclen = float(self.vec.abs())
        if (veclen == 0):
            raise ValueError, 'directional vector is NULL vector'
        self.vec = self.vec / veclen        #normalize directional vector
     
    def findnearestPoint(self, to=Point(0.,0.,0.)):
        """ return point of self (line) that is closest possible to 'to'
            if point is not on line then the point of line that is closest to point (in eucledian space) is returned
            
            to -- point to examine
        """
        P = self.P
        vec = self.vec
        u = float(vec.dot(to - P))/float(vec.dot(vec))
        return (P + (u*vec))
        
    def dist(self, other):
        """    distance: line (self) to point (other)
        """
        if isinstance(other,Point):
            P = self.findnearestPoint(other)
            return P.dist(other)
        else:
            raise TypeError, 'Line.dist expects a Point as parameter'

    def __repr__(self):
        """ return printable string of object
        """
        return "Line(" + repr(self.P) + ", " + repr(self.vec) + ")"

    def stretchIntersect(self, P0, P1):
        """ intersect self (Line) and a line L through P0 and P1
            with L = P0 + alpha * (P1 - P0) and return the
            value of alpha of intersection point or two lines closest points on L
            and the closest point on self
            
        """
        A = self.P - P0
        B = self.vec
        C = P1 - P0
     
        print B.cross(C)
        angle = (B.cross(C)).abs()
        
        if round(angle, precision_epsilon) != 0.0:
            ma = ((A.dot(C)*C.dot(B)) - (A.dot(B)*C.dot(C)))/ \
                ((B.dot(B)*C.dot(C)) - (C.dot(B)*C.dot(B)))
            ma = float(ma)
            mb = (ma*C.dot(B) + A.dot(C))/ C.dot(C)
            mb = float(mb)
            Pa = self.P + (self.vec * ma)
            Pb = P0 + (C * mb)
            return [Pa, Pb, mb]
        else:
            return None        #lines are parallel: no intersection
        
        

    def lineintersect(self,other):
        """ calculate point of intersection for two lines 
            intersection is considered valid with an allowance of the global precision parameter
            
            returns Pa = point on self that is closest to other
                    Pb = point on other that is closest to self
                    
                    if Pa.near(Pb) --> lines do intersect
                    
            if lines are parallel: returning None
        """
        A = self.P-other.P
        B = self.vec
        C = other.vec
        # Check for parallel lines
        cp12 = B.cross(C)
        absCp12 = cp12.abs()
        if round(absCp12, precision_epsilon) != 0.0:
            ma = ((A.dot(C)*C.dot(B)) - (A.dot(B)*C.dot(C)))/ \
                ((B.dot(B)*C.dot(C)) - (C.dot(B)*C.dot(B)))
            ma = float(ma)
            mb = (ma*C.dot(B) + A.dot(C))/ C.dot(C)
            mb = float(mb)
            Pa = self.P + (self.vec * ma)
            Pb = other.P + (other.vec * mb)
            return [Pa, Pb]
        else:
            return None

    def intersect(self, other):
        """ intersect with line or plane
            the appropriate fn is sub-called
        """
        if isinstance(other,Plane):
            return other.lineintersect(self)
        elif isinstance(other,Line):
            return self.lineintersect(other)
        else:
            return None
    
    def rotatearound(self,points,theta):
        """ rotate points around self with the angle theta
        
        Assume points is a Point or a list of Points
        """
        theta = pi*theta/180
        if isinstance(points,Point):
            points = [points]
        # Translate so axis is at origin
        for i in range(len(points)):
            points[i] = points[i] - self.P
        # Matrix common factors     
        c = cos(theta)
        t = (1 - cos(theta))
        s = sin(theta)
        X = self.vec.x
        Y = self.vec.y
        Z = self.vec.z
        # Matrix 'M'
        d11 = t*X**2 + c
        d12 = t*X*Y - s*Z
        d13 = t*X*Z + s*Y
        d21 = t*X*Y + s*Z
        d22 = t*Y**2 + c
        d23 = t*Y*Z - s*X
        d31 = t*X*Z - s*Y
        d32 = t*Y*Z + s*X
        d33 = t*Z**2 + c
        
        #            |p.x|
        # Matrix 'M'*|p.y|
        #            |p.z|
        rpoints = []
        for i in range(len(points)):
            nx = d11*points[i].x + d12*points[i].y + d13*points[i].z
            ny = d21*points[i].x + d22*points[i].y + d23*points[i].z
            nz = d31*points[i].x + d32*points[i].y + d33*points[i].z
            rpoints.append(Point(nx,ny,nz)+self.P)
        return rpoints
            
    def transform(self, T):
        """ Line t = T * self,  
        
            T -- transformation matrix (expected to be a 4x4 matrix)
            The Point self.P is transformed normally, the 
            directional vector self.vec is rotated and finally normalized 
            but NOT moved            
        """
        self.P = self.P.transform(T)
        self.vect = self.vect.transformNoTranslation(T).normalized()
        return Point(t[0,0],t[1,0],t[2,0])            

class Plane:
    def __init__(self, P=Point(0,0,0), P2=None, P3=None, D=0):
        """ initializing constructor
        
            plane can be defined from: 
                -- three points in space: list P with at least 3 points (first three taken) or three points P, P2, P3
                -- list P of ints/reald numbers (= normal vector coords) and distance D
                -- P as normal vector with D as distance from origin
        """
        if type(P) == list and len(P) > 2: # P == list
            print 'plane from list'
            if isinstance(P[0], Point):                #list of points --> fromPoints
                self.fromPoints(P[0], P[1], P[2])
            elif type(other) == float or type(other) == int: #list of numbers --> fromND
                self.fromND(Point(P[0], P[1], P[2]), D)
            else:
                raise TypeError, 'Invalid parameter list to Plane constructor'
        elif isinstance(P,Point) and P2 == None:     #normalvector as Point and D
            #print 'plane from nd'
            self.fromND(P, D)
        elif isinstance(P, Point) and isinstance(P2, Point) and isinstance(P3, Point):
            #print 'plane points'
            self.fromPoints(P, P2, P3) #three points, D irrelevant
        elif isinstance(P, Point) and isinstance(P2, Point):
            #print 'plane pn'
            self.fromPointNorm(P, P2)
        elif isinstance(P, Point) and isinstance(P2, Line):
            #print 'plane pl'
            self.fromPointLine(P, P2)
        else:
            raise TypeError, 'unknown initializers'
    
    def __repr__(self):
        """ string representing plane in readable version
        """
        return "Plane(" + repr(self.N) + ", " + repr(self.D) + ")"

    def fromPoints(self, p1, p2, p3):
        """ describing parameters = define plane by three points p1, p2, p3

            p1, p2, p3 -- each one points in 3-space representing a point from plane
            collinear points will raise an error as the plane would not be defined disambigously
        """
        if isinstance(p1, Point) and isinstance(p2, Point) and isinstance(p3, Point):
            N = (p2-p1).cross(p3-p1)
            N = N.normalized() #will throw an error if points are collinear
            D = (-N).dot(p1)
            self.N = N
            self.D = D
        else:
            raise TypeError, 'Plane.fromPoints expects three points as params'
    
    def fromND(self, Norm, Dist = 0):
        """ load plane from normal vector (will be normalized) and distance to origin
        """
        if not isinstance(Norm, Point):
            raise TypeError, 'Plane.fromND expects normal vector as of type Point'
        self.N = Norm.normalized()
        self.D = Dist
    
    def fromPointNorm(self, P, Norm):
        """ define plane by a contained point and the normal direction
        """
        if Norm.abs() == 0:
            raise ValueError, 'Plane normal must not be a null-vector'
        self.N = Norm.normalized()
        self.D = (-self.N).dot(P)
        
    def fromPointLine(self, P, L):
        """ define plane by a contained point and the normal direction
        """
        vect2 = P - L.P        #the second directional vector is from point to line.pos
        norm = vect2.cross(L.vec)
        
        self.fromPointNorm(P, norm)
    
    def dist(self, other):
        """ float dist = distance from self(plane) to other (point)
            
        """
        if isinstance(other, Point):
            #return float (self.N.dot(other - self.D*self.N)/self.N.abs())
            return float (self.N.dot(other - self.D*self.N))    #n is already normalized, so self.N.abs === 1.0
        else:
            raise TypeError ,'can only calculate distance from Plane to Point'
            return None

    def transform(self, T, pivot = Point(0,0,0)):
        """ transform plane by some transformation matrix
            
            the normalvector will be kept normalized/renormalized under all 
            circumstances (except invalid T)
            
            the pivot point (e.g. for fixed-point for rotation) can be specified and
            defaults to the origin of the coordinate system
        """
        if not isinstance (pivot, Point):
            raise TypeError, 'Pivot point must be a point'
        
        pointOnSelf = self.N * self.D
        origin = pointOnSelf - pivot
        origin = origin.transformed(T)        
        pointOnSelf = origin + pivot
        
        norm = self.N.transformNoTranslation(T).normalized()

        self.fromPointNorm(pointOnSelf, norm)        

    def planeintersect(self, other):
        """ returns line of intersection of this plane and another
            None is returned if planes are parallel
            20110207 NEW VERSION: M. Krauss
        """
        N1 = self.N
        N2 = other.N
        D1 = self.D
        D2 = other.D
        if (N1.cross(N2)).abs() == 0:
            # Planes are parallel
            return None
        else:
            v = N1.cross(N2)
            b = np.array([[D1],[D2]])
            p = Point(0,0,0)
            try:
                # intersection with the plane x=0
                A = np.array([[N1.y , N1.z],[N2.y , N2.z]])
                x = la.solve(A,b)
                p = Point(0,x[0],x[1])
                return Line(p, v)
            except:
                try:
                    # intersection with the plane y=0
                    A = np.array([[N1.x , N1.z],[N2.x , N2.z]])
                    x = la.solve(A,b)
                    p = Point(x[0],0,x[1])
                    return Line(p, v)
                except:
                    # intersection with the plane z=0
                    A = np.array([[N1.x , N1.y],[N2.x , N2.y]])
                    x = la.solve(A,b)
                    p = Point(x[0],x[1],0)
                    return Line(p, v)

    def lineintersect(self,other):
        """    Point p = intersection of self (Plane) and other (Line)
        """
        N = self.N
        D = self.D
        P = other.P
        vec = other.vec
        u1 = float(N.dot(D*N - P))
        u2 = float(N.dot(vec))
        #print u1,u2
        u = u1 / u2
        return P + u * vec

    def intersect(self, other):
        """ pseudo-overloaded intersection fn for planes and lines, will call appropriate members planeintersect/lineintersect
        """
        if isinstance(other,Plane):
            return self.planeintersect(other)
        elif isinstance(other,Line):
            return self.lineintersect(other)
        else:
            return None
    
    def projection(self, other):
        """ Point X = projection of Point other to plane = closest point to other on self = intersection of line through other and perpendicular to self with self
        """
        if isinstance(other,Point):
            return self.lineintersect(Line(other,vec=self.N))
        else:
            raise TypeError, 'can only project Points'
            return None
    
    def getpoints(self, x_range, y_range):
        """    get some points lying on plane
        """ 
        a = self.N[0]
        b = self.N[1]
        c = self.N[2]
        d = self.D
        xs = np.arange(x_range[0],x_range[1],(x_range[1]-x_range[0])/2)
        ys = np.arange(y_range[0],y_range[1],(y_range[1]-y_range[0])/2)
        xP = np.zeros((len(xs),len(ys)), dtype=float)
        yP = np.zeros((len(xs),len(ys)), dtype=float)
        zP = np.zeros((len(xs),len(ys)), dtype=float)
        for x in range(len(xs)):
            for y in range(len(ys)):
                xP[x,y] = xs[x]
                yP[x,y] = ys[y]
                zP[x,y] = (-a*xs[x]-b*ys[y]-d)/c
        return xP,yP,zP
        
    def getcoordinatesystem(self):
        """ return axis unit vectors for plane coordinate system where z1 is normal vector
            
        """
        ZeroPoint = float(self.D) * self.N
        z1 = self.N
        x1Point = self.projection(Point(1,0,0))
        if x1Point == Point(0,0,0):
            x1Point = self.projection(Point(0,1,0))
        x1 = x1Point - ZeroPoint
        x1 = x1 / x1.abs()
        y1 = z1.cross(x1)
        return [ZeroPoint,x1,y1,z1]
        
if __name__ == '__main__':
    p1 = Point(1,1,0)
    A = np.array([[2., 0, 0, 0], [0,1, 0, 0], [0, 0, 6, -5], [0,0,0,1]])
    p2 = p1.transform(A)
    
   

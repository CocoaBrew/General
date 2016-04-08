#!/usr/bin/python

# Dan Coleman
# Writes schedule from csv data

import sys
import csv
import random
from cgi import FieldStorage

""" Throughout, hours refer to shifts, not full sixty-minute periods. """

class Tutor:
    ### Constructor for Tutor ###
    def __init__ (self, csvFilename):
        self.name = self.parseName(csvFilename)
        self.timeMatrix = self.extractTimes(csvFilename)
        self.assigned = []
        self.ed = self.parseEdLevel(csvFilename)
        self.shiftsCleared = self.parseHrs(csvFilename) * 2
    
    # Parse full name from filename
    def parseName(self, filename):
        nameParts = filename.split('.')
        noEd = nameParts[0][:-2]
        nameList = []
        for i in noEd:
            if not i.isdigit():
                nameList.append(i)
        name = ''.join(nameList)
        return nane
        
    # Parse education level from filename
    def parseEdLevel(self, filename):
        nameParts = filename.split('.')
        return nameParts[0][-2:]
        
    # Parse cleared hours from filename
    def parseHrs(self, filename):
        nameParts = filename.split('.')
        noEd = nameParts[0][:-2]
        numHrsList = []
        for i in noEd:
            if i.isdigit():
                numHrsList.append(i)
        numHrs = int(''.join(numHrsList))
        return numHrs
                
    # Takes time values from csv file
    def extractTimes(self, filename):
        times = {}
        with open(filename, 'rb') as csvTimes:
            readTime = csv.reader(csvTimes)
            for row in readTime:
                times[row[0]] = list(row[1:])
        csvTimes.close()
        return times
    
    # Returns value of given time in timeMatrix
    def status(self, time, day):
        dayCode = self.convertDay(day)
        statusCode = self.timeMatrix[time][dayCode]
        return statusCode
        
    # Converts from first letter of day 
    # to an indexable number
    def convertDay(self, day):
        code = 100
        if (day == 's'):
            code = 0
        elif (day == 'm'):
            code = 1
        elif (day == 't'):
            code = 2
        elif (day == 'w'):
            code = 3
        elif (day == 'r'):
            code = 4
        elif (day == 'f'):
            code = 5
        return code
        
    # Attempts to add a given hour
    # to the schedule of this tutor
    def assign(self, hr, blockList):
        newBlocks = []
        if len(self.assigned) != self.shiftsCleared:
            day = self.convertCol(hr)
            time = hr[1:]
            availCode = self.status(time, day)
            if (availCode == 1) or (availCode == 0): ##entire sect makeAssign??
                nt = self.nextTime(hr)
                pt = self.prevTime(hr)
                if (nt not in blockList) and (nt != ''):
                    self.assigned.append(nt)
                    newBlocks.append(nt)
                    self.assigned.append(hr)
                    newBlocks.append(hr)
                elif (pt not in blockList) and (pt != ''):
                    self.assigned.append(pt)
                    newBlocks.append(pt)
                    self.assigned.append(hr)
                    newBlocks.append(hr)
        return newBlocks

    # Gets indexable value for the day
    def convertCol(self, hr):
        day = hr[0]
        return self.convertDay(day)
        
    # Gets time for immediately preceding hr
    def nextTime(self, hr):
        newTime = ''
        day = hr[0]
        hour = hr[1:3]
        if hr[-2:] == '30':
            newMin = ':00'
            newHour = str(int(hour) + 1)
            if len(newHour) != 2:
                newHour = '0' + newHour
            newTime = day + newHour + newMin
        elif hr[-2:] == '00':
            newMin = ':30'
            newTime = day + hour + newMin
        return newTime
        
    # Gets time for immediately following hr
    def prevTime(self, hr):
        newTime = ''
        day = hr[0]
        hour = hr[1:3]
        if hr[-2:] == '30':
            newMin = ':00'
            newTime = day + hour + newMin
        elif hr[-2:] == '00':
            newMin = ':30'
            newHour = str(int(hour) - 1)
            if len(newHour) != 2:
                newHour = '0' + newHour
            newTime = day + newHour + newMin
        return newTime
    
    
class Schedule:
    ### Constructor for Schedule ###
    def __init__(self, course):
        self.course = course
        self.tutors = self.getAllTutors()
        self.reqHrs = self.hrsForTutoring() #####
        self.blockHrs = []
        
    # Instantiate all tutors
    def getAllTutors(self):
        tList = []
        courseFile = "CSVs/" + self.course + "/" + self.course + "tutors.csv"
        with open (courseFile, 'rb') as courseInfo:
            infoRead = csv.reader(courseInfo)
            for info in infoRead:
                tList.append(Tutor(info))
        courseFile.close()
        return tList
        
    # Get the desired hours from csv file
    # for tutoring to be held
    def hrsForTutoring(self):
        hList = []
        courseFile = "CSVs/" + self.course + "/" + self.course + ".csv"
        with open (courseFile, 'rb') as hoursInfo:
            infoRead = csv.reader(hoursInfo)
            dayCount = 0
            for info in infoRead:
                day = ''
                if (dayCount == 0):
                    day = 's'
                elif (dayCount == 1):
                    day = 'm'
                elif (dayCount == 2):
                    day = 't'
                elif (dayCount == 3):
                    day = 'w'
                elif (dayCount == 4):
                    day = 'r'
                elif (dayCount == 5):
                    day = 'f'
                for i in range(0, len(info)):
                    hr = day + info[i]
                    hList.append(hr)
                dayCount += 1
        courseFile.close()
        return hList
        

    # Create the tutoring schedule
    def makeSchedule(self):
        while not self.schedDone(.9):
            self.blockHrs = []
            for hr in self.reqHrs:
                if (self.blockHrs.count(hr) <= 2):  ##format reqHrs -> s09:30
                    for tutor in self.tutors:
                        if (self.blockHrs.count(hr) <= 2):
                            self.blockHrs.extend(tutor.assign(hr, 
                                self.blockHrs))
            success = True
            for t in self.tutors:
                if t.shiftsCleared != len(t.assigned):
                    success = False
            if not success:
                self.blockHrs = []
                for t in self.tutors:
                    t.assigned = []
                # mix for improved result next time
                random.shuffle(self.reqHrs)
                random.shuffle(self.tutors)
                    
        for t in self.tutors:
            for hr in t.assigned:
                schedHr = self.reqHrs.index(hr)
                if (isinstance(self.reqHrs[schedHr], str) or 
                    isinstance(self.reqHrs[schedHr], unicode)):
                    self.reqHrs[schedHr] = list(self.reqHrs[schedHr],[])
                self.reqHrs[schedHr][1].append(t.name)
                
        self.formatOutput()
    
    # Verify whether schedule meets margin of error
    # for what percentage of shifts are filled. 
    # Percentage = alpha * 100
    def schedDone(self, alpha):
        done = True
        unfilled = []
        for hr in self.reqHrs:
            if self.blockHrs.count(hr) == 0:
                unfilled.append(hr)
        #(len(self.reqHrs) - len(unfilled)) / len(self.reqHrs)
        fullRatio =  1 - (float(len(unfilled)) / len(self.reqHrs))
        if (fullRatio < alpha):
            done = False
        return done
    
    # Writes created schedule to file
    def formatOutput(self):
        for hr in self.reqHrs:
            print(hr[0] + " -> " + hr[1]) 
        
            
def main():
    """coursename = ''
    if (len(sys.argv) == 2):     # retrieve
        coursename = sys.argv[1] # name"""
    """postVar = FieldStorage()
    print(postVar)
    coursename = str(postVar['course'].value)"""
    coursename = "HowardMath"
    sched = Schedule(coursename)
    sched.makeSchedule()
    
    
def htmlTop():
    print ("""
    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8"/>
        <meta name="author" content="Dan Coleman"/>
        <link rel="stylesheet" href="tutor.css" />
        <title>Tutor Manager</title>
      </head>

      <body>
        <h1>Course Schedule</h1>
    
    """)
    
def htmlEnd():
    print ("""
      </body>
    </html>
    """)
    
    
main()
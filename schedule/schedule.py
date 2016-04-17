#!/usr/bin/python

# Dan Coleman
# Writes schedule from csv data

import sys
import csv
import random

""" Throughout, hours refer to shifts, not full sixty-minute periods. """

class Tutor:
    ### Constructor for Tutor ###
    def __init__ (self, csvFilename, course):
        self.course = course
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
        return name
        
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
        filepath = "CSVs/" + self.course + "/" + filename
        with open(filepath, 'rb') as csvTimes:
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
    def assign(self, hr, blockList, reqList):
        newBlocks = []
        if len(self.assigned) < self.shiftsCleared:
            availCode = int(self.status(hr[1:], hr[0]))
            if (availCode == 1) or (availCode == 0): ##entire sect makeAssign??
                nt = self.nextTime(hr)
                pt = self.prevTime(hr)
                if (nt not in blockList) and (nt in reqList) and (nt != ''):
                    self.assigned.append(nt)
                    newBlocks.append(nt)
                    self.assigned.append(hr)
                    newBlocks.append(hr)
                elif (pt not in blockList) and (pt in reqList) and (pt != ''):
                    self.assigned.append(pt)
                    newBlocks.append(pt)
                    self.assigned.append(hr)
                    newBlocks.append(hr)
        print ("mark assign")  #####
        return newBlocks
        
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
                tList.append(Tutor(info[0], self.course))
        courseInfo.close()
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
        hoursInfo.close()
        return hList
        

    # Create the tutoring schedule
    def makeSchedule(self):
        while not self.schedDone(.3):
            self.blockHrs = []  # hrs that are already fully assigned
            for hr in self.reqHrs:
                if (self.blockHrs.count(hr) <= 2):  ##format reqHrs -> s09:30
                    for tutor in self.tutors:
                        if (self.blockHrs.count(hr) <= 2):
                            self.blockHrs.extend(tutor.assign(hr, 
                                self.blockHrs, self.reqHrs))
            success = True
            '''for t in self.tutors:
                if t.shiftsCleared != len(t.assigned):
                    success = False'''
            if not success:
                self.blockHrs = []
                for t in self.tutors:
                    t.assigned = []
                # mix for improved result next time
                random.shuffle(self.reqHrs)
                random.shuffle(self.tutors)
                print ("mark fail")  #######
                    
        for t in self.tutors:
            for hr in t.assigned:
                schedLoc = -1 #
                for entry in self.reqHrs: #
                    if (entry[0] == hr): #
                        schedLoc = self.reqHrs.index(entry) #
                    #schedLoc = self.reqHrs.index(hr)
                if (isinstance(self.reqHrs[schedLoc], str) or 
                    isinstance(self.reqHrs[schedLoc], unicode) and
                    schedLoc >= 0):
                    self.reqHrs[schedLoc] = [self.reqHrs[schedLoc],[]]
                self.reqHrs[schedLoc][1].append(t.name)
                
        print (self.reqHrs)
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
        print ("mark done check")  ######
        return done
    
    # Writes created schedule to file
    def formatOutput(self):  ######
        shrs = []
        mhrs = []
        thrs = []
        whrs = []
        rhrs = []
        fhrs = []
        for hr in self.reqHrs:
            if (hr[0][0] == 's'):
                shrs.append(hr[0])
            elif (hr[0][0] == 'm'):
                mhrs.append(hr[0])
            elif (hr[0][0] == 't'):
                thrs.append(hr[0])
            elif (hr[0][0] == 'w'):
                whrs.append(hr[0])
            elif (hr[0][0] == 'r'):
                rhrs.append(hr[0])
            elif (hr[0][0] == 'f'):
                fhrs.append(hr[0])
        print (shrs)
        print (mhrs)
        print (thrs)
        print (whrs)
        print (rhrs)
        print (fhrs)
        filename = "schedules/" + self.course + ".html"
        fout = open(filename, 'w')
        htmlOut = '''
        <!DOCTYPE html>
        <html>
          <head>
            <meta charset="utf-8"/>
            <meta name="author" content="Dan Coleman"/>
            <link rel="stylesheet" href="../tutor.css" />
            <title>Course Schedule</title>
          </head>

          <body>
            <h1>Course Schedule</h1>
    
            <h2>%s</h2>''' % (self.course)
        htmlOut = htmlOut + '''
            
            <table class="sched">
              <tr>
                '''
        """for hr in shrs:  ########
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t\t" % (hr[0], hr[1]))
        for hr in mhrs:
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t\t" % (hr[0], hr[1]))
        for hr in thrs:
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t\t" % (hr[0], hr[1]))
        for hr in whrs:
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t\t" % (hr[0], hr[1]))
        for hr in rhrs:
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t\t" % (hr[0], hr[1]))
        for hr in fhrs:
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t" % (hr[0], hr[1]))"""
        for hr in shrs:
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t\t" % (hr[0], hr[0][0]))
        htmlOut = htmlOut + "</tr>\n\t<tr>"
        for hr in mhrs:
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t\t" % (hr[0], hr[0][0]))
        htmlOut = htmlOut + "</tr>\n\t<tr>"
        for hr in thrs:
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t\t" % (hr[0], hr[0][0]))
        htmlOut = htmlOut + "</tr>\n\t<tr>"
        for hr in whrs:
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t\t" % (hr[0], hr[0][0]))
        htmlOut = htmlOut + "</tr>\n\t<tr>"
        for hr in rhrs:
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t\t" % (hr[0], hr[0][0]))
        htmlOut = htmlOut + "</tr>\n\t<tr>"
        for hr in fhrs:
            htmlOut = htmlOut + ("<td>%s -> %s</td>\n\t" % (hr[0], hr[0][0]))
        htmlOut = htmlOut + '''
              </tr>
            </table>
          </body>
        </html>
            '''
        fout.write(htmlOut)
        fout.close() 
        
        
def main():
    coursename = ''
    if (len(sys.argv) > 1):
        coursename = sys.argv[1].strip()
    sched = Schedule(coursename)
    sched.makeSchedule()
    
    """fname = "DaveBradley4ug.csv"
    atut = Tutor(fname, sched.course)
    times = atut.extractTimes(fname)
    blkhrs = []
    nblkhrs = atut.assign("m10:30", [])
    blkhrs.extend(nblkhrs)
    print (atut.assigned)
    """
    
    print ("successful")

    
main()
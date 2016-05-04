#!/usr/bin/python

# Dan Coleman
# Writes schedule from csv data
# For tutor-scheduling system
#
# Spring 2016 Senior Capstone

import sys
import csv
import random

""" Throughout, hours refer to shifts, not full sixty-minute periods. """

##### TUTOR-SCHEDULING SYSTEM #####

# Creates object to which hours can be assigned.
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
            if (not i.isdigit()):
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
            if (i.isdigit()):
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
    def assign(self, hr, blockList, reqList, tutorsPerShift, priority):
        newBlocks = []
        if (blockList.count(hr) < tutorsPerShift):
            if (len(self.assigned) < self.shiftsCleared):
                availCode = int(self.status(hr[1:], hr[0]))
                if (availCode == int(priority)):
                    newBlocks.append(hr)
                    self.assigned.append(hr)

        print ("mark assign")  #####
        return newBlocks
    

# Assembles information and creates an associated schedule.
class Schedule:
    ### Constructor for Schedule ###
    def __init__(self, course, tps):
        self.course = course
        self.tutors = self.getAllTutors()
        self.reqHrs = self.hrsForTutoring()
        self.blockHrs = []
        self.tutorsPerShift = tps
        self.percentTutorsFilled = 0
        
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
        PRIORITY_BOUND = 100  # limits high priority runs
        END_CONSTANT = 10  # constant to determine escape condition
        priority = 1
        countRuns = 0
        # leave loop if run goes past escape condition
        while ((not self.schedDone()) and 
            countRuns < PRIORITY_BOUND * END_CONSTANT):
            # mix up hrs content
            if (countRuns % 10 == 0):
                random.shuffle(self.reqHrs)
            # change priority based on bound condition
            if (countRuns >= PRIORITY_BOUND):
                priority = 0
            # clear hrs lists
            self.emptyHrs()
            for hr in self.reqHrs:
                for tutor in self.tutors:
                    if (tutor.shiftsCleared > len(tutor.assigned)):
                        self.blockHrs.extend(tutor.assign(hr, 
                            self.blockHrs, self.reqHrs, self.tutorsPerShift,
                            priority))
            DEPTH = 1
            self.checkTutorsFull(DEPTH)
            countRuns += 1

        self.percentTutorsFilled = self.percentTutFull()
        self.reqHrs = self.putNamesToHrs()
        self.formatOutput()
    
    # Verify whether schedule meets margin of error
    # for what percentage of shifts are filled. 
    # ACCEPTABLE => schedule with enough timeslots taken
    def schedDone(self):
        done = True
        ACCEPTABLE = .5
        unfilled = []
        for hr in self.reqHrs:
            if (self.blockHrs.count(hr) == 0):
                unfilled.append(hr)
        ## ((# self.reqHrs) - (# unfilled)) / (# self.reqHrs)
        fullRatio =  1 - (float(len(unfilled)) / len(self.reqHrs))
        if (fullRatio < ACCEPTABLE):
            done = False
        for t in self.tutors:
            if (len(t.assigned) < t.shiftsCleared):
                done = False

        return done

    # Calculates ratio of tutors with fewer than their cleared hours
    # to tutors who have all of their cleared hours
    def percentTutFull(self):
        numComplete = 0
        for t in self.tutors:
            if (len(t.assigned) == t.shiftsCleared):
                numComplete += 1
        return (float(numComplete) / len(self.tutors) * 100)

    # Empties blocked hours and 
    # assigned hours for each tutor
    def emptyHrs(self):
        # assigned hrs in general
        self.blockHrs = []
        # assigned hrs for each tutor
        for t in self.tutors:
            t.assigned = []

    # Verifies whether schedule has assigned each tutor 
    # to the number of hours he is cleared to work
    def checkTutorsFull(self, depth):
        success = True
        incomplete = []
        for t in self.tutors:
            if (t.shiftsCleared > len(t.assigned)):
                incomplete.append(t) 
                success = False
        if (not success and depth > 0):
            for tut in incomplete:
                random.shuffle(self.reqHrs)
                for hr in self.reqHrs:
                    if (hr not in tut.assigned and 
                        self.blockHrs.count(hr) < self.tutorsPerShift
                        and tut.shiftsCleared > len(tut.assigned)):
                        self.blockHrs.extend(tut.assign(hr, self.blockHrs, 
                            self.reqHrs, self.tutorsPerShift, 0))
            return self.checkTutorsFull(depth - 1)
        else:
            return True
    
    # Tags the assigned hours with 
    # the assigned tutors names
    def putNamesToHrs(self):
        markedHrs = []
        for entry in self.reqHrs:
            for t in self.tutors:
                if (t.assigned.count(entry) != 0):
                    marked = False
                    for hr in markedHrs:
                        if (hr[0] == entry and marked == False):
                            marked = True
                            if (t.name not in hr[1]):
                                hr[1].append(t.name)
                    if (marked == False):
                        markedHrs.append([entry, []])
                        markedHrs[-1][1].append(t.name)

        return markedHrs

    # Writes created schedule to file
    def formatOutput(self):
        # Schedule will always present these hours
        SCHED_HRS = ['09:00', '09:30', '10:00', '10:30', '11:00', '11:30', 
            '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', 
            '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', 
            '19:00', '19:30', '20:00', '20:30']
        # sort hours by day
        shrs = []
        mhrs = []
        thrs = []
        whrs = []
        rhrs = []
        fhrs = []
        for hr in self.reqHrs:
            if (hr[0][0] == 's'):
                shrs.append(hr)
            elif (hr[0][0] == 'm'):
                mhrs.append(hr)
            elif (hr[0][0] == 't'):
                thrs.append(hr)
            elif (hr[0][0] == 'w'):
                whrs.append(hr)
            elif (hr[0][0] == 'r'):
                rhrs.append(hr)
            elif (hr[0][0] == 'f'):
                fhrs.append(hr)
        print (shrs)#######
        print (mhrs)#####
        print (thrs)#######
        print (whrs)#######
        print (rhrs)#######
        print (fhrs)#####
        filename = "sched_files/" + self.course + ".html"
        fout = open(filename, 'w')

        # format header info
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
    
            <h2 id="coursetitle">%s</h2>''' % (self.course)
        htmlOut = htmlOut + '''
            
            <table class="sched">
              <colgroup>
                <col span="1">
              </colgroup>
              <tr class="tableheaders">
                <th>Times</th>
                <th>Sunday</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednesday</th>
                <th>Thursday</th>
                <th>Friday</th>
              </tr>
              '''
        for time in SCHED_HRS:
            htmlOut = htmlOut + '''
              <tr>
                ''' + ("<td>%s</td>\n\t\t" % (time))
            filled = False
            for hr in shrs:  ########
                if (hr[0][1:] == time):
                    htmlOut = htmlOut + ("<td>%s</td>\n\t\t" % 
                        ((", ").join(hr[1])))
                    filled = True
            if (not filled):
                htmlOut = htmlOut + "<td> </td>\n\t\t"

            filled = False
            for hr in mhrs:  ########
                if (hr[0][1:] == time):
                    htmlOut = htmlOut + ("<td>%s</td>\n\t\t" % 
                        ((", ").join(hr[1])))
                    filled = True
            if (not filled):
                htmlOut = htmlOut + "<td> </td>\n\t\t"

            filled = False
            for hr in thrs:  ########
                if (hr[0][1:] == time):
                    htmlOut = htmlOut + ("<td>%s</td>\n\t\t" % 
                        ((", ").join(hr[1])))
                    filled = True
            if (not filled):
                htmlOut = htmlOut + "<td> </td>\n\t\t"

            filled = False
            for hr in whrs:  ########
                if (hr[0][1:] == time):
                    htmlOut = htmlOut + ("<td>%s</td>\n\t\t" % 
                        ((", ").join(hr[1])))
                    filled = True
            if (not filled):
                htmlOut = htmlOut + "<td> </td>\n\t\t"

            filled = False
            for hr in rhrs:  ########
                if (hr[0][1:] == time):
                    htmlOut = htmlOut + ("<td>%s</td>\n\t\t" % 
                        ((", ").join(hr[1])))
                    filled = True
            if (not filled):
                htmlOut = htmlOut + "<td> </td>\n\t\t"

            filled = False
            for hr in fhrs:  ########
                if (hr[0][1:] == time):
                    htmlOut = htmlOut + ("<td>%s</td>\n\t\t" % 
                        ((", ").join(hr[1])))
                    filled = True
            if (not filled):
                htmlOut = htmlOut + "<td> </td>\n\t\t"
            htmlOut = htmlOut + '''
              </tr>'''
        htmlOut = htmlOut + '''
            </table>
            <p>
              '''
        htmlOut = htmlOut + ("Percent of tutors with filled schedules: %s." 
            % ("%.1f" % (self.percentTutorsFilled) ))
        htmlOut = htmlOut + '''
            </p>
          </body>
        </html>
            '''
        fout.write(htmlOut)
        fout.close() 


def main():
    # default number of tutors during a shift
    tps = 2

    # get course and num of tutors from execution args
    coursename = ''
    if (len(sys.argv) > 1):
        coursename = sys.argv[1].strip()
        tps = int(sys.argv[2].strip())

    # Make schedule for 'coursename'
    sched = Schedule(coursename, tps)
    sched.makeSchedule()

    print ("successful")

    
main()

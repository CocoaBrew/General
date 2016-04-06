#!/usr/bin/python

# Dan Coleman
# Writes schedule from csv data

import sys
import csv

if __name__ == "__main__":
    # open tutordata files
    
    print(htmlTop)
    print(htmlContent)
    print(htmlEnd)
    
"""
End thoughts.
Begin file.
"""
class Tutor:
    def __init__ (self, csvFilename):
        self.name = self.parseName(csvFilename)
        self.timeMatrix = self.extractTimes(csvFilename)
    
    def parseName(self, filename):
        nameParts = filename.split('.')
        name = nameParts[0]
        return name
        
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
        
    def convertDay(self, day):
        code = 100
        if (day == 'S'):
            code = 0
        elif (day == 'M'):
            code = 1
        elif (day == 'T'):
            code = 2
        elif (day == 'W'):
            code = 3
        elif (day == 'R'):
            code = 4
        elif (day == 'F'):
            code = 5
        return code
    

def main():
    coursename = ''
    if (len(sys.argv) == 2):
        coursename = sys.argv[1]
    
    
    
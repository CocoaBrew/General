# Dan Coleman
# Writes schedule from csv data

import csv

def htmlTop():
    top = """<!DOCTYPE html>
              <html>
                <head>
                  <meta charset="utf-8"/>
                  <meta name="author" content="Dan Coleman"/>
                  <link rel="stylesheet" href="tutor.css" />
                  <title>Schedule</title>
                </head>
                <body>
                  """
    return top

def htmlEnd():
    end = """   </body>
              </html>"""
    return end

if __name__ == "__main__":
    # open tutordata files
    
    print(htmlTop)
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
    
    
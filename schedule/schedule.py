# Dan Coleman
# Writes schedule from csv data

import cgi

def htmlTop():
    top = """<!DOCTYPE html>
              <html>
                <head>
                  <meta charset="utf-8"/>
                  <meta name="author" content="Dan Coleman"/>
                  <link rel="stylesheet" href="tutor.css" />
                  <title>Tutor Manager</title>
                </head>
                <body>
                  """
    return top


def htmlEnd():
    end = """    </body>
              </html>"""
    return end


if __name__ == "__main__":
    # open tutordata files
    
    
    print(htmlTop)
    print(htmlEnd)
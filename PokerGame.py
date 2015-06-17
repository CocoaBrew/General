# This program will let user play a simple Poker game, 
# then report user's score and thank him for playing at end
# Dan Coleman
# 12/04/13
# Program 6

# provides information for Dice class

import random

class Dice:
    def __init__(self):
        # create empty dice, then roll to set them
        self.dice = [0] * 5
        self.rollAll()

    def roll(self, positions):
        # update the values for all positions in the positions list
        for pos in positions:
            self.dice[pos] = random.randint(1,6)

    def rollAll(self):
        # update values for all five positions
        self.roll(range(5))

    def values(self):
        # return a copy of the list of dice values
        return self.dice[:]

    def score(self):
        # Return the poker description and score for the current set
        # of dice values.  This is what you need to implement.
        
        # create dictionary for tallying occurrences
        d = {}
        for i in self.values():
        	if i in d:
        		d[i] += 1
        	else:
        		d[i] = 1
        
        sD = sorted(d)  #sort dictionary keys
        pD = list(d.keys())  #list of dictionary keys
        
        # Overall, use length of dict and whether values equal each other
        # to determine what response to return
        
        # "Five of a Kind", 30
        if len(pD) == 1:
        	return "Five of a Kind", 30
        
        # "Four of a Kind", 15
        elif len(pD) == 2 and (d[pD[0]] == 4 or d[pD[1]] == 4):
        	return "Four of a Kind", 15
        
        # "Full House", 12 (3 and 2 of two different kinds)
        elif len(pD) == 2:
        	return "Full House", 12
        
        # "Three of a Kind", 8
        elif (len(pD) == 2 or len(pD) == 3) and (d[pD[0]] == 3 or d[pD[1]] == 3 or d[pD[2]] == 3):
        	return "Three of a Kind", 8
        
        # "Straight", 20  (one of each from 1-5 or 2-6)
        elif len(pD) == 5 and ((sD[0]) == (sD[1]-1) == (sD[2]-2) == (sD[3]-3) == (sD[4]-4)):
        	return "Straight", 20
        
        # "Two Pairs", 5
        elif len(pD) == 3:
        	return "Two Pairs", 5
        
        # "Garbage", 0
        else:
        	return "Garbage", 0
        
# provides information for Poker class

class Poker:
    # initiates money and instance of dice class
    def __init__(self):
        self.d = Dice()
        self.money = 100
		
    def run(self):
        while (self.money >= 10):
            play = input("Would you like to play/continue (Y/N)? ")

            if play == 'Y':
                self.playRound()
                print(self.d.score())  #print score
                self.money += self.d.score()[1]  #add money amt from score
                print("Money is at", self.money, ". ")
            else:
                break
				
        print("Your money's final balance is", self.money, ". ")
        print("Thank you for playing. ")
		
    # plays a round of the Poker game
    def playRound(self):
        self.money -= 10  #mark money down for a round of play
        self.d.rollAll()

        for i in range(2):  #give user two optional rerolls
            print(self.d.values())

            again = input("Would you like to roll again (Y/N)? ")

            # rolls again based on entered indices
            if again == 'Y':
                where = input("Enter indices of dice for re-roll: ")
                whereList = []

                for i in where:  #eliminate unnecessary characters from input string
                    if (i != '[') and (i != ']') and (i != ',') and (i != ' '):
                        whereList.append(int(i))
		#print(where)
                self.d.roll(whereList)
            else:
                break
	
	
# actually run the created class        
def main():
	s = Poker()  #instantiate the object
	s.run()
	
main()

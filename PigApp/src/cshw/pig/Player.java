package cshw.pig;

/**
 * Provides a class to store information
 * about a player's score.
 * @author Dan Coleman
 * @version 12/5/14
 */
public class Player 
{
	private int score;
	
	/**
	 * Constructor for Player object.
	 */
	public Player()
	{
		score = 0;
	}
	
	/**
	 * Returns this player's score.
	 * @return Player's score.
	 */
	public int getScore()
	{
		return score;
	}
	
	/**
	 * Adds a new point amount to this player's score.
	 * @param scoreVal Score to be added to existing total.
	 */
	public void setScore(int scoreVal)
	{
		score = score + scoreVal;
	}
}

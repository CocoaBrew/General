
import hailstoneProject.Hailstone;

/**
 * Enables hailstone sequence comparison.
 * @author Dan Coleman
 * @version 9/17/14
 */
public class Comparison 
{
	private int startVal;
	private int endVal;
	private int maxVal;
	private int maxValStartVal;
	private int topLength;
	private int topLengthStartVal;
	
	/**
	 * Constructor for a Comparison object.
	 * @param start Lower bound of sequence range, inclusive.
	 * @param end Upper bound of sequence range, inclusive.
	 */
	public Comparison(int start, int end)
	{
		startVal = start;
		endVal = end;
		maxVal = 0;
		maxValStartVal = 0;
		topLength = 0;
		topLengthStartVal = 0;
	}
	
	/**
	 * Finds the longest length, maximum value, and their respective
	 * sequence starting values in a range of sequences.
	 */
	public void compare()
	{
		for (int i = startVal; i <= endVal; i++)
		{
			Hailstone stone = new Hailstone(i);
			stone.calcSequence();
			if (stone.getLength() > topLength)
			{
				topLength = stone.getLength();
				topLengthStartVal = i;
			}
			if (stone.getLargestValue() > maxVal)
			{
				maxVal = stone.getLargestValue();
				maxValStartVal = i;
			}
		}
	}
	
	/**
	 * Finds the longest length, maximum value, and their respective
	 * sequence starting values in a range of sequences, considering 
	 * only those that start with prime numbers.
	 */
	public void comparePrimes()
	{
		for (int i = startVal; i <= endVal; i++)
		{
			if (isPrime(i))
			{
				Hailstone stone = new Hailstone(i);
				stone.calcSequence();
				if (stone.getLength() > topLength)
				{
					topLength = stone.getLength();
					topLengthStartVal = i;
				}
				if (stone.getLargestValue() > maxVal)
				{
					maxVal = stone.getLargestValue();
					maxValStartVal = i;
				}
			}
		}
	}
	
	/**
	 * Finds the longest length, maximum value, and their respective
	 * sequence starting values in a range of sequences, considering 
	 * only those that start with multiples of 13.
	 */
	public void compareThirteenMultiples()
	{
		for (int i = startVal; i <= endVal; i++)
		{
			if (isThirteenMultiple(i))
			{
				Hailstone stone = new Hailstone(i);
				stone.calcSequence();
				if (stone.getLength() > topLength)
				{
					topLength = stone.getLength();
					topLengthStartVal = i;
				}
				if (stone.getLargestValue() > maxVal)
				{
					maxVal = stone.getLargestValue();
					maxValStartVal = i;
				}
			}
		}
	}
	
	/**
	 * Check whether a given number is prime.
	 * @param entry The number to check.
	 * @return Whether the number is prime.
	 */
	private static boolean isPrime(int entry)
	{
		boolean prime = true;
		for (int i = 2; i < (entry / 2); i++)
		{
			if ((entry % i) == 0)
			{
				prime = false;
			}
		}
		
		return prime;
	}
	
	/**
	 * Check whether a given number is a multiple of thirteen.
	 * @param entry The number to check.
	 * @return Whether the number is a multiple of thirteen.
	 */
	private static boolean isThirteenMultiple(int entry)
	{
		boolean thirteenMultiple = false;
		if ((entry % 13) == 0)
		{
			thirteenMultiple = true;
		}
		
		return thirteenMultiple;
	}
	
	/**
	 * Accessor method for maximum value in range of sequences.
	 * @return Maximum value in sequence range.
	 */
	public int getMaxVal()
	{
		return maxVal;
	}
	
	/**
	 * Accessor method for starting value of sequence 
	 * with maximum value.
	 * @return Starting value of maximum value sequence.
	 */
	public int getMaxValStartVal()
	{
		return maxValStartVal;
	}
	
	/**
	 * Accessor method for length of longest sequence 
	 * in sequence range.
	 * @return Length of longest sequence.
	 */
	public int getTopLength()
	{
		return topLength;
	}
	
	/**
	 * Accessor method for starting value of sequence 
	 * with longest length.
	 * @return Starting value of longest length sequence.
	 */
	public int getTopLengthStartVal()
	{
		return topLengthStartVal;
	}
}

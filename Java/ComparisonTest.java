
/**
 * Uses Comparison class to find largest number, longest length, 
 * and their respective starting values in a given numerical range.
 * @author Dan Coleman
 * @version 9/18/14
 *
 */
public class ComparisonTest 
{
	public static void main(String[] args)
	{
        long startTime = System.nanoTime();
        
		testOne();    //Test 1.
		System.out.println();
		testTwo();    //Test 2. 
		System.out.println();
		testThree();  //Test 3.
		System.out.println();
		testFour();   //Test 4.
        System.out.println();
		
        long stopTime = System.nanoTime();
        System.out.println(stopTime - startTime);
	}
	
	/**
	 * Tests getting the longest length, highest value,
	 * and their respective starting values for a simple range.
	 */
	private static void testOne()
	{
		Comparison trialOne = new Comparison(1, 6);
		trialOne.compare();		//normal compare method
		System.out.print("Longest length should be 9. ");
		System.out.println("Actual max length: " + trialOne.getTopLength());
		System.out.println("Max length start value: " + 
				trialOne.getTopLengthStartVal());
		System.out.print("Highest value should be 16. ");
		System.out.println("Actual max value: " + trialOne.getMaxVal());
		System.out.println("Max value start value: " + 
				trialOne.getMaxValStartVal());
	}
	
	/**
	 * Tests getting the longest length, highest value,
	 * and their respective starting values for the 
	 * full range up to 10000.
	 */
	private static void testTwo()
	{
		Comparison trialTwo = new Comparison(1, 10000);
		trialTwo.compare();		//normal compare method
		System.out.print("Longest length should be 262. ");
		System.out.println("Actual max length: " + trialTwo.getTopLength());
		System.out.println("Max length start value: " + 
				trialTwo.getTopLengthStartVal());
		System.out.print("Highest value should be 27114424. ");
		System.out.println("Actual max value: " + trialTwo.getMaxVal());
		System.out.println("Max value start value: " + 
				trialTwo.getMaxValStartVal());
	}
	
	/**
	 * Tests getting the longest length, highest value,
	 * and their respective starting values for a range that
	 * has a further restriction of only prime starting values.
	 */
	private static void testThree()
	{
		Comparison trialThree = new Comparison(2, 15);
		trialThree.comparePrimes();		//compare method for primes
		System.out.print("Longest length should be 17. ");
		System.out.println("Actual max length: " + trialThree.getTopLength());
		System.out.println("Max length start value: " + 
				trialThree.getTopLengthStartVal());
		System.out.print("Highest value should be 52. ");
		System.out.println("Actual max value: " + trialThree.getMaxVal());
		System.out.println("Max value start value: " + 
				trialThree.getMaxValStartVal());
	}
	
	/**
	 * Tests getting the longest length, highest value,
	 * and their respective starting values for a range that
	 * has a further restriction of only starting values that
	 * are multiples of 13.
	 */
	private static void testFour()
	{
		Comparison trialFour = new Comparison(1, 10000);
		//compare method for multiples of 13.
		trialFour.compareThirteenMultiples();
		System.out.print("Longest length should be 244. ");
		System.out.println("Actual max length: " + trialFour.getTopLength());
		System.out.println("Max length start value: " + 
				trialFour.getTopLengthStartVal());
		System.out.print("Highest value should be 6810136. ");
		System.out.println("Actual max value: " + trialFour.getMaxVal());
		System.out.println("Max value start value: " + 
				trialFour.getMaxValStartVal());
	}
}

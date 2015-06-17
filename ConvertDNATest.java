import static org.junit.Assert.*;

import org.junit.Test;

import java.io.File;
import java.io.FileNotFoundException;
import java.util.Scanner;

/**
 * Test cases for ConvertDNA to verify appropriate
 * results in certain situations.
 * @author Dan Coleman
 *
 */
public class ConvertDNATest
{	
	// Args and filepaths must be manually set
	// for each test that requires either.
	
	// Testing for FileNotFoundExceptions
	@Test (expected = FileNotFoundException.class)
	public void testFileNotFound() throws FileNotFoundException 
	{
		// A non-existent file
		String[] args = {"/Users/archorsie/Desktop/forklift.fasta"};
		ConvertDNA.main(args);
	}
	
	// Testing for IllegalArgumentExceptions
	@Test (expected = IllegalArgumentException.class)
	public void testIllegalArgument() throws FileNotFoundException
	{
		// A file with an invalid input character
		String[] args = {"/Users/archorsie/Desktop/test2.fasta"};
		ConvertDNA.main(args);
	}
	

	@Test // Tests only for correct output name
	public void testOutputFileName() throws FileNotFoundException
	{
		int num1 = 1;	// Values taking the
		int num2 = 50;	// places of similar
		int num3 = 3;	// values in primary class.
		String[][] stuff = new String[num2][num3];
		String inName = "foo.fasta";
		
		for (int i = 0; i < stuff.length; i++)
		{
			stuff[i][1] = "";
		}
		
		assertEquals("foo-aa.fasta", ConvertDNA.toFile(inName, stuff, num1));
	}
	
	
	
	@Test // Test for output line length of 75 or less
	public void testLineLength() throws FileNotFoundException
	{
		// The given "example_input.fasta" file
		String[] args = {"/Users/archorsie/Desktop/test.fasta"};
		// Run program to confirm converted sequence file exists
		ConvertDNA.main(args);
		
		boolean lessThan75 = true;
		
		String inName = "/Users/archorsie/Desktop/test-aa.fasta";
		File inputFile = new File(inName);
		Scanner in = new Scanner(inputFile);
		
		// Loop through the output file to verify correct length
		while (in.hasNextLine() && lessThan75 == true)
		{
			String nextLn = in.nextLine();
			if (nextLn.contains(">"))
			{
				nextLn = "";
			}
			else
			{
				if (nextLn.length() > 75)
				{
					lessThan75 = false;
				}
			}
		}
		
		in.close();
		
		assertEquals(true, lessThan75);
	}
	
}

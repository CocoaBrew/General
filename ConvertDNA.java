import java.io.File;
import java.io.PrintWriter;
import java.io.FileNotFoundException;
import java.util.Scanner;

/**
 * Program takes input file of DNA sequences and 
 * outputs file of amino acid sequences.
 * Solves CS181 Programming Assignment 4.
 * 3/05/14
 * @author Dan Coleman
 * 
 */
public class ConvertDNA 
{
	/**
	 * Checks if chosen file is an actual file.
	 * @param input The file to use for input
	 * @return Whether file is a valid file
	 */
	private static boolean inputValidity(File input)
	{
		// Assume file is valid
		boolean valid = true;
		// Try to prove invalidity
		try
		{
			Scanner in = new Scanner(input);
			in.close();
		}
		catch (FileNotFoundException exception)
		{
			valid = false;
		}
		
		return valid;
	}
	
	
	/**
	 * Converts a triplet of DNA to an amino acid.
	 * @param dna The DNA triplet
	 * @return The amino acid of a DNA triplet.
	 */
	private static String toAmino(String dna)
		throws IllegalArgumentException
	{
		String amino;
		// Uses switch statement to convert the triplets
		switch (dna)
		{
		case "TCA": amino = "S"; break;
		case "TCC": amino = "S"; break;
		case "TCG": amino = "S"; break;
		case "TCT": amino = "S"; break;
		case "TTC": amino = "F"; break;
		case "TTT": amino = "F"; break;
		case "TTA": amino = "L"; break;
		case "TTG": amino = "L"; break;
		case "TAC": amino = "Y"; break;
		case "TAT": amino = "Y"; break;
		case "TAA": amino = "_"; break;
		case "TAG": amino = "_"; break;
		case "TGC": amino = "C"; break;
		case "TGT": amino = "C"; break;
		case "TGA": amino = "_"; break;
		case "TGG": amino = "W"; break;
		case "CTA": amino = "L"; break;
		case "CTC": amino = "L"; break;
		case "CTG": amino = "L"; break;
		case "CTT": amino = "L"; break;
		case "CCA": amino = "P"; break;
		case "CCC": amino = "P"; break;
		case "CCG": amino = "P"; break;
		case "CCT": amino = "P"; break;
		case "CAC": amino = "H"; break;
		case "CAT": amino = "H"; break;
		case "CAA": amino = "Q"; break;
		case "CAG": amino = "Q"; break;
		case "CGA": amino = "R"; break;
		case "CGC": amino = "R"; break;
		case "CGG": amino = "R"; break;
		case "CGT": amino = "R"; break;
		case "ATA": amino = "I"; break;
		case "ATC": amino = "I"; break;
		case "ATT": amino = "I"; break;
		case "ATG": amino = "M"; break;
		case "ACA": amino = "T"; break;
		case "ACC": amino = "T"; break;
		case "ACG": amino = "T"; break;
		case "ACT": amino = "T"; break;
		case "AAC": amino = "N"; break;
		case "AAT": amino = "N"; break;
		case "AAA": amino = "K"; break;
		case "AAG": amino = "K"; break;
		case "AGC": amino = "S"; break;
		case "AGT": amino = "S"; break;
		case "AGA": amino = "R"; break;
		case "AGG": amino = "R"; break;
		case "GTA": amino = "V"; break;
		case "GTC": amino = "V"; break;
		case "GTG": amino = "V"; break;
		case "GTT": amino = "V"; break;
		case "GCA": amino = "A"; break;
		case "GCC": amino = "A"; break;
		case "GCG": amino = "A"; break;
		case "GCT": amino = "A"; break;
		case "GAC": amino = "D"; break;
		case "GAT": amino = "D"; break;
		case "GAA": amino = "E"; break;
		case "GAG": amino = "E"; break;
		case "GGA": amino = "G"; break;
		case "GGC": amino = "G"; break;
		case "GGG": amino = "G"; break;
		case "GGT": amino = "G"; break;
		// If no match found in the triplets, exception is thrown
		default: throw new IllegalArgumentException();
		}
		
		return amino;
	}
	
	
	/**
	 * Converts each DNA sequence in an array into an
	 * amino acid sequence.
	 * @param info Array of DNA sequences
	 * @param count Index of last sequence in info
	 * @return Array of amino acid sequences
	 */
	private static String[][] convertSeq(String[][] info, int count)
	{
		// Cycles through all the sequences
		for (int k = 0; k <= count; k++)
		{
			String aaSeq = "";
			// Breaks up the sequences for conversion
			for (int i = 0; (i + 2) < info[k][1].length(); i = i + 3)
			{
				aaSeq = aaSeq + toAmino(info[k][1].substring(i, i + 3));
			}
			info[k][1] = aaSeq;
		}
		return info;
	}
	
	
	/**
	 * Creates a file and writes into the file each amino 
	 * acid sequence in an array of such sequences, with
	 * each sequence preceded by its respective description.
	 * @param inName Name of original input file
	 * @param info Array of amino acid sequences
	 * @param count Index of last sequence in info
	 * @return Output filename for use in testing
	 */
	public static String toFile(String inName, String[][] aaInfo, int count)
		throws FileNotFoundException
	{
		String outName;
		final int EXTENSION = 6;	// Length of .fasta extension
		outName = inName.substring(0, (inName.length() - EXTENSION));
		outName = outName + "-aa.fasta";
		
		PrintWriter out = new PrintWriter(outName);
		
		// Print formatted amino sequence data to file
		for (int i = 0; i <= count; i++)
		{
			out.println(aaInfo[i][0]);
			final int MAX_LINE_LENGTH = 75;
			String line = "";
			
			for (int k = 0; k < aaInfo[i][1].length(); k++)
			{
				line = line + aaInfo[i][1].substring(k, k + 1);
				if (line.length() == MAX_LINE_LENGTH)
				{
					out.println(line);
					line = "";
				}
				else if (k == aaInfo[i][1].length() - 1)
				{
					out.println(line);
					line = "";
				}
			}
			
			out.println();
		}
		
		out.close();
		
		// Return outName for testing purposes
		return outName;
	}
	
	/**
	 * Prints to the command line the description segment from each 
	 * sequence followed by the number of sequences processed.
	 * @param info Array of amino acid sequences
	 * @param count Index of last sequence in info
	 */
	private static void toTerminal(String[][] info, int count)
	{
		// List all descriptions
		for (int i = 0; i <= count; i++)
		{
			System.out.println(info[i][2].trim());
		}
		
		// Print the number of sequences
		System.out.print(count + 1);		// Add 1 to offset init at -1
		System.out.println(" sequences processed.");
		System.out.println();
	}
	
	public static void main(String[] args) throws FileNotFoundException
	{
		// Take the filepath given on the command line 
		// in the program call.
		String inName = args[0];
		System.out.println();
		
		File inputFile = new File(inName);
		
		// Verify that the file exists. Throw an exception
		// if it doesn't, otherwise continue.
		if (inputValidity(inputFile) == false)
		{
			System.out.println("0 sequences processed.");
			System.out.println();
			throw new FileNotFoundException();
		}
		
		Scanner in = new Scanner(inputFile);
		
		int seqCount = -1;	// Initialize seq. counter below 1st index.
		final int SEQS_LIMIT = 50;		// Number of rows possible.
		String[][] info = new String[SEQS_LIMIT][3];
	
		// Initialize locations of possible sequence entries
		// to avoid a "null" value at the beginning of the sequence.
		for (int i = 0; i < info.length; i++)
		{
			info[i][1] = "";
		}
		
		// Take input from the selected file.
		while (in.hasNextLine())
		{
			String nextLn = in.next();
			// Determine if description line or part of sequence.
			if (nextLn.substring(0, 1).equals(">"))
			{
				seqCount++;		// Update index variable.
				
				info[seqCount][2] = in.nextLine();
				info[seqCount][0] = nextLn + info[seqCount][2];
			}
			else
			{
				info[seqCount][1] = info[seqCount][1] + nextLn;
				info[seqCount][1].toUpperCase();
			}
		}
		
		// Do the actual DNA to amino conversion.
		String[][] aaInfo = convertSeq(info, seqCount);
		
		// Print the converted materials to the correct file.
		toFile(inName, aaInfo, seqCount);
		
		// Print the correct output to the terminal.
		toTerminal(aaInfo, seqCount);

		in.close();
	}
	
}

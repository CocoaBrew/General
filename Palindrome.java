import java.util.Scanner;

public class Palindrome 
{
	private static boolean isPalindrome(String word)
	{
		for (int i = 0; i < (word.length() / 2); i++)
		{
			if (word.charAt(i) != word.charAt(word.length() - (1 + i)))
			{
				return false;
			}
		}
		
		return true;
	}
	
	public static void main(String[] args) 
	{
		Scanner in = new Scanner(System.in);
		
		System.out.print("Enter a string: ");
		String word = in.nextLine().trim();
		in.close();
		if (isPalindrome(word))
		{
			System.out.print("String is a palindrome.");
		}
		else
		{
			System.out.print("String isn't a palindrome.");
		}
	}
}

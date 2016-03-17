import java.util.Scanner;

/**
 * Implements Player interface to make a player that
 * plays based on user input.
 * @author Dan Coleman
 *
 */
public class HumanPlayer implements Player
{
	/**
	 * Allows an human player to make a move.
	 * @param board The game board prior to the move.
	 * @param playerNum Whose turn it is.
	 * @return The board after the move.
	 */
	public String[][] move(String[][] board, int playerNum)
	{
		Scanner in = new Scanner(System.in);
		
		System.out.print("Player ");
		System.out.print(playerNum);
		System.out.print("'s move? ");
		String theMove = in.nextLine().trim().toUpperCase();
		
		String theRow = theMove.substring(0,1);
		String theCol = theMove.substring(1,2);
		try
		{
			toRowNum(theRow);
			toColNum(theCol);
		}
		catch (IllegalArgumentException e)
		{
			System.out.println("Invalid entry. Try again.");
			this.move(board, playerNum);
		}
		
		int row = toRowNum(theRow);
		int column = toColNum(theCol);
		
		if (board[row][column].equals(" "))
		{
			board[row][column] = whichMark(playerNum);
		}
		else
		{
			System.out.println("Space already taken. Try again.");
			this.move(board, playerNum);
		}
		
		return board;
	}
	
	/**
	 * Converts row identifier to its correct index.
	 * @param theRow The input row's letter.
	 * @return Converted index value.
	 * @throws IllegalArgumentException If identifier is an invalid letter.
	 */
	private int toRowNum(String theRow) 
		throws IllegalArgumentException
	{
		int row;
		
		switch (theRow)
		{
		case "A": row = 0; break;
		case "B": row = 1; break;
		case "C": row = 2; break;
		default : throw new IllegalArgumentException();
		}
		
		return row;
	}
	
	/**
	 * Converts column number to its correct index.
	 * @param theCol The input column's number.
	 * @return Converted index value.
	 * @throws IllegalArgumentException If identifier is an invalid number.
	 */
	private int toColNum(String theCol) 
		throws IllegalArgumentException
	{
		int column;
		
		switch (theCol)
		{
		case "1": column = 0; break;
		case "2": column = 1; break;
		case "3": column = 2; break;
		default : throw new IllegalArgumentException();
		}
		
		return column;
	}
	
	/**
	 * Decides which mark is appropriate for current move.
	 * @param playerNum Player number of player that is playing.
	 * @return The mark of the currently playing player.
	 */
	private String whichMark(int playerNum)
	{
		String playerMark;
		if (playerNum == 1)
		{
			playerMark = "X";
		}
		else
		{
			playerMark = "O";
		}
		
		return playerMark;
	}
}

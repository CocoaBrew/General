import java.util.Random;

/**
 * Implements Player interface to make a player that
 * will move without user input.
 * @author Dan Coleman
 *
 */
public class ComputerPlayer implements Player
{
	/**
	 * Allows an automated player to make a move.
	 * @param board The game board prior to the move.
	 * @param playerNum Whose turn it is.
	 * @return The board after the move.
	 */
	public String[][] move(String[][] board, int playerNum)
	{
		System.out.print("Player 2's move: ");
		
		int row;
		int column;
		Random misc = new Random();
		do
		{
			row = misc.nextInt(3);
			column = misc.nextInt(3);
		} while (!board[row][column].equals(" "));
		
		final String player2Mark = "O";
		board[row][column] = player2Mark;
		
		this.printMove(row, column);
		return board;
	}
	
	/**
	 * Prints the move selection to the screen.
	 * @param row Row index of the random move.
	 * @param column Column index of the random move.
	 */
	private void printMove(int row, int column)
	{
		System.out.println(toRowChar(row) + toColChar(column));
	}
	
	/**
	 * Converts an index to its correct row identifier.
	 * @param row Row index in the board array.
	 * @return The correct row letter.
	 */
	private String toRowChar(int row)
	{
		String theRow;
		
		switch (row)
		{
		case 0: theRow = "A"; break;
		case 1: theRow = "B"; break;
		case 2: theRow = "C"; break;
		default: theRow = " ";
		}
		
		return theRow;
	}
	
	/**
	 * Converts an index to its correct column number.
	 * @param column Column index in the board array.
	 * @return The correct column number.
	 */
	private int toColChar(int column)
	{
		int theCol;
		switch (column)
		{
		case 0: theCol = 1; break;
		case 1: theCol = 2; break;
		case 2: theCol = 3; break;
		default: theCol = -1;
		}
		
		return theCol;
	}
}

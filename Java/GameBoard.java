/**
 * Provides Tic-Tac-Toe game with a board and a means
 * of testing for winning situations.
 * @author Dan Coleman
 *
 */
public class GameBoard 
{
	private final int SIZE = 3; //length and width of game template
	private String[][] board;
	private boolean player1Turn; //remembers whose turn it is
	
	private Player player1;
	private Player player2;
	
	/**
	 * Constructor for new game with two human players.
	 * @param p1 Player to be player1.
	 * @param p2 Player to be player2.
	 */
	public GameBoard(HumanPlayer p1, HumanPlayer p2)
	{
		player1 = new HumanPlayer();
		player2 = new HumanPlayer();
		player1Turn = true;
		
		board = new String[SIZE][SIZE];
		for (int i = 0; i < SIZE; i++)
		{
			for (int k = 0; k < SIZE; k++)
			{
				board[i][k] = " ";
			}
		}
	}
	
	/**
	 * Constructor for new game with one human and
	 * one computer player.
	 * @param p1 Player to be player1.
	 * @param p2 Player to be player2.
	 */
	public GameBoard(HumanPlayer p1, ComputerPlayer p2)
	{
		player1 = new HumanPlayer();
		player2 = new ComputerPlayer();
		player1Turn = true;
		
		board = new String[SIZE][SIZE];
		for (int i = 0; i < SIZE; i++)
		{
			for (int k = 0; k < SIZE; k++)
			{
				board[i][k] = " ";
			}
		}
	}
	
	/**
	 * Prints the current status of the game's board.
	 */
	public void printBoard()
	{
		for (int i = 0; i < (SIZE - 1); i++)
		{
			System.out.println("   |   |   ");
			System.out.println(" " + board[i][0] + " | " + board[i][1] + " | " +
					board[i][2] + " ");
			System.out.println("___|___|___");
		}
		
		System.out.println("   |   |   ");
		System.out.println(" " + board[2][0] + " | " + board[2][1] + " | " +
				board[2][2] + " ");
		System.out.println("   |   |   ");
		System.out.println();
	}
	
	/**
	 * Lets the correct player play.
	 */
	public void move()
	{
		int playerNum = 0;
		if (player1Turn)
		{
			playerNum = 1;
			board = player1.move(board, playerNum);
			player1Turn = false;
		}
		else
		{
			playerNum = 2;
			board = player2.move(board, playerNum);
			player1Turn = true;
		}
	}
	
	/**
	 * Checks whether the game is in a completed state,
	 * either due to a win or a tie.
	 * @return Whether or not game finished.
	 */
	public boolean isFinished()
	{
		boolean done = false;
		if (this.isWinningCase() || this.isTieCase())
		{
			done = true;
		}
		
		return done;
	}
	
	/**
	 * Checks whether the game is a tie scenario.
	 * @return Whether or not a tie.
	 */
	private boolean isTieCase()
	{
		boolean full = true;
		//can't be a draw if any squares are empty
		for (int i = 0; i < SIZE; i++)
		{
			for (int k = 0; k < SIZE; k++)
			{
				if (board[i][k].equals(" "))
				{
					full = false;
				}
			}
		}
		
		return full;
	}
	
	/**
	 * Checks whether the game is a winning scenario.
	 * @return Whether a win is present.
	 */
	private boolean isWinningCase()
	{
		boolean gameWon = false;
		for (int i = 0; i < SIZE; i++)
		{
			if ((board[i][0].equals(board[i][1]) && 
					board[i][1].equals(board[i][2])) &&
					!board[i][0].equals(" "))
			{
				gameWon = true;
			}
			else if ((board[0][i].equals(board[1][i]) &&
					board[1][i].equals(board[2][i])) &&
					!board[0][i].equals(" "))
			{
				gameWon = true;
			}
		}
		if (((board[0][0].equals(board[1][1]) && 
				board[1][1].equals(board[2][2])) ||
				board[2][0].equals(board[1][1]) &&
				board[1][1].equals(board[0][2])) &&
				!board[1][1].equals(" "))
		{
			gameWon = true;
		}
		
		return gameWon;
	}
	
	/**
	 * Notifies user of game's result.
	 */
	public void printResult()
	{
		if (this.isWinningCase())
		{
			if (this.playerOneWins())
			{
				System.out.println("Player 1 wins.");
			}
			else
			{
				System.out.println("Player 2 wins.");
			}
		}
		else
		{
			System.out.println("Game ends in a draw.");
		}
	}
	
	/**
	 * Checks whether player1 is in a winning position.
	 * @return Whether player1 has won.
	 */
	private boolean playerOneWins()
	{
		boolean p1Wins = false;
		final String p1Mark = "X"; //mark for player one
		for (int i = 0; i < SIZE; i++)
		{
			if ((board[i][0].equals(board[i][1]) && 
					board[i][1].equals(board[i][2])) &&
					board[i][0].equals(p1Mark))
			{
				p1Wins = true;
			}
			else if ((board[0][i].equals(board[1][i]) &&
					board[1][i].equals(board[2][i])) &&
					board[0][i].equals(p1Mark))
			{
				p1Wins = true;
			}
		}
		if (((board[0][0].equals(board[1][1]) && 
				board[1][1].equals(board[2][2])) ||
				board[2][0].equals(board[1][1]) &&
				board[1][1].equals(board[0][2])) &&
				board[1][1].equals(p1Mark))
		{
			p1Wins = true;
		}
		
		return p1Wins;
	}
}

import java.util.Scanner;

/**
 * Sets up and plays a game of Tic-Tac-Toe.
 * Solves CS181 Programming Assignment 7.
 * 4/23/14
 * @author Dan Coleman
 * 
 */
public class TicTacToe
{
	public static void main(String[] args)
	{
   	Scanner in = new Scanner(System.in);
   	System.out.print("Play vs. 'h'uman or 'c'omputer? ");
   	String choice = new String(in.nextLine());
   	boolean computerIsPlayer = choice.equalsIgnoreCase("c");

   	GameBoard game;
   	if (computerIsPlayer)
   	{
   		game = new GameBoard(new HumanPlayer(), new ComputerPlayer());
   	}
   	else
   	{
   		game = new GameBoard(new HumanPlayer(), new HumanPlayer());
   	}
		
   	while (!game.isFinished())
   	{
   		game.move();
   		game.printBoard();
   	}
		
   	game.printResult();
   	
   	in.close();
   }
}
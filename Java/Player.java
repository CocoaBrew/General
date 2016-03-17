/**
 * Interface provides guidelines for the implementation
 * of the player classes.
 * @author Dan Coleman
 *
 */
public interface Player 
{
	/**
	 * Lets a player make a move.
	 * @param grid The game's board prior to the next player playing.
	 * @param whichPlayer Whose turn it is.
	 * @return The board after the move.
	 */
	public String[][] move(String[][] grid, int whichPlayer);
}

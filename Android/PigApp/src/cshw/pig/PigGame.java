package cshw.pig;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.TextView;

/* Dice image credits: http://etc.usf.edu/clipart/galleries/579-dice */

/**
 * Runs an ANDROID application that plays a Pig. 
 * @author Dan Coleman
 * @version 12/5/14
 */
public class PigGame extends Activity 
{
	private static ImageView viewer;
	private static TextView tView;
	private static TextView personScoreView;
	private static TextView compScoreView;
	private static Button rollButton;
	private static Button scoreButton;
	private static int value;
	private static int turnTotal = 0;
	private static final int WINNING_SCORE = 50;
	private static final int COMP_TURN_MAX = 20;
	private static Player person;
	private static Player computer;
	private static boolean isHumanTurn = true;
	
	@Override
	/**
	 * Initializes variables on creation of a game instance.
	 */
	protected void onCreate(Bundle savedInstanceState) 
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_pig_game);
		viewer = (ImageView) findViewById(R.id.roll_result);
		tView = (TextView) findViewById(R.id.turn_score);
		personScoreView = (TextView) findViewById(R.id.hum_total_score);
		compScoreView = (TextView) findViewById(R.id.comp_total_score);
		rollButton = (Button) findViewById(R.id.roll_die);
		scoreButton = (Button) findViewById(R.id.hold_score);
		person = new Player();
		computer = new Player();
	}

	/**
	 * Specifies what happens on a "ROLL" button press.
	 * @param v Main view of program.
	 */
	public static void rollDie(View v)
	{		
		scoreButton.setClickable(true);
		roll();
		if (value != 1)
		{
			turnTotal = turnTotal + value;
			tView.setText(String.valueOf(turnTotal));
		}
		else
		{
			turnTotal = 0;
			tView.setText(String.valueOf(turnTotal));
			computerPlay();
		}
	}
	
	/**
	 * Sets the random roll value of the die.
	 */
	private static void roll()
	{
		value = (int) (Math.random() * 6) + 1;
		if (value == 1)
			viewer.setImageResource(R.drawable.die_01);
		else if (value == 2)
			viewer.setImageResource(R.drawable.die02);
		else if (value == 3)
			viewer.setImageResource(R.drawable.die_03);
		else if (value == 4)
			viewer.setImageResource(R.drawable.die_04);
		else if (value == 5)
			viewer.setImageResource(R.drawable.die_05);
		else if (value == 6)
			viewer.setImageResource(R.drawable.die_06);
	}
	
	/**
	 * Enumerates logistics of computer's turn.
	 */
	private static void computerPlay()
	{
		isHumanTurn = false;
		if (computer.getScore() < WINNING_SCORE)
		{				
			if (person.getScore() < WINNING_SCORE)
			{
				turnTotal = 0;
				tView.setText(String.valueOf(turnTotal));
				do
				{
					roll();
					if (value != 1)
					{
						turnTotal = turnTotal + value;
					}
					else
					{
						turnTotal = 0;
					}
					tView.setText(String.valueOf(turnTotal));
				} while (turnTotal <= COMP_TURN_MAX);
				
				hold();
			}
		}
	}
	
	/**
	 * Specifies what happens on a "HOLD" button press.
	 * @param v Main view of program.
	 */
	public static void holdScore(View v)
	{
		hold();
		if (!isHumanTurn)
		{
			computerPlay();
		}
	}
	
	/**
	 * Sets updated score and checks if a winning scenario.
	 */
	private static void hold()
	{
		if (isHumanTurn)
		{
			person.setScore(turnTotal);
			personScoreView.setText(String.valueOf(person.getScore()));
			isHumanTurn = false;
		}
		else
		{
			computer.setScore(turnTotal);
			compScoreView.setText(String.valueOf(computer.getScore()));
			isHumanTurn = true;
		}
		
		turnTotal = 0;
		tView.setText(String.valueOf(turnTotal));
		
		if (computer.getScore() >= WINNING_SCORE)
		{
			setGameOver("Computer");
		}
		else if (person.getScore() >= WINNING_SCORE)
		{
			setGameOver("Human");
		}
	}
	
	/**
	 * Ends game.
	 * @param winner Who won game.
	 */
	private static void setGameOver(String winner)
	{
		tView.setText("---Game Over!---\n" + winner + " wins!");
		scoreButton.setClickable(false);
		rollButton.setClickable(false);
	}
}

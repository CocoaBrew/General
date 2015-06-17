package cshw.roll_a_die;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.ImageView;

public class MainActivity extends Activity 
{
	private static ImageView viewer;

	@Override
	protected void onCreate(Bundle savedInstanceState) 
	{
		super.onCreate(savedInstanceState);
		setContentView(R.layout.activity_main);
		viewer = (ImageView) findViewById(R.id.result);
	}
	
	public static void rollDie(View v)
	{		
		int value = (int) (Math.random() * 6) + 1;
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
}

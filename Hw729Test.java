/**
 * Sorts problems instances for Heapsort.
 * @author Dan Coleman
 * @version 4/13/15
 */
public class Hw729Test 
{
	public static void main(String[] args)
	{
		//case 1
		int[] s_array = {30, 25, 18, 20, 12, 19, 17, 16, 14, 11};
		Hw729 case1 = new Hw729(s_array);
		case1.runSort();
		
		//case 2
		int[] s2_array = {20, 30, 1, 45, 33, 72, 88, 1093, 334};
		Hw729 case2 = new Hw729(s2_array);
		case2.runSort();
		
		//case 3
		int[] s3_array = {20, 30, 1, 45, 33, 72, 88, 1093, 334,
				25, 18, 20, 12, 19, 17, 16, 14, 11, 309, 1190, 4359, 332, 207, 8};
		Hw729 case3 = new Hw729(s3_array);
		case3.runSort();
	}
}

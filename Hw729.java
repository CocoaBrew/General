/**
 * Solves problem 29 from chapter 7 (Heapsort).
 * @author Dan Coleman
 * @version 4/13/15
 */
public class Hw729 
{
	private Heap H;
	
	public Hw729(int[] values)
	{
		H = new Heap(values);
	}
	
	private class Heap
	{
		public int[] S;
		public int heapsize;
		
		public Heap(int[] s)
		{
			S = s;
		}
	}
	
	private static void siftdown(Heap H, int i)
	{
		int parent = i;
		int largerchild = 0;
		int siftkey = H.S[i];
		boolean spotfound = false;
		
		while (2 * parent < H.heapsize && !spotfound)
		{
			if (2 * parent < H.heapsize - 1 && H.S[2 * parent] < H.S[2 * parent + 1])
			{
				largerchild = 2 * parent + 1;
			}
			else
			{
				largerchild = 2 * parent;
			}
			if (siftkey < H.S[largerchild])
			{
				H.S[parent] = H.S[largerchild];
				parent = largerchild;
			}
			else
			{
				spotfound = true;
			}
		}
		
		H.S[parent] = siftkey;
	}
	
	private static int root(Heap H)
	{
		int keyout = H.S[0];
		H.S[0] = H.S[H.heapsize - 1];
		H.heapsize = H.heapsize - 1;
		siftdown(H, 0);
		return keyout;
	}
	
	private static void removekeys(int n, Heap H, int[] S)
	{
		for (int i = n - 1; i >= 0; i--)
		{
			S[i] = root(H);
		}
	}
	
	private static void makeheap(int n, Heap H)
	{
		H.heapsize = n;
		for (int i = (int) Math.floor((n - 1)/2); i >= 0; i--)
		{
			siftdown(H, i);
		}
	}
	
	private static void heapsort(int n, Heap H)
	{
		makeheap(n, H);
		removekeys(n, H, H.S);
	}
	
	public void runSort()
	{
		heapsort(H.S.length, H);
		for (int num : H.S)
		{
			System.out.print(num + " ");
		}
		
		System.out.println();
	}
}

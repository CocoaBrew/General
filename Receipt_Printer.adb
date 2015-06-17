WITH Ada.Text_IO;
WITH Ada.Integer_Text_IO;
WITH Ada.Float_Text_IO;

-----------------
-- Program will calculate total cost of purchase
-- and print receipt for the transaction.
--
-- Written by: Dan Coleman
-- Date: 4/30/14
-----------------

PROCEDURE Receipt_Printer IS

   Max_Name_Length : CONSTANT Integer := 100;

   Filename : String (1 .. Max_Name_Length);
   Filename_Length : Integer;
   File_Of_Items : Ada.Text_IO.File_Type; -- File of grocery info.

   Max_Grocery_String_Length : CONSTANT Integer := 64;
   Max_Items : CONSTANT Integer := 100;

   -- A grocery's name and the length of the name.
   TYPE Grocery_String IS RECORD
      Item_Name : String (1 .. Max_Grocery_String_Length);
      Item_Name_Length : Integer RANGE 0 .. Max_Grocery_String_Length;
   END RECORD;

   -- Every grocery has these qualities.
   TYPE Grocery_Item IS RECORD
      Item : Grocery_String;
      Quantity : Integer;
      Cost_Per_Item : Float;
      Total_Cost : Float;
   END RECORD;

   -- Defines a grocery list of groceries.
   TYPE Grocery_List IS ARRAY (1 .. Max_Items) OF Grocery_Item;


   -- Takes in grocery info from file
   -- and gives back list of groceries.
   PROCEDURE Read_File (
      List : OUT Grocery_List;
      Item_Num : OUT Integer) IS

      -- Quantity taken from file.
      Quantity_Of_Item : Integer;

      -- Price per unit taken from file.
      Price_Of_Item : Float;

      -- Item name taken from file.
      Name_Of_Item : Grocery_String;

      -- Calculated price of entire item
      Item_Total_Price : Float;

   BEGIN   --Read_File

      Ada.Text_IO.Put (Item => "Enter a filename: ");
      Ada.Text_IO.Get_Line (Item => Filename,
         Last => Filename_Length);
      Ada.Text_IO.New_Line;

      Ada.Text_IO.Open (File => File_Of_Items,
         Mode => Ada.Text_IO.In_File,
         Name => Filename (1 .. Filename_Length));

      Item_Num := 0; -- Item counter.

      -- Retrieves info from file and adds items to grocery list.
      WHILE NOT Ada.Text_IO.End_Of_File (File => File_Of_Items) LOOP
         WHILE NOT Ada.Text_IO.End_Of_Line (File => File_Of_Items) LOOP

            Item_Num := Item_Num + 1;

            Ada.Integer_Text_IO.Get (File => File_Of_Items,
               Item => Quantity_Of_Item);
            Ada.Float_Text_IO.Get (File => File_Of_Items,
               Item => Price_Of_Item);
            Ada.Text_IO.Get_Line (File => File_Of_Items,
               Item => Name_Of_Item.Item_Name,
               Last => Name_Of_Item.Item_Name_Length);

            Item_Total_Price := Float(Quantity_Of_Item) * Price_Of_Item;

            List(Item_Num).Item := Name_Of_Item;
            List(Item_Num).Quantity := Quantity_Of_Item;
            List(Item_Num).Cost_Per_Item := Price_Of_Item;
            List(Item_Num).Total_Cost := Item_Total_Price;

         END LOOP;
      END LOOP;

      Ada.Text_IO.Close (File => File_Of_Items);

   END Read_File;


   -- Sorts groceries in order of total item cost, cheapest
   -- to most expensive, using a selection sort algorithm.
   PROCEDURE Sort_Grocery_List (
      List : IN OUT Grocery_List;
      Size : IN Integer) IS

      Min : Integer;   -- Index of smallest element.
      Temp : Grocery_Item;   -- Temporary storage for elements.

   BEGIN   --Sort_Grocery_List

      Sort_List_Loop:
         FOR I IN 1 .. Size LOOP

         Min := I;

         -- Checks for index of cheapest item.
         Least_Cost_Loop:
            FOR J IN (I + 1) .. Size LOOP
            IF List(Min).Total_Cost > List(J).Total_Cost THEN
               Min := J;
            END IF;
         END LOOP Least_Cost_Loop;

         -- Verifies switching needs to be done, then switches.
         IF Min /= I THEN
            Temp := List(I);
            List(I) := List(Min);
            List(Min) := Temp;
         END IF;
      END LOOP Sort_List_Loop;

   END Sort_Grocery_List;


   -- Calculates total cost of purchase.
   FUNCTION Total_Transaction_Cost (
      List : IN Grocery_List;
      Size : IN Integer)
         RETURN Float IS

      Total : Float; -- Total cost of purchase.

   BEGIN   --Total_Transaction_Cost
      Total := 0.0;
      Find_Total_Loop:
         FOR I IN 1 .. Size LOOP
         Total := Total + List(I).Total_Cost;
      END LOOP Find_Total_Loop;

      RETURN Total;
   END Total_Transaction_Cost;


   -- For formatting output.
   -- Prints requested number of spaces.
   PROCEDURE Print_Spaces (Num_Of_Spaces : IN Integer) IS

      Space : CONSTANT String := " ";

   BEGIN   --Print_Spaces

      Space_Printing_Loop:
         FOR I IN 1 .. Num_Of_Spaces LOOP
         Ada.Text_IO.Put (Item => Space);
      END LOOP Space_Printing_Loop;

   END Print_Spaces;


   -- Outputs grocery list info.
   PROCEDURE Print_Receipt (
      List : IN Grocery_List;
      Size : IN Integer;
      Total : IN Float) IS

      -- Width of a grocery item line; total of
      -- printed item line widths.
      Line_Width : CONSTANT Integer := 45;

      -- Length of equals bar for the total.
      Bar_Length : CONSTANT Integer := Line_Width + 10;

   BEGIN   --Print_Receipt

      Print_Items_Loop:   -- Prints each item in grocery list.
         FOR I IN 1 .. Size LOOP
         Ada.Integer_Text_IO.Put (Item => List(I).Quantity,
            Width => 3);
         Ada.Text_IO.Put (Item => " ");
         Ada.Text_IO.Put (Item => List(I).Item.Item_Name(
            1..List(I).Item.Item_Name_Length));

         Print_Spaces (Num_Of_Spaces =>   -- Width of quantity plus space
            Line_Width - List(I).Item.Item_Name_Length - 4); -- equals 4.

         Ada.Float_Text_IO.Put (Item => List(I).Total_Cost,
            Fore => 6,
            Aft => 2,
            Exp => 0);
         Ada.Text_IO.New_Line;
      END LOOP Print_Items_Loop;

      Equals_Bar_Loop:   -- Starts the "equals bar."
         FOR I IN 1 .. (Bar_Length - 1)  LOOP -- Stops at Bar_Length-1
         Ada.Text_IO.Put (Item => "-");       -- so total bar has
      END LOOP Equals_Bar_Loop;               -- Bar_Length dashes after
                                              -- last dash from Put_Line
      Ada.Text_IO.Put_Line (Item => "-");     -- call.
      Ada.Text_IO.Put (Item => "Total:");

      Print_Spaces (Num_Of_Spaces =>   -- 6 is length of "Total:".
         Line_Width - 6);

      Ada.Float_Text_IO.Put (Item => Total,
         Fore => 6,
         Aft => 2,
         Exp => 0);
      Ada.Text_IO.New_Line;

   END Print_Receipt;


   -- Info about grocery items.
   Groceries : Grocery_List;

   -- Number of groceries.
   Purchase_Size : Integer;

   -- Total cost of entire transaction.
   Purchase_Cost : Float;

BEGIN   --Receipt_Printer

   -- Process file info.
   Read_File (List => Groceries,
      Item_Num => Purchase_Size);

   -- Sort the list of groceries.
   Sort_Grocery_List (
      List => Groceries,
      Size => Purchase_Size);

   -- Calculate total cost of purchase.
   Purchase_Cost := Total_Transaction_Cost (
      List => Groceries,
      Size => Purchase_Size);

   -- Print transaction info.
   Print_Receipt (List => Groceries,
      Size => Purchase_Size,
      Total => Purchase_Cost);

END Receipt_Printer;

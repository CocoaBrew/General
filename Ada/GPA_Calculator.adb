with Ada.Text_IO;
with Ada.Float_Text_IO;
with Ada.Integer_Text_IO;

----------------------------
-- This program is designed to calculate GPA.
--
-- Written by: Dan Coleman
-- Date: Jan. 11, 2014
----------------------------

procedure Calculate_GPA is

	Max_Name_Length : constant Integer := 31;
	Min_Credit		: constant Float := 0.5;
	Max_Credit		: constant Float := 6.0;
	Decimal_Output	: constant Integer := 2;

	-- Variable for loop control.
	Count : Integer;
	
	-- Student's name, entered by user.
	Student_Name : String (1 .. Max_Name_Length);
	
	-- Number of courses, entered by user.
	Number_Of_Courses : Integer;
	
	-- Create type for letter grade. 
	type Letter_Grade_Type is ("A", "B", "C", "D", "F");

	-- Letter grade for a course, entered by user.
	Grade : Letter_Grade_Type;
	
	-- Number of credit hours in course, entered by user. 
	Course_Credit_Hrs : Float range Min_Credit .. Max_Credit;
	
	-- Total number of credit hours, calculated by program.
	Total_Credit_Hrs : Float;
	
	-- Total number of quality points, calculated by program. 
	Total_Quality_Pts : Integer;
	
	-- Student's GPA, calculated by program.
	GPA : Float;
	
begin

	Ada.Text_IO.Put (Item => "Please enter student name: ");
	Ada.Text_IO.Get (Item => Student_Name);
	Ada.Text_IO.New_Line (Spacing => 2);
	Ada.Text_IO.Put (Item => "How many courses has ");
	Ada.Text_IO.Put (Item => Student_Name);
	Ada.Text_IO.Put (Item => " taken: ");
	Ada.Integer_Text_IO.Get (Item => Number_Of_Courses);
	Ada.Text_IO.New_Line (Spacing => 2);
	
	-- Initialize variables for loop.
	Count := 1;
	Total_Quality_Pts := 0;
	Total_Credit_Hrs := 0.0;
	
	Course_Input_Loop:
	loop
		exit Course_Input_Loop when Count > Number_Of_Courses;
		Ada.Text_IO.Put (Item => "Enter grade for course ");
		Ada.Integer_Text_IO.Put (Item => Count);
		Ada.Text_IO.Put (Item => ": ");
		Ada.Text_IO.Get (Item => Grade);
		Ada.Text_IO.New_Line;
		
		if Grade = "A" then 
			Total_Quality_Pts := Total_Quality_Pts + 4;
		elsif Grade = "B" then
			Total_Quality_Pts := Total_Quality_Pts + 3;
		elsif Grade = "C" then
			Total_Quality_Pts := Total_Quality_Pts + 2;
		elsif Grade = "D" then
			Total_Quality_Pts := Total_Quality_Pts + 1;
		else
			Total_Quality_Pts := Total_Quality_Pts + 0;
		end if;
		
		Ada.Text_IO.Put (Item => "Enter credit hours for course ");
		Ada.Integer_Text_IO.Put (Item => Count);
		Ada.Text_IO.Put (Item => ": ");
		Ada.Float_Text_IO.Get (Item => Course_Credit_Hrs);
		Ada.Text_IO.New_Line (Spacing => 2);
		
		-- Add course credits to total amount of credits.
		Total_Credit_Hrs := Total_Credit_Hrs + Course_Credit_Hrs;
		
		Count := Count + 1;
	end loop Course_Input_Loop;
	
	-- Perform GPA calculation
	GPA := Total_Quality_Pts / Total_Credit_Hrs;
	
	Ada.Text_IO.Put (Item => Student_Name);
	Ada.Text_IO.Put (Item => ", your GPA is ");
	Ada.Float_Text_IO.Put (
		Item => GPA,
		Aft  => Decimal_Output);
	Ada.Text_IO.Put (Item => ".");
	Ada.Text_IO.New_Line;
	Ada.Text_IO.Put (Item => "Assessment of your GPA: ");
	if GPA >= 3.7 then
		Ada.Text_IO.Put (Item => "Very respectable.");
	elsif (GPA >= 3.2) and (GPA < 3.7) then
		Ada.Text_IO.Put (Item => "Keep it up.");
	elsif (GPA >= 3.0) and (GPA < 3.2) then
		Ada.Text_IO.Put (Item => "Careful. Don't let it slip.");
	elsif (GPA >= 2.0) and (GPA < 3.0) then 
		Ada.Text_IO.Put (Item => "Needs some work.");
	elsif (GPA >= 1.0) and (GPA < 2.0) then
		Ada.Text_IO.Put (Item => "You are in a deep hole.");
	else
		Ada.Text_IO.Put (Item => "Ouch. There are no words....");
	end if;
	Ada.Text_IO.New_Line;
	
end Calculate_GPA;
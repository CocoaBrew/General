remove_last([S],S, []).
remove_last([H|T],S,[H| NT]):-remove_last(T,S,NT).

/*  format:  turing(left-tape, current-state, [current|right-tape]).  */

/*  move left  */
turing(Left_tape,Cur_state,[Cur_sym | Right_tape]):-
t(Cur_state,Cur_sym, New_state, New_sym, l),             /*  a move to the left */
write(Left_tape),write(Cur_state),write([Cur_sym | Right_tape]),
write(' '),write(New_sym),write(' left '),nl,                /* print out configuration and move information */
remove_last(Left_tape, Sym, New_left),                          /* remove last symbol from the tape to the left of the head */
turing(New_left,New_state, [Sym, New_sym | Right_tape]).       /* call with modified configuration  */

/* move right  */
turing(Left_tape,Cur_state,[Cur_sym | Right_tape]):-
t(Cur_state, Cur_sym, New_state, New_sym, r),   /*  a move to the right */
write(Left_tape),write(Cur_state),write([Cur_sym | Right_tape]),
write(' '), write(New_sym),write(' right '), nl,        /* print out configuration and move information */
append(Left_tape, [New_sym], New_left),         /*  attach symbol just written to the end of the left tape  */
turing(New_left, New_state, Right_tape).        /* call with modified configuration  */

/*   an empty right tape is interpreted as a blank  */
turing(Left_tape, Cur_state, []) :-
turing(Left_tape, Cur_state, [' ']).

/*  The Turing machine accepts if it is in an accepting state.  */
turing(Left_tape,Accept,[Cur_sym | Right_tape]) :-
accepting(Accept),
write(Left_tape),write(Accept),write([Cur_sym | Right_tape]),
write(' accept '),nl.

/*  If there are no moves, the machine rejects.  */
/*  if no other move, reject.  */

turing(Left_tape, Cur_state, [Cur_sym | Right_tape]) :-
write(Left_tape),write(Cur_state),
write([Cur_sym | Right_tape]),
write(' reject '), nl.


/* Decider for twice as many 'b's as 'a's (Problem #1)*/
/* Dan Coleman */

/*  transition function format: t(Current_state, Current_symbol, New_state, New_symbol, Direction)  */
start(s1).              /* starting state  (Using first character delimiter '#') */
t(s1,#,s1,#,r).
t(s1,a,s2,x,r).         /*  State checks for next character  */
t(s1,x,s1,x,r).
t(s1,b,s4,x,r).
t(s1,' ',s9,' ',r).	/* If empty string is found, move to acceptance */

t(s2,a,s2,a,r).         /*  finds first b following an a  */
t(s2,b,s3,x,r).
t(s2,x,s2,x,r).

t(s3,b,s5,x,l).         /*  Find second b after initial a, move to return state.  */
t(s3,a,s3,a,r).
t(s3,x,s3,x,r).

t(s4,b,s6,x,r).         /* If first char seen is b, seeing b sends to state looking for a, */
t(s4,x,s4,x,r).         /* else seeing a sends to state looking for b.  */
t(s4,a,s3,x,r).

t(s6,a,s5,x,l).         /* State that finds a and moves to return state */
t(s6,b,s6,b,r).
t(s6,x,s6,x,r).

t(s5,#,s1,#,r).	       /*  Return to beginning of string  */
t(s5,x,s5,x,l).
t(s5,a,s5,a,l).
t(s5,b,s5,b,l).

accepting(s9).

top(L):-start(S),turing([],S,L).

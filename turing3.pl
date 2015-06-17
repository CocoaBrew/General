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


/* Adding one to a binary number (Problem #3)*/
/* Dan Coleman */

/*  transition function format: t(Current_state, Current_symbol, New_state, New_symbol, Direction)  */
start(s0).              /* starting state (Input string is reverse of actual binary number) */
t(s0,0,s9,1,r).         /*  */
t(s0,1,s0,0,r).         /*  */
t(s0,' ',s9,1,l).
accepting(s9).

top(L):-start(S),turing([],S,L).

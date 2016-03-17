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


/* Copying string, marking separation with a delimiter (Problem #2)*/
/* Dan Coleman */

/*  transition function format: t(Current_state, Current_symbol, New_state, New_symbol, Direction)  */
start(s0).              /* starting state  */
t(s0,x,s0,x,r).         /* If x is seen, move right */
t(s0,a,s1,x,r).         /* If a is seen, change a to x and move to s1 */
t(s0,b,s8,x,r).         /* If b is seen, change b to x and move to s8 */
t(s0,^,s10,^,r).        /* If ^ is seen, enter acceptance state */

/* If a is seen */
t(s1,^,s2,^,r).	     /* State that places delimiter */
t(s1,' ',s2,^,r).
t(s1,a,s1,a,r).
t(s1,b,s1,b,r).

t(s2,' ',s5,a,l).    /* State that writes a then changes to return state */
t(s2,a,s2,a,r).
t(s2,b,s2,b,r).

t(s5,x,s0,a,r).      /* Return state if a was seen and written. Changes x back to a */
t(s5,a,s5,a,l).
t(s5,b,s5,b,l).
t(s5,^,s5,^,l).

/* If b is seen */
t(s8,^,s9,^,r).	     /* State that places delimiter */
t(s8,' ',s9,^,r).
t(s8,a,s8,a,r).
t(s8,b,s8,b,r).

t(s9,' ',s6,b,l).    /* State that writes b then changes to return state */
t(s9,a,s9,a,r).
t(s9,b,s9,b,r).

t(s6,x,s0,b,r).	     /* Return state if b was seen and written. Changes x back to b */
t(s6,a,s6,a,l).
t(s6,b,s6,b,l).
t(s6,^,s6,^,l).


accepting(s10).

top(L):-start(S),turing([],S,L).

% Dan Coleman
% CS 191 Prolog Assignment 1
% 3-21-14

/* Can be deciphered using the additive cipher with the negative
of the enciphering key.*/
additive(A, Key, B) :-
	name(A, X),
	change(X, Key, Y),
	name(B, Y).

/* Adds Key to each letter's ASCII value, takes the modulus of that value,
then converts the result back into letter form.*/
change([], _, []).
change([H|T], Key, [R|S]) :-
	mod2(H - 97 + Key, 26, A),
	R is A + 97,
	change(T, Key, S).

mod2(X, Y, Z) :- Z is integer(X - Y*floor(X/Y)).    % Gives desired modulus.



/* It is necessary to create another algorithm to decipher.
Except in some particular instances when attempting to
decipher the message, fractions do not work.
In the multiplicative cipher, there are fixed points when the key
equals one, since anything times one is itself, and there is always a
fixed point at "a" since zero times anything is zero.
A = 97; 97 - 97 = 0; 0 * K = 0; 0 + 97 = 97; and 97 = A. */
multiplicative(A, Key, B) :-
	name(A, X),
	change_m(X, Key, Y),
	name(B, Y).

/* Multiplies each letter's ASCII value by Key, takes the modulus of that value,
then converts the result back into letter form.*/
change_m([], _, []).
change_m([H|T], Key, [R|S]) :-
	mod2((H - 97) * Key, 26, A),
	R is A + 97,
	change_m(T, Key, S).

/* Outputs the number (L) of, in our case, distinct letters
that are contained in the string X.*/
distinct(X, L) :-
	name(X, Y),
	setof(A, member(A, Y), Z),
	length(Z, L).

/* Loops through keys 1 to 26 and checks how many distinct letters
each iteration has. Prints the key, how many distinct letters the
enciphered output has, and the output itself for each key.
Takes parameters of A, the string to be enciphered, and
Key, the largest possible key. In this case, Key will be 26.*/
test(_, 0) :- write('End of test.'), nl, nl.   % Basis case.
test(A , Key) :-
	Key > 0,
	multiplicative(A, Key, B),
	distinct(B, Num),   % Num is number of distinct letters in output string.
	write('Key: '),
	write(Key), nl,
	write('Number of distinct letters: '),
	write(Num), nl,
	write(B), nl, nl,
	K is Key - 1,
	test(A, K).   % Recursive call.



/* Requires a new algorithm to decipher.
Cipher works best when the keys, but especially the multiplicative key,
are not equal to zero.
The affine cipher first applies additive and then multiplicative.
A_Key is the key that is to be used as the key in the additive cipher,
M_Key is the key that is to be used as the key in the multiplicative cipher.*/
affine(A, A_Key, M_Key, B) :-
	additive(A, A_Key, T),
	multiplicative(T, M_Key, B).

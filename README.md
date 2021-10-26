# Stack-Machine
A single-register accumulator written in "modern" PHP 8.

If you have to ask why, the answer is always "Why not?"

## Writing Code Input
Input files for Stack Machine (abbreviated SM from here on) are read
as single tokens separated by newlines (`\n`).

There are 10 basic instructions. A line consisting of an integer is simply pushed to the stack.

Unless a NULL instruction, all instructions push their result to the stack.

### Instructions
- `add` - Adds the last two integers on the stack together
- `sub` - Subtracts the last integer on the stack from the second to last
- `label` - Defines a label
- `jmp` - Jumps to the label provided
- `jz` - Jumps to the token provided IF the top value on the stack is zero
- `dup` - Duplicates the next value on the stack
- `setv` - Sets the top value on the stack as a variable
- `getv` - Recalls a set variable and pushes it to the stack
- `pnum` - Print the next integer on the stack
- `pchr` - Print the ASCII codepoint represented by the next integer on the stack. (Values over 255 will be bitewise AND with 255)

### Example Program
This example program will store the integers 3 and 6 as variables, recall
them, add them together, then print them. Then it will print an ASCII 
newline before exiting.
```
3
setv(test)
6
setv(testtwo)
getv(test)
getv(testtwo)
add
pnum
10
pchr
```

## Running A Program
The first argument provided to `main.php` should be the relative path
to the program. For example:
`php main.php tests/math.sm`
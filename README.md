### Oracle Assembler in PHP

PASM is an attempted port of Oracle's Assembly into PHP (the "P" in "PASM" stands for "PHP", and the "ASM" stands for Assembly).

PASM requires at least PHP >= 5.4 to run correctly.


Here's an Assembly book I'm writing. Jsut in case you wonder if I have any idea of the language itself as to be so crazy as to put it in PHP :)


# Complex Coding for Dummies
\#/

a wastebin!

perfect!

that's where the idea that is code goes 😀

hehe

this is code:

Its boils down to you. I am always ready..

OK.


    for (uint8_t sfd : dictionary)
    {
      if (sfd != 1)   // is sfd == 1
        elems += (sfd);  // if not, add the sfd to the elems
      else
      {
        dx.push_back(elems); // if so, insert into dx
        elems = "";    // reset elems
      }
    }

you see? it represents places to carry values throughout the code, and it is about inserting values 

that make the code do the object of it all

the things that carry code are variables

and they will stay variant, unless you use it as a constant

OK

a constant is Π

pi

always 3.14..

The reduced Planck length, is always the same

OK.

I'll take that as a yes

so the RPL is a constant

ALWAYS USE DESCRIPTIVE NAMES

Because someone will help you someday, and you want them to understand the code

putting together an equation

the variable always goes on the left

it will never be on the right

so, string_var = "this is a string"

now that information is tagged like a picture. and it represents the sentence on the right

Space_var= this is a space

naturally, there are different types of information

you can't hold a "literal" (a literally written in number) like string_var = 3

it wuold need to be "3"

there are other types

in fact, you can make your own

they are treated how YOU want them treated

you make actions for them to do, and they are created by code. For the use of reuse

those are called functions

methods, which we will will talk about is another name for one. But only for a special reason.

they are the behaviors of the types

nowhere in C or C++ will you be writing real code outside of a function

OK

In fact tho, Assembly does a way that represents the function, by putting it all in a line and makes 

it descend.

the real thing between them, the relationship, is that they are all linear

they always come down, never just going up

the possibility is there, but your coding friends would beat you up ;D

OK

loops do what I said there

they make you return to a position, a linear position, and do the code again

these are done by commands

OK.

in assembly, we simply type

Give me an example of you loop

okay

the one I showed you, the yellow box, was a for loop. a special one that is just an easy way to do

now in Assembly

it would be


    loop:      ; linear position for jmp calls
    ..         ; pseudo code
    ..         ; same
    jmp loop   ; goto linear position called "loop"

that would go again and again

the dots are code

OK

the dots here are not code though

they are just pseudo code

something that we will speak of

pseudo code gives you an idea of what you're going to do and how you want to accomplish it

its like 

if (f(x) == g(x)) then goto the next command

you would actually write that

if you don't figure out your shorthand for yourself

that's just an example

this is a real if


    if (f(x) == g(x))
    {
        return x;
    }

assembly is complex but its cool like this:

(pseudo)


    mov ahx, bhx     ; move bhx to ahx
    dec ahx          ; decrement ahx (btw, always comment)
    inc bhx          ; increment bhx
    cmp bhx, ahx     ; compare the two
    jlz loop_point   ; are is bhx less than ahx, or is it a zero value?


this is false, here. How'd it know?

cmp means compare

it lists usable "flags" that tell jmp (which jumps automatically for the heck of it) to jmp or not

that's based on the "verb" you use. "nouns"?

I think you're referring to variables you created

like


    [variable-name]    define-directive    initial-value   [,initial-value]...
    
    name_of_variable   qword               "Hello World!", 0xa
| Directive | Purpose           | Storage Space      |
| --------- | ----------------- | ------------------ |
| DB        | Define Byte       | allocates 1 byte   |
| DW        | Define Word       | allocates 2 bytes  |
| DD        | Define Doubleword | allocates 4 bytes  |
| DQ        | Define Quadword   | allocates 8 bytes  |
| DT        | Define Ten Bytes  | allocates 10 bytes |

| Directive | Purpose              |
| --------- | -------------------- |
| RESB      | Reserve a Byte       |
| RESW      | Reserve a Word       |
| RESD      | Reserve a Doubleword |
| RESQ      | Reserve a Quadword   |
| REST      | Reserve a Ten Bytes  |

So that is obviously easy.

You do this in the section.bss  portion of the file

oh well

so if bhx is less than or zero compared to ahx, then it would go to loop_point

that's the easy part of Assembly

you can do linear jump points in C and C++ too

they use

goto

Ok

so

goto loop_point

would take you to your code below that point in the line count of the file

and so we get to talking of scope

scope is everything underneath the loop_point is visible

to the loop_point

but everything before is not

OK

fortunately, the geniuses who wrote the languages, made them easy by plotting the points like 
loop_point in the program beforehand, so you can pass down, but if you goto loop_point, it's going down

and yes you can write your own language

that's actually what Assembly was partially written for

Assembly came from binary

that shits over my head

but to be still in scope

like this way of telling you, by marking the area with a title, you can talk about the title in code that describes the intent and accomplishes it. 


the greatest coders skip much of the introduction tho as writing their code for intent, like setting up things, the virtual world just lets you climb in.

They do this by using native functions

native functions are built in, and completely predesigned (and very helpful)

alas, understand we're having a nested conversation about coding

i said, “this is coding”, that was the object here

we'll get into objects later

still going?

i hope you're writing notes

Assembly code works on things called opcodes. so when you're doing a function, it's already built in what that is. And you have to write it into the specific variable, named 'ax' to make it happen. its like turning your phone to the wifi station, and going to do a calculator on the web

That is actually the basic nature of binary.

To work with as little information as possible.

In fact, humans don’t read binary code

It’s not bloody-likely they will either.

It was designed and formatted for computation.

only true analysis can comprehend binary

as in, which opcode is where.

And just to tell you, that is called “low level”

Binary is the lowest in software

As for being important to know binary, it’s really not.

The math it creates is helpful, by visualizing the bits of it moving around

So, here is 0-7 of a nibble (a 4 bit piece of a byte [a half])

0000
0001
0010 (we did 1 x 2. Remember, it went to the left on x 2. You’ll recognize 4)
0011 (we went back to pick up 1 again, and since it’s mod 1, it will be an odd number [trust me])
0100 4. (2 x 2)
0101 5.
0110 6.
0111 7.

Now if I moved the whole thing over 1 to the left, it would be 7 x 2 which is 14.

1110 it’s not odd, it has the 2, the 4, and the 8

Magically we have a way to multiply by even numbers.

wait, what about 6?

0110 x 0111
that would be x4 x2 because we want 6 more
so, it would be 42

00010110

Boom!
done.

So, now when we use


    mov rhx, 7  ; move 7 into rhx
    mul rhx, 6   ; multiply rhx by 6

then 


    eax == 42

eax? Why eax?

No reason.

eax is just the pneumonic for the inline-function return value.

inline-function? Oh yea, linear.

linear is a important sounding word for inline, meaning “on the same line”

They exist in C and C++ too. As well as other languages. (not so sure about Python)

the pneumonic is the register associated with such an action.

eax is a 32-bit (meaning 2 to the 32nd power number) register

register is where we hold the ticket, if you will.

this “value” is “registered” in eax

That’s what you might say or not. You can talk anyway you want.

In fact, if you want to read this book still, and you’re with me, you can talk or not talk all you want.

You’ll be making so much money for doing this level of programming that your mom can move into your basement. It’s okay. You could furnish it.


But that’s on your own time.


----------

Chapter 2

I would make a table of every function in Assembly. But I’m not going to! Not here anyway.

If you want you can look at the Appendix.

It has every operation that you can execute in Oracle Assembly.

Well, why? Why just Oracle? Doesn’t that hurt my chances?

Well no. Oracle is a huge company with one thing on its mind. Popularity.

They want their packages to be sold, so they pay the big bucks to get into colleges and such.

Speaking of which. Nearly every single distribution of Assembly (whatever flavor you like)
is essentially the same thing. The only differences they could have, are the format of the file.

Now me, personally, I like NASM. That’s an open source Assembly Compiler. Great thing there.

I am going to use the Oracle set of functions, but I’m going to make sure they’re in NASM. Or I’ll report the difference.

The entitled name of this book, Complex Coding for Dummies, “_start” as we’ll call it in the program, where we begin our program is called by typing this at the beginning:


    .global _start

Easy! 

Now the program will start with the _start function, because we named it

In a later Chapter, we’ll definitely get to talking about Assembly in C++ and really, C

There are a few that allow it, at least.

Now we want to make a hello world!


    .global _start
    
    _start:
            ; write(1, message, 13)
            mov     rax, 1                ; system call 1 is write
            mov     rdi, 1                ; file handle 1 is stdout
            mov     rsi, message          ; address of string to output
            mov     rdx, 13               ; number of bytes
            syscall                       ; invoke operating system to do the write
    
            ; exit(0)
            mov     rax, 60               ; system call 60 is exit
            xor     rdi, rdi              ; we want return code 0
            syscall                         # invoke operating system to exit
    section .text
    title:   db      "Complex Coding", 0  ; we will not use this, but since I said it
    series:  db      "For Dummies", 0     ; The Title Series
    message: db      "Hello, world\n", 0  ; our message

By yourself, you might understand that a function is an operation. It’s just a simple connection between the two words. Or ahHA! You get the connection now.

There. Level playing field, created.

When we think of these things, we try to ignore hard ideas and conceptualize something more or less vivid to the imagination that won’t shut you down or out to the business of its dealing.

Programming is the world’s most profitable way to work. Nothing compares. And the lower the level of programming, the more understanding is needed to construct the function correctly. Thus, we pay those people more. Assembly is one above binary. And you cant program in binary. Or actually you can, but it’s only for kernel patches and upgrades. So, I was lying. Sue me.

They do that in hex. Hexadecimal. 0-F where (F == 15) There are 16 values per hexadecimal code.
It doesn’t condense information but it does come in handy for knowing bytes.

In fact, we use hexdecimal as a shortcut for long numbers. And it is used in Assembly.

So we’ve covered what a variable is, and we’ve covered what a function is.

Now we’ve learned what a hexadecimal is. If you look at a program in a hex editor, you’ll see that there are hexadecimals codes. 

Now, each code means something different. and all it is is one level short of binary. It’s like the high-level (-order) bits and the low-level (-order) of bits. The two halves make a byte. each is 4 bits. Enough to carry 0-16. And we mark everything higher than 9 with A-F.

So each of these is a function of Assembly if you go up a level of programming languages. It is very useful in times of knowing your system. And.. if you’re a ham, then you can know them, and go all punk rock hex programmer on people. But you really gotta know your stuff. So… onward!

I know that the function world seems despot, and that you are limited to functions that are, as we said, “native”, but behind the glass is an entirely capable scalar idea of functions and therefore functionality. It is the PROGRAM!

The program is in itself is the one largest function.

It is given parameters.

It is given execution time.

That is what a function is.

Honestly, if you used one function in your program, like a zip program might do, you would have a program in a program. The executable, the thing you double click or type in, is a shell for the function. That itself is written into the format of the computer you’re on — or else it won’t run.

The format is a way for the OS, a function-filled program, to interpret your program. And each program is run on an interval. Just some important knowledge for you to remember if you need it.

So let’s say this diagram is your operating system. Starting from the application and going down to the actual physics that run the computer, like electricity (basically just electricity circa 2020).


![(copyright unknown)](https://paper-attachments.dropbox.com/s_5940E759C1885786C02E827120AB26F9FF1402A00B6D935241FA6D11A88194C8_1591738488883_layersofabstraction.jpg)


As we can see, the topmost, and the simplest form of work for the program is the application; ideally the window or shell holding the program (application).

Next, the algorithm goes through. That’s our guy! The function!

The programming language then turns it into Assembly Language, which then turns it over to machine code. That’s binary. When the applications essence meets the Instruction Set Architecture (don’t worry this is standard stuff. Nothing has changed in 5 decades.) it reads the code benevolently without a mistake (remember this sentence: the code is wrong, not the machine. Always.).

The Micro Architecture, than allows for these series of bits and bytes to circulate along the system’s hardware. It shoots these through the gates and registers. And finally the transistors which hold the information. Lastly, electricity is the rendered effect. So, if you come up with a computer field that can just read electricity, you’ve got something there. But you’ll likely need it in some format. So you need at least an application written for the transcribing. And then the rest.

(See: Wanted, 2008)


![If you saw this, you know how the electrical field machine would work. (copyright imdb.com)](https://paper-attachments.dropbox.com/s_5940E759C1885786C02E827120AB26F9FF1402A00B6D935241FA6D11A88194C8_1591739412930_wanted.jpg)


Now, if we haven’t gone online, and searched for anything yet on Assembly, there is one big difference in the two major distributions of Assembly. One is called Intel syntax which goes right to left in `mov` calls such as


    mov rdx, [dog]

So, dogs value will be stored in the 64 bit register (the r prefix to the dx register [dx is a single byte]), which is 8 bytes. So it can hold a bit of information, but you can’t hold anything larger than 2 to the 64th power. So sad.

And the AT&T syntax (yes they were around) is left to right. Choose your own adventure. But in this book, as crazy as it seems, we choose to use the Intel syntax.


    mov [dog], rdx

Here, again it does the same thing as the Intel syntax. It’s just reversed in its direction of code. I like Intel because it reminds me of simple things in programming, like having the variable loaded on the left. Like C++ code and C code and so many others.

The registers, which is an inevitable topic at this point are as follows


![The many registers of NASM Assembly (copyright unknown)](https://paper-attachments.dropbox.com/s_5940E759C1885786C02E827120AB26F9FF1402A00B6D935241FA6D11A88194C8_1591741516412_registers.png)


As we can see, there are plenty of registers. And each of these is either a volatile variable (adverb) or a constant (non-volatile) register. This means they will either be changeable throughout, or the latter, changeable in a loop. But they are linear. So if you set a non-volatile before a loop, it will be that until you call either the function again and you’ve set it to something else, or you’ll have to change it before compile-time.

Table of uses:

| Register | 64, 32, 16, 8, 8-bit | Uses                                                 | Preserved                                              |
| -------- | -------------------- | ---------------------------------------------------- | ------------------------------------------------------ |
| bx       | rbx, ebx, bx, bh, bl | non-volatile register                                |                                                        |
| ax       | rax, eax, ax, ah, al | volatile register                                    |                                                        |
| cx       | rcx, ecx, cx, ch, cl | volatile register<br>function parameter #4           |                                                        |
| dx       | rdx, edx, cx, dh, dl | scratch register (anything)<br>function parameter #3 |                                                        |
| di       | rdi, edi, di, dil    | function parameter #1 in Linux; 64 bit only          | preservable (preserved)<br>Needs to be copied out once |
| si       | rsi, esi, si, sil    | function parameter #2 in Linux; 64 bit only          | preservable (preserved)<br>Needs to be copied out once |

| Register | 64, 32, 16, 8-byte    | Normal functionof register | Preserved                                              |
| -------- | --------------------- | -------------------------- | ------------------------------------------------------ |
| r8       | r8, r8d, r8w, r8b     | function parameter #5      |                                                        |
|          | r9, r9d, r9w, r9b     | function parameter #6      |                                                        |
|          | r10, r10d, r10w, r10b |                            |                                                        |
|          | r11, r11d, r11w, r11b |                            |                                                        |
|          | r12, r12d, r12w, r12b |                            | preservable (preserved)<br>Needs to be copied out once |
|          | r13, r13d, r13w, r13b |                            | preservable (preserved)<br>Needs to be copied out once |
|          | r14, r14d, r14w, r14b |                            | preservable (preserved)<br>Needs to be copied out once |
|          | r15, r15d, r15w, r15b |                            | preservable (preserved)<br>Needs to be copied out once |

So now that we’re all acquainted with the normal registers… We can get to know a few more. 😓 

This is a list of the rest of the registers we’ll cover in this book at this time.

![(copyright: Wikipedia.com, https://en.wikipedia.org/wiki/FLAGS_register)](https://paper-attachments.dropbox.com/s_5940E759C1885786C02E827120AB26F9FF1402A00B6D935241FA6D11A88194C8_1591744049848_flags1.PNG)



![(copyright: Wikipedia.com, https://en.wikipedia.org/wiki/FLAGS_register)](https://paper-attachments.dropbox.com/s_5940E759C1885786C02E827120AB26F9FF1402A00B6D935241FA6D11A88194C8_1591744058370_flags2.PNG)


If we wanted to use these flags, we’d learn what’s inside of them and their uses by practicing calling them like in this snippet.


    pushf        ; Use the stack to transfer the FLAGS
    pop ax       ; ...into the AX register
    push ax      ; and copy them back onto the stack for storage
    xor ax, 400h ; Toggle (complement) DF only; other bits are unchanged
    push ax      ; Use the stack again to move the modified value
    popf         ; ...into the FLAGS register
    ; Insert here the code that required the DF flag to be complemented
    popf         ; Restore the original value of the FLAGS

Don’t freak! The Direction Flag (400h as it says in the top table) is not for going up and down! No! Instead of that awful idea, it’s going left-to-right or right-to-left.

As we see, popf turns all the flags back to their safe and original toggle position.

This is the soft approach to programming language creation.

You want the user to be able to return things to their natural state. If you don’t then you won’t have the users to even compile your language. Yes you can make a language with Assembly by creating a compiler. Nothing short of genius will do it though.

BTW, microcontrollers — little boards like Raspberry PI’s and the Arduino and the less popular ones — are all able to be programmed in their firmware, or the chipset. Essentially achieving the Instruction Set Control level of programming. (See diagram of Complexity/Abstraction).


----------

Chapter 3

For the kiddies, we didn’t grow up with Windows, or iOS or OS X on the Mac.

We had to walk next door for the coax cable to be plugged into the computer for us to network.

At the time (Well, after it came around) the internet was beginning. It was acceptable because we didn’t have Fiber Optic lines. I’m not even sure those were around.

We had to remember the script (if we crashed our computers, while hacking them) to dial the number of the local BBS if we weren’t getting on the internet, or we were dialing the internet host. Likely, is was this old company that disintegrated called AOL (America Online).

Life wasn’t about the computer. It was more of a hobby, than a lifestyle. We had Nintendo at best.

That plus comic books. Lots of those. And CDs. Lots of those shiny discs we learned we could put in the microwave for 2 to 5 seconds and blow the hell out of them. But that was only blank discs, which were of course unheard of at the turning of the 80s to the 90s. Wait…

I had tape cassettes at that time. Man, the internet is awesome!!!

Anyway, my point is, we have to move forward. All of this will be old hat in 20 years. We won’t have what we have, and we’ll soon be in our escape pods as MegaMaid explodes. But don’t worry, there’s one here for you.

Assembly programming will not go away. If it does, it’s because we aren’t using chips anymore. Right now, the only thing we or you in 20 years, will know now of will be microchips made of titanium and programmed graphene. These chips of course will be the best in the world. And you can’t run them without electrons. So we’re forced to use what we have now, anyway.


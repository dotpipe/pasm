<?php

//namespace src\pasm;

class PASM
{
    public static $ZF = 0;    // Comparison Flag for Exchanges
    public static $OF = 0;    // Overflow Flag
    public static $CF = 0;    // Carry Flag
    public static $counter = 0;   // Needed for loops
    public static $chain = array();   // Chain of events in line use PASM::$end() to stop and start again
    public static $args = array();    // Array to hold args for set variables
    public static $stack = array();    // Stack
    public static $array = array();    // array for stack formations
    public static $sp;         // Stack pointer
    public static $ST0;        // LAST STACK ELEMENT
    public static $pdb = 1;    // debug flag (DEFAULT FALSE)
    // The stack is referenced under objects
    // The pairing, is to be justified, with
    // a simulacrum between the object and the
    // variable. Inside and out. This is an image.
    // You may make apps out of it. You may make
    // such PHP wrappers as to create formidable
    // language enhancements and have a fast,
    // easy to see connection to low level speed
    public static $tp;     // holder for current bit
    public static $ecx;    // RHS, DECR, INC, COMPARATOR
    public static $adx;    // Registers
    public static $bdx;    //
    public static $cdx;    //
    public static $ddx;    //
    public static $edx;    //
    public static $bitcmp; // TRUE or FALSE comparator bit
    public static $ah;     // LHS, COMPARATOR
    public static $ldp;    // The amount of commands to go back in loops and jmps
    public static $rdx;    // Holds answers to addition, and other math
    public static $qword;  // String Register
    public static $RC;     // Round to this decimal
    public static $wait;   // wait variable
    public static $strp;   // string pointer
    public static $cl;    // BOOL ANSWER FOR JMP
    public static $string; // STRING register
    public static $lop;   // Place in $chain
    public static $err;    // Error No.
    public static $err_str;    // Error String
    public static $jbl;     // deprecated
    public static $buffer;  // socket buffer
    public static $output;  // output for run_pop and similar
    public static $cnt;     // Dedicated to counting for external arrays
    public static $FUNC0;   // Top of function stack

    
    /**
     *	
     *
     * 
     */
    public static function runTest()
    {    // Useful for some testing
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];// Will be easier to just play around
        // However this verifies all methods work
        foreach (get_class_methods("PHP") as $method) {
            if ($method == "runTest") {
                continue;
            }
            $r = new \ReflectionMethod("PASM", $method);
            $params = $r->getParameters();
            $results = [];
            $p = [];
            foreach ($params as $param) {
                //$param is an instance of ReflectionParameter
                $p[] = $param->getName();
                $results = $p;
                        //echo $param->isOptional();
            }
                $x = new PASM();
            $y = "$" . implode(',$', $results);
            try {
                PASM::$ecx = 3;
                PASM::$ah = 3;
                PASM::$method();
                continue;
            } catch (\Exception $e) {
                PASM::$ecx = 2;
                PASM::$ah = 3;
                PASM::$method();
                continue;
            } finally {
                PASM::$ecx = [2];
                PASM::$ah = [3];
                PASM::$method();
            }
        }
    }

    /**
     *	
     * get retrieves the given string as a variable
     * 
     */
    public static function get(string $var = "ah")
    {
        yield PASM::${$var};
        return new static;
    }

    
    /**
     *	
     * var_p prints the given string as a variable
     * 
     */
    public static function var_p(string $var = "ah")
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];
                PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
        echo PASM::${$var};
        return new static;
    }

    /**
     *	
     * char_adjust_addition changes $rdx to 8 bits
     * 
     */
    public static function char_adjust_addition()
    {
        PASM::setup_chain(__METHOD__);
        PASM::$rdx = chr((PASM::$ecx + PASM::$ah)%256);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * carry_add sets the $cl flag for carrying over on addition commands
     * 
     */
    public static function carry_add()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$cl = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * add uses addition to form $rdx from $ecx annd $ah
     * 
     */
    public static function add()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx = PASM::$ecx + PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * and changes the $cl flag to the $ecx & $ah answer
     * 
     */
    public static function and()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$cl = PASM::$ecx & PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * chmod uses $string as the first parameter
     * and $ah as the second param for PHP's native chmod()
     * 
     */
    public static function chmod()
    {
        PASM::setup_chain(__METHOD__);

        chmod(PASM::$string, PASM::$ah);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * bit_scan_fwd if $tp is null at current() it breaks up $qword into
     * portions according to '1's from a binary string
     * derived from the $qword and resets the $tp variable to the beginning.
     * 
     * otherwise it will goto the next $tp in the array
     * 
     */
    public static function bit_scan_fwd()
    {
        PASM::setup_chain(__METHOD__);

        if (PASM::$tp == null) {
            PASM::$tp = PASM::$qword;   // qword is used to look through a string via bit scanning
            PASM::$tp = decbin(PASM::$tp);
            PASM::$tp = str_split(PASM::$tp, 1);
            reset(PASM::$tp);
            return new static;
        }
        next(PASM::$tp);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * bit_scan_rvr if $tp is null at current() it breaks up $qword into
     * portions according to '1's from a binary string
     * derived from the $qword and resets the $tp variable to the end.
     * 
     * otherwise it will goto the previous $tp in the array
     * 
     */
    public static function bit_scan_rvr()                  // reverse of above
    {
        PASM::setup_chain(__METHOD__);

        if (PASM::$tp == null) {
            PASM::$tp = PASM::$qword;
            PASM::$tp = decbin(PASM::$tp);
            PASM::$tp = str_split(PASM::$tp, 1);
            end(PASM::$tp);
            return new static;
        }
        prev(PASM::$tp);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * byte_rvr will reverse the given $ecx in binary
     * then set it as $rdx
     * 
     */
    public static function byte_rvr()                  // reverse byte
    {
        PASM::setup_chain(__METHOD__);

        $temp = decbin(PASM::$ecx);
        PASM::$rdx = strrev($temp);
        PASM::$rdx = bindec(PASM::$rdx);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * bit_test sets $bitcmp to the $ah'th bit
     * just on or off
     * 
     */
    public static function bit_test()                  // bit is filled in pointer
    {
        PASM::setup_chain(__METHOD__);

        PASM::$bitcmp = PASM::$tp[PASM::$ah]

        return new static;
    }

    
    /**
     *	
     * bit_test_comp search thru arbitry $ecx for the $ahth bit
     * and set $CF to it's returned value
     * 
     * if given anything returned as true for a parameter, it will also set the $bitcmp flag
     * 
     */
    public static function bit_test_comp(bool $bitc = false)         // look thru byte and see the $ah'th bit
    {
        PASM::setup_chain(__METHOD__);

        $bo = decbin(PASM::$ecx);
        $bo = $bo[PASM::$ah];
        PASM::$CF = (bool)($bo);
        PASM::$bitcmp = ($bitc) ? PASM::$CF : false;
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * bit_test_comp search thru arbitry $ecx for the $ahth bit
     * and set $CF to it's returned value reset $ecx to 0
     * 
     */
    public static function bit_test_reset()    // Clear bit (ah) test flag
    {
        PASM::setup_chain(__METHOD__);

        $bo = decbin(PASM::$ecx);
        $bo = $bo[PASM::$ah];
        PASM::$CF = (bool)($bo);
        PASM::$ecx = 0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * bit_test_comp search thru arbitry $ecx for the $ahth bit
     * and set $CF to it's returned value and ecx[$ah] to 1
     * 
     */
    public static function bit_test_set()                  // Test bit
    {
        PASM::setup_chain(__METHOD__);

        $bo = decbin(PASM::$ecx);
        $bo = $bo[PASM::$ah];
        PASM::$CF = (bool)($bo);
        PASM::$ecx[PASM::$ah] = 1;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * call is used to call a function from the stack
     * 
     */
    public static function call() // call top of stack function
    {
        PASM::setup_chain(__METHOD__);

        if (is_callable(PASM::$FUNC0)) {
            return call_user_func_array(PASM::$FUNC0(), PASM::$string);
        }
        return new static;
    }

    
    /**
     *	
     * cmp_mov_a compares the top of the stack '>' to $ah for true
     * changes $ecx to $ah if $ah is
     * 
     */
    public static function cmp_mov_a()         // check ah against top of stack
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx = (PASM::$ah > PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_mov_ae compares the top of the stack '>=' to $ah for true
     * changes $ecx to $ah if $ah is
     * 
     */
    public static function cmp_mov_ae()    // same (documenting will continue below)
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx = (PASM::$ah >= PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_mov_b compares the top of the stack '<' to $ah for true
     * changes $ecx to $ah if $ah is 
     * 
     */
    public static function cmp_mov_b()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx = (PASM::$ah < PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_mov_be compares the top of the stack '<=' to $ah for true
     * changes $ecx to $ah if $ah is 
     * 
     */
    public static function cmp_mov_be()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx = (PASM::$ah <= PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_mov_e compares the top of the stack '==' to $ah for true
     * changes $ecx to $ah if $ah is
     * 
     */
    public static function cmp_mov_e()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx = (PASM::$ah == PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_mov_nz compares the top of the stack '==' to $ah for true
     * and $CF needs to be equal to that annswer
     * changes $ecx to $ah if it is
     * 
     */
    public static function cmp_mov_nz()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx = (PASM::$CF == 1 & PASM::$ah == PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_mov_pe compares the top of the stack '<' to $ah for true
     * changes $ecx to $ah if $ah is higher 
     * 
     */
    public static function cmp_mov_pe()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx = (PASM::$CF == 0) ? PASM::$ah : PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_mov_po compares 1 '==' to $CF for true
     * changes $ecx to $ah if $ah is
     * 
     */
    public static function cmp_mov_po()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx = (PASM::$CF == 1) ? PASM::$ah : PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_mov_s compares 0 '<' to $ah for true
     * changes $ecx to $ah if $ah is
     * 
     */
    public static function cmp_mov_s()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx = (PASM::$ah < 0) ? PASM::$ah : PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_mov_a compares 0 '>' to $ah for true
     * changes $ecx to $ah if $ah is
     * 
     */
    public static function cmp_mov_z()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx = (PASM::$ah > 0) ? PASM::$ah : PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * mov copies $ah to $ecx
     * 
     */
    public static function mov()   // move ah to ecx. Same as mov_ah()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * movabs pushes the current $ecx to $stack['movabs']
     * then moves the ST0 pointer to the end of the stack
     * 
     */
    public static function movabs()    // copy $ecx to stack
    {
        PASM::setup_chain(__METHOD__);

        array_push(PASM::$stack, array("movabs" => PASM::$ecx));
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * clear_carry sets CF to 0
     * 
     */
    public static function clear_carry()   // clear $CF
    {
        PASM::setup_chain(__METHOD__);

        PASM::$CF = 0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * clear_registers clears all native PASM registers and $CF bit to 0
     * 
     */
    public static function clear_registers()   // make all registers 0
    {
        PASM::setup_chain(__METHOD__);

        PASM::$CF = PASM::$adx = PASM::$bdx = PASM::$cdx = PASM::$ddx = PASM::$edx = PASM::$rdx = 0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * comp_carry negates the current status of $CF
     * 
     */
    public static function comp_carry()    // negate $CF
    {
        PASM::setup_chain(__METHOD__);

        PASM::$CF = !(PASM::$CF);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_e compares '==' $ecx to $ah and sets the $cl field accordingly
     * 
     */
    public static function cmp_e()         // bool of equality comparison (documentation continues below)
    {
        PASM::setup_chain(__METHOD__);

        PASM::$cl = PASM::$ecx == PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_same compares '==' $ecx to $ah and sets the $cl field accordingly
     * 
     */
    public static function cmp_same()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$cl = PASM::$ecx == PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * cmp_xchg compares '==' $ecx to $ah and sets the $ZF field accordingly
     * as well, it switches $rdx and $ah
     * 
     */
    public static function cmp_xchg()
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ZF = PASM::$ecx == PASM::$ah;
        list(PASM::$rdx, PASM::$ah) = array(PASM::$ah, PASM::$rdx);
        return new static;
    }


    
    /**
     *
     * switches $rdx and $ah
     * 
     */
    public static function xchg(&$x, &$y)
    {
        PASM::setup_chain(__METHOD__);
        list(PASM::$rdx, PASM::$ah) = array(PASM::$ah, PASM::$rdx);
        return new static;
    }

    
    /**
     *	
     * decr decrements any variable via string (ie 'rdx')
     * one integer value 
     * 
     */
    public static function decr(string $var = "ecx")                  // decrement ecx
    {
        PASM::setup_chain(__METHOD__);

        PASM::${$var}--;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * divide $ecx by $ah
     * 
     */
    public static function divide()    // $ecx/$ah
    {
        PASM::setup_chain(__METHOD__);

        if (is_numeric(PASM::$ecx) && is_numeric(PASM::$ah)) {
            PASM::$rdx = round(PASM::$ecx/PASM::$ah);
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * absf sets $rdx to the absolute value of ah
     * 
     */
    public static function absf()                  // absolute value of $ah
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx = abs(PASM::$ah);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * addf uses addition of $ecx and $ah to set $rdx
     * 
     */
    public static function addf()                  // add $ecx and $ah
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx = PASM::$ecx + PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    /**
     *	
     * addc uses addition of $ecx and $ah and current $rdx to set $rdx
     * 
     */
    public static function addc()                  // add $ecx and $ah
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx += PASM::$ecx + PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * round uses the PHP native function round() with $ST0
     * and the number in $RC for the number of decimal places
     * to set the $stack
     * 
     */
    public static function round()         // round top stack to RC decimal
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::$ST0 = &PASM::$stack[array_key_last(PASM::$stack)];
        PASM::$ST0 = round(PASM::$ST0, PASM::$RC);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * round_pop uses the PHP native function round() with $ST0
     * and the number in $RC for the number of decimal places
     * to set the $ah register and pops the stack once
     * it then sets $ST0 to the last element in the $stack
     * 
     */
    public static function round_pop()         // same but pop
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::$ah = &PASM::$stack[array_key_last(PASM::$stack)];
        PASM::$ah = round(PASM::$ST0, PASM::$RC);
        array_pop(PASM::$stack);
        if (count(PASM::$stack) > 0) {
            PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        } else {
            PASM::$ST0 = null;
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * neg inverts a positive number to a positive number
     * or the reverse action, depending on the value. $ah
     * is multiplied by -1 to set $rdx to the answer
     * 
     */
    public static function neg()   // negate $ah
    {
        PASM::setup_chain(__METHOD__);

        if (is_numeric(PASM::$ah)) {
            PASM::$rdx = PASM::$ah * (-1);
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * stack_cmov_b compares $ST0 '>' to $ah
     * if true, it makes $rdx the value in $ah
     * 
     */
    public static function stack_cmov_b()                  // move on comparison (begins again below)
    {
        PASM::setup_chain(__METHOD__);

        if (count(PASM::$stack) > 0) {
            PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        } else {
            PASM::$ST0 = null;
        }
        if (PASM::$ST0 != null && PASM::$ah < PASM::$ST0) {
            PASM::$rdx = PASM::$ah;
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * stack_cmov_be compares $ST0 '>=' to $ah
     * if true, it makes $rdx the value in $ah
     * 
     */
    public static function stack_cmov_be()
    {
        PASM::setup_chain(__METHOD__);

        if (count(PASM::$stack) > 0) {
            PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        } else {
            PASM::$ST0 = null;
        }
        if (PASM::$ST0 != null && PASM::$ah <= PASM::$ST0) {
            PASM::$rdx = PASM::$ah;
        }
        return new static;
    }

    
    /**
     *	
     * stack_cmov_e compares $ST0 '==' to $ah
     * if true, it makes $rdx the value in $ah
     * 
     */
    public static function stack_cmov_e()
    {
        PASM::setup_chain(__METHOD__);

        if (count(PASM::$stack) > 0) {
            PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        } else {
            PASM::$ST0 = null;
        }
        if (PASM::$ST0 != null && PASM::$ah == PASM::$ST0) {
            PASM::$rdx = PASM::$ah;
        }
        return new static;
    }

    
    /**
     *	
     * stack_cmov_nb compares $ST0 '<' to $ah
     * if true, it makes $rdx the value in $ah
     * 
     */
    public static function stack_cmov_nb()
    {
        PASM::setup_chain(__METHOD__);

        if (count(PASM::$stack) > 0) {
            PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        } else {
            PASM::$ST0 = null;
        }
        if (PASM::$ST0 != null && PASM::$ah > PASM::$ST0) {
            PASM::$rdx = PASM::$ah;
        }
        return new static;
    }

    
    /**
     *	
     * stack_cmov_nbe compares $ST0 '<=' to $ah
     * if true, it makes $rdx the value in $ah
     * 
     */
    public static function stack_cmov_nbe()
    {
        PASM::setup_chain(__METHOD__);

        if (count(PASM::$stack) > 0) {
            PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        } else {
            PASM::$ST0 = null;
        }
        if (PASM::$ST0 != null && PASM::$ah >= PASM::$ST0) {
            PASM::$rdx = PASM::$ah;
        }
        return new static;
    }

    
    /**
     *	
     * stack_cmov_b compares $ST0 '!=' to $ah
     * if true, it makes $rdx the value in $ah
     * 
     */
    public static function stack_cmov_ne()
    {
        PASM::setup_chain(__METHOD__);

        if (count(PASM::$stack) > 0) {
            PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        } else {
            PASM::$ST0 = null;
        }
        if (PASM::$ST0 != null && PASM::$ah != PASM::$ST0) {
            PASM::$rdx = PASM::$ah;
        }
        return new static;
    }

    
    /**
     *	
     * fcomp substracts the $ST0 stack pointer
     * from $ah and pops its last value off
     * 
     */
    public static function fcomp()         // subtract top of stack from $ah and pop
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ah) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return new static;
        }
        PASM::$rdx = PASM::$ah - PASM::$stack[array_key_last(PASM::$stack)];
        array_pop(PASM::$stack);
        if (count(PASM::$stack) > 0) {
            PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        } else {
            PASM::$ST0 = null;
        }
        if (PASM::$ST0 != null && PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }
        return new static;
    }

    
    /**
     *	
     * cosine sets the $ST0 pointer to the current $ST0
     * wrapped in the PHP native mathematical function, cosine (cos)
     * 
     */
    public static function cosine()    // change top of stack to cosine of top of stack
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ST0 = &PASM::$stack[array_key_last(PASM::$stack)];
        PASM::$ST0 = (PASM::$ST0 != null) ? cos(PASM::$ST0) : null;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * stack_pnt_rev goes thru the stack backward,
     * in reverse, and sets the $sp variable to its position
     * 
     */
    public static function stack_pnt_rev()         // go traverse the stack backward
    {
        PASM::setup_chain(__METHOD__);

        prev(PASM::$stack);
        PASM::$sp = current(PASM::$stack);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fdiv divides $ecx by $ST0, the stack's last entry
     * setting the value of $rdx
     * 
     */
    public static function fdiv()                  // divide ST0 into $ecx
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ecx) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return new static;
        }
        PASM::$rdx = PASM::$ecx / PASM::$ST0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fdiv_pop divides $ST0 by $ecx and sets the last element to $ST0 again
     * 
     */
    public static function fdiv_pop()                  // opposite as above and pop
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx = PASM::$ST0 / PASM::$ecx;
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fdiv_rev divides $ST0 by $ecx and sets the value at $rdx
     * 
     */
    public static function fdiv_rev()                  // opposite of fdiv
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx = PASM::$ST0 / PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fdiv_rev divides $ecx by $ST0 and sets the value at $rdx
     * 
     */
    public static function fdiv_rev_pop()                  // same as above with po
    {
        PASM::setup_chain(__METHOD__);

        if (PASM::$ST0 == 0) {
            echo "Denominator cannot be 0";
            PASM::$cl = 0;
            return new static;
        }
        PASM::$rdx = PASM::$ecx / PASM::$ST0;
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * add_stack uses addition to set $rdx
     * with the value of $ecx + $ST0
     * 
     */
    public static function add_stack()         // add top of stack to ecx
    {
        PASM::setup_chain(__METHOD__);

        if (count(PASM::$stack) == 0 || !PASM::$stack[array_key_last(PASM::$stack)]) {
            exit("Stack is empty at " . PASM::$lop);
        }
        PASM::$rdx = PASM::$ecx + PASM::$ST0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * ficomp uses comparison of $ST0 '==' to $ah
     * and sets the bit $cl with it
     * Then it pops the stack and reissues the last
     * element to $ST0
     * 
     */
    public static function ficomp()    // compare and pop
    {
        PASM::setup_chain(__METHOD__);

        if (PASM::$ST0 == PASM::$ah) {
            PASM::$cl = 1;
        }
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    /**
     *	
     * s_ref_ptr creates a referenced variable fro the $ST0 pointer
     * to the tail end of the stack in case you plan on changing it a lot
     * 
     */
    public static function s_ref_ptr()
    {
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        PASM::$stack[array_key_last(PASM::$stack)] = &PASM::$ST0;
        return new static;
    }

    /**
     *	
     * recvr_stack recover contents of the stack from the filename given as a parameter
     * 
     */
    public static function recvr_stack(string $filename)
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!file_exists($filename)) {
            return false;
        }
        PASM::$stack = (unserialize(file_get_contents($filename)));
        return new static;
    }

    
    /**
     *	
     * stack_load uses $key so it's indexable by string coefficient
     * 'fc' so you can have the count of the reference rather than
     * name it everytime
     * 
     */
    public static function stack_load() // stack with count on stack
    {
        PASM::setup_chain(__METHOD__);

        $key = "fc" . count(PASM::$stack);
        array_push(PASM::$stack, array($key => PASM::$ecx));
        PASM::$ecx = null;
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * stack_mrg uses $key so it's indexable by string coefficient
     * 'fc' so you can have the count of the reference rather than
     * name it everytime, all of this while merging native $array and $stack
     * 
     */
    public static function stack_mrg() // stack with count on stack
    {
        PASM::setup_chain(__METHOD__);

        $key = "fc" . count(PASM::$stack);
        array_merge(PASM::$stack, PASM::$array);
        PASM::$ecx = null;
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fmul multiplies $ecx by $ah giving $rdx it's value
     * 
     */
    public static function fmul()                  // multiplies ecx and ah
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ah) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return new static;
        }
        PASM::$rdx = PASM::$ecx * PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * stack_pnt_fwd moves the $sp (stack pointer) forward, one iteration
     * 
     */
    public static function stack_pnt_fwd()         // moves stack pointer forward
    {
        PASM::setup_chain(__METHOD__);

        next(PASM::$stack);
        PASM::$sp = current(PASM::$stack);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * store_int subtracts by $ST0 - 2^$ah and sets $rdx to the value
     * 
     */
    public static function store_int()         // subtracts $ST0 - 2-to-the-$ah and puts answer in $rdx
    {
        PASM::setup_chain(__METHOD__);

        if (ctype_xdigit(PASM::$stack[array_key_last(PASM::$stack)])) {
            PASM::$ST0 = hexdec(PASM::$stack[array_key_last(PASM::$stack)]);
        }
        if (is_numeric(PASM::$stack[array_key_last(PASM::$stack)])) {
            PASM::$ST0 = decbin(PASM::$stack[array_key_last(PASM::$stack)]);
        }
        $test = rtrim(PASM::$ST0, "01");
        if (strlen($test) == 0) {
            PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        } else {
            return;
        }
        if (is_numeric(PASM::$ah)) {
            PASM::$rdx = PASM::$ST0 - pow(2, 8*PASM::$ah);
        } else {
            echo "\$ah is not in numeric form";
            return;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * store_int subtracts by $ST0 - 2^$ah and sets $rdx to the value
     * and pops the last value, then resets the $ST0 pointer to the last entry
     * 
     */
    public static function store_int_pop() // same as above, but with pop
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$stack[array_key_last(PASM::$stack)])) {
            echo "Invalid Operand in store_int_pop: \$ah = " . PASM::$ah . " & " . PASM::${$ST0} . " = " . PASM::$stack[array_key_last(PASM::$stack)];
            return;
        }
        if (ctype_xdigit(PASM::$stack[array_key_last(PASM::$stack)])) {
            PASM::$ST0 = hexdec(PASM::$stack[array_key_last(PASM::$stack)]);
        }
        if (is_numeric(PASM::$stack[array_key_last(PASM::$stack)])) {
            PASM::$ST0 = decbin(PASM::$stack[array_key_last(PASM::$stack)]);
        }
        $test = rtrim(PASM::$ST0, "01");
        if (strlen($test) == 0) {
            PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        } else {
            return;
        }
        if (is_numeric(PASM::$ah)) {
            PASM::$rdx = PASM::$ST0 - pow(2, 8*PASM::$ah);
        } else {
            echo "\$ah is not in numeric form";
            return;
        }
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * substract_rev subtracts $ah from $ech and places the
     * value in $rdx
     * 
     */
    public static function subtract_rev() // like subtract but backwards
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$ecx) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return;
        }
        PASM::$rdx = PASM::$ecx - PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * substract substracts $ecx from $ah
     * and puts the value in $rdx
     * 
     */
    public static function subtract()  // $ah - $ecx
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$ecx) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return;
        }
        PASM::$rdx = PASM::$ah - PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fld1 pushes ecx+1 to the stack
     * 
     */
    public static function fld1()  // pushes ecx+1 to stack
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ecx)) {
            return;
        }
        array_push(PASM::$stack, array("inc" => (PASM::$ecx + 1)));
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * load_logl2 pushs log(log(2)) to the stack
     * 
     */
    public static function load_logl2() //
    {
        PASM::setup_chain(__METHOD__);

        array_push(PASM::$stack, array("logl2" => log(log(2))));
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * load_logl2t pushes log(2,10)
     * 
     */
    public static function load_logl2t()
    {
        PASM::setup_chain(__METHOD__);

        array_push(PASM::$stack, array("logl2t" => log(2, 10)));
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * load_loglg2 pushes log(2, log($ah)) to the stack
     * 
     */
    public static function load_loglg2()
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ah)) {
            echo "\$ah must be numeric for load_loglg2";
            return;
        }
        array_push(PASM::$stack, array("loglg2" => log(2, log(PASM::$ah))));
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * load_ln2 pushes log(e, 2) to the stack
     * 
     */
    public static function load_ln2()
    {
        PASM::setup_chain(__METHOD__);

        $e = M_E;
        array_push(PASM::$stack, array("ln2" => log($e, 2)));
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * load_pi pushes 3.14159... to the stack
     * 
     */
    public static function load_pi()
    {
        PASM::setup_chain(__METHOD__);

        array_push(PASM::$stack, array("pi" => M_PI));
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * float_test recreates $ah as a decimal in $rdx
     * 
     */
    public static function float_test()
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ah)) {
            echo "\$ah must be numeric for float_test";
            return;
        }
        PASM::$rdx = PASM::$ah + 0.0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fmul_pop multiplies $ah and $ecx as $rdx
     * pops the stack and puts the top as $ST0
     * 
     */
    public static function fmul_pop() // ah * ecx and pop
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ah) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return;
        }
        PASM::$rdx = PASM::$ah * PASM::$ecx;
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * clean_exceptions clears the $ZF bit
     * 
     */
    public static function clean_exceptions()  // clear exception bit
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ZF = 0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * clean_reg claers the $cl bit
     * 
     */
    public static function clean_reg() // clear cl
    {
        PASM::setup_chain(__METHOD__);

        PASM::$cl = 0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fnop counts as a function, but does absolutely nothing
     * its there because people want hackers in their stuff i guess
     * 
     */
    public static function fnop()  // counts as function, does nothing but takes up space (like in assembly)
    {
        PASM::setup_chain(__METHOD__);


        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fpatan puts the value of arctan($ah) in the $cl flag
     * 
     */
    public static function fpatan()    // gets arctan of $ah
    {
        PASM::setup_chain(__METHOD__);


        PASM::$cl = atan(PASM::$ah);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fptan puts the value of tan($ah) in the $cl flag
     * 
     */
    public static function fptan() // gets tangent of ah
    {
        PASM::setup_chain(__METHOD__);

        PASM::$cl = tan(PASM::$ah);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fprem divide the top of the stack by the next down
     * 
     */
    public static function fprem() // look to documentation (Oracle Systems Manual)
    {
        PASM::setup_chain(__METHOD__);

        if (count(PASM::$stack) > 1) {
            $temp_sp = end($stack);
            $before = current($temp_sp);
            $temp_sp = prev($stack);
            $after = current($temp_sp);

            PASM::$ecx = $before / $after;
        }
        else
            exit("Programming error: " . PASM::$lop . " count is at fault");
        PASM::$rdx = (round(PASM::$ecx, (PASM::$RC+1)) - (PASM::$ecx*10*(PASM::$RC+1)))/10/(1+PASM::$RC);
        PASM::$cl = 0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * frndint rounds the top of the stack, with the remaining $RC decimals
     * and puts it into $rdx
     * 
     */
    public static function frndint()   // round top of stack into $rdx
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ecx) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            exit("\$ecx or \$ST0 is not numeric");
        }
        PASM::$rdx = round(PASM::$stack[array_key_last(PASM::$stack)], PASM::$RC);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * frstor copies $ah to $rdx
     * 
     */
    public static function frstor() // copy $ah to $rdx
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fsin stores the sin of $ST0 in the final element of $stack
     * wherein it was retrieved from.
     * 
     */
    public static function fsin() // change top of stack to sin of top of stack
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::$ST0 = &PASM::$stack[array_key_last(PASM::$stack)];
        PASM::$ST0 = (PASM::$ST0 != null) ? sin(PASM::$ST0) : null;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fsincos pushes cosine of $ST0 to stack and applies sine to it
     * 
     */
    public static function fsincos() // push cos of $ST0 to stack and fill $ST0 with sin of itself
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        array_push(PASM::$stack, array("cos" => cos(PASM::$ST0)));
        PASM::$sp = end(PASM::$stack);
        PASM::$ST0 = &PASM::$stack[array_key_last(PASM::$stack)];
        PASM::$ST0 = (PASM::$sp != null) ? sin(PASM::$ST0) : null;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fscale rounds the top 2 elements of the $stack array
     * in separate variables
     * 
     */
    public static function fscale()    // round top 2 stack elements and push to rdx ans powers of 2
    {
        PASM::setup_chain(__METHOD__);
        end(PASM::$stack);
        $spa = prev(PASM::$stack);
        $sp1 = round(current(PASM::$stack));
        $spb = next(PASM::$stack);
        $sp0 = current(PASM::$stack);
        if (!is_numeric($sp1) || !is_numeric($sp0)) {
            echo "Top 2 stack registers must be numeric for fscale()";
            return;
        }
        PASM::$rdx = pow(2, $sp0+$sp1);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fsqrt push top of stack onto stack as the square root of it
     * 
     */
    public static function fsqrt() // push to stack top value's sqrt
    {
        PASM::setup_chain(__METHOD__);

        PASM::$stack[array_key_last(PASM::$stack)] = sqrt(PASM::$stack[array_key_last(PASM::$stack)]);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fst copies ST0 to a position at $ecx from the back
     * if it is a negative number, and forward if positive
     * 
     */
    public static function fst() // copy ST0 to another position ($ecx)
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        $pn = abs($ecx);
        ($ecx > 0) ? reset(PASM::$stack) : end(PASM::$stack);
        while ($pn > 0 && $ecx < 0)
        {
            prev(PASM::$stack);
            $pn--;
        }
        while ($pn > 0 && $ecx > 0)
        { 
            next(PASM::$stack);
            $pn--;
        }
        $temp = &current(PASM:$stack);
        
        $temp = PASM::$ST0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fstcw copies $ah to $rdx
     * 
     */
    public static function fstcw() // copy  $ah to $rdx
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fstp 
     * 
     */
    public static function fstp()  // same as fst() but pops
    {
        PASM::setup_chain(__METHOD__);

        PASM::fst();
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * subtract_pop subtract $ST0 from $ah and place value in $rdx
     * then pop the $stack
     * 
     */
    public static function subtract_pop()  // like it says ($ah - $ST0)
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$stack[array_key_last(PASM::$stack)])) {
            echo "\$ST0 & \$ah must be numeric for subtract_pop";
            return;
        }
        PASM::$rdx = PASM::$ah - (PASM::$ST0);
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * subtract_rev_pop subtract $ah from $ST0 then pop the $stack
     * 
     */
    public static function subtract_rev_pop() // (same only reverse)
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$stack[array_key_last(PASM::$stack)])) {
            echo "\$ST0 & \$ah must be numeric for subtract_rev_pop";
            return;
        }
        PASM::$rdx = PASM::$ST0 - PASM::$ah;
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * ftst see if float is possible type
     * 
     */
    public static function ftst()  // check that math works
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$rdx)) {
            echo "\$rdx must be numeric for ftst";
            return;
        }
        PASM::$rdx -= 0.0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     * fucom copies $sp to $ecx and $ST0 to $rdx
     * 
     */
    public static function fucom() // ecx == $sp and $rdx = $ST0
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ecx) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return new static;
        }
        if (!is_float(PASM::$stack[PASM::$ecx]) || !is_float(PASM::$ST0)) {
            PASM::$CF = 7;
        }
        PASM::$ecx = PASM::$sp;
        PASM::$rdx = PASM::$ST0;
        if (0 != (PASM::$ecx - PASM::$ah)) {  // Now derive carry flag
            PASM::$CF = (PASM::$ecx < PASM::$ah) ? 0 : 1;
        } else {
            PASM::$CF = 4;
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function fucomp()    // above ith pop
    {
        PASM::setup_chain(__METHOD__);

        PASM::fucom();
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function fucompp()   // above with another pop
    {
        PASM::setup_chain(__METHOD__);

        PASM::fucom();
        array_pop(PASM::$stack);
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function fxam()  // get decimal value, without integer
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ah) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return new static;
        }
        PASM::$rdx = PASM::$ah - round(PASM::$ah);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function fxch()  // exchange values from one stack place to another (the top)
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ST0 = &PASM::$stack[array_key_last(PASM::$stack)];
        $temp = PASM::$stack[PASM::$ecx];
        PASM::$stack[PASM::$ecx] = PASM::$ST0;  // goes into PASM::$ecx
        PASM::$ST0 = $temp;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function fxtract()   // get highest significand and exponent of number
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$stack[array_key_last(PASM::$stack)])) {
            echo "\$ST0 & \$ah must be numeric for fxtract";
            return;
        }
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        $ot = PASM::$ST0;
        $t = 1;
        PASM::$ah = PASM::$ST0;
        $significand = 0;
        $exponent = 0;
        $worked = "";
        while (0 < $ot) {
            $t = PASM::$ah;
            while ($t > 0) {
                $exponent = $t;
                $significand = $ot;
                if (PASM::$ah == pow($significand, $exponent)) {
                    $temp_sig = $significand;
                    $temp_exp = $exponent;
                }
                $t -= 1;
            }
            $ot -= 1;
        }
        PASM::$ST0 = $exponent;
        PASM::$stack[array_key_last(PASM::$stack)] = $significand;
        array_push(PASM::$stack, array("exp" => $exponent));
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function fyl2x()
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ecx) || !is_numeric(PASM::$ah)) {
            return new static;
        }
        PASM::$rdx = PASM::$ecx * log(PASM::$ah, 2);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function fyl2xp1()
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ecx) || !is_numeric(PASM::$ah)) {
            return new static;
        }
        PASM::$rdx = PASM::$ecx * log(PASM::$ah, 2 + 1);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function hlt(string $async_filename, string $signal = null)
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        // Push the "signal" variable into the $async_filename(.json)
        // If it is anything but the "signal", it will stay halted
        // Use SESSID or cURL to change the file. (Remote Hosted)
        // Being Deprecated
        go_again:
            usleep(2500);
        try {
            $async = file_get_contents($async_filename);
            $async = json_decode($async);
        } catch (\Exception $e) {
        }
        if ($async->signal != $signal) {
            goto go_again;
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function idiv()  // divide $ah / $ecx
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ecx) || !is_numeric(PASM::$ah)) {
            return new static;
        }
        PASM::$rdx = PASM::$ah / PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function imul()  // $ah * $ecx
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ecx) || !is_numeric(PASM::$ah)) {
            return new static;
        }
        PASM::$rdx = PASM::$ah * PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function in()    // $string is server, collects in $buffer
    {
        PASM::setup_chain(__METHOD__);

        $socket = stream_socket_server(PASM::$string, $err, $err_str);
        if (!$socket) {
            echo "PASM::$err (PASM::$err_str)<br />\n";
            PASM::$cl = 0;
            return new static;
        } else {
            while ($conn = stream_socket_accept($socket)) {
                PASM::add_to_buffer(fread($conn, 1000));
                fclose($conn);
            }
            fclose($socket);
        }
        PASM::$cl = 1;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function inc()   // increment $ecx
    {
        PASM::setup_chain(__METHOD__);

        if (!is_numeric(PASM::$ecx)) {
            return new static;
        }
        PASM::$ecx++;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function in_b()  // read 1 byte at a time
    {
        PASM::setup_chain(__METHOD__);

        if (!is_string(PASM::$string) || 0 == count(PASM::$string)) {
            echo "\$string must be numeric for in_b";
            return;
        }
        $socket = stream_socket_server(PASM::$string, $err, $err_str);
        if (!$socket) {
            echo "PASM::$err (PASM::$err_str)<br />\n";
            PASM::$cl = 0;
            return new static;
        } else {
            while ($conn = stream_socket_accept($socket)) {
                PASM::add_to_buffer(fread($conn, 1));
                fclose($conn);
            }
            fclose($socket);
        }
        PASM::$cl = 1;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function in_d() // read 1 dword at a time
    {
        PASM::setup_chain(__METHOD__);

        if (!is_string(PASM::$string) || 0 == count(PASM::$string)) {
            echo "\$string must be numeric for in_d";
            return;
        }
        $socket = stream_socket_server(PASM::$string, $err, $err_str);
        if (!$socket) {
            echo "PASM::$err (PASM::$err_str)<br />\n";
            PASM::$cl = 0;
            return new static;
        } else {
            while ($conn = stream_socket_accept($socket)) {
                PASM::add_to_buffer(fread($conn, 4));
                fclose($conn);
            }
            fclose($socket);
        }
        PASM::$cl = 1;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function in_w()  // read word at a time
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::setup_chain(__METHOD__);

        if (!is_string(PASM::$string) || 0 == count(PASM::$string)) {
            echo "\$string must be numeric for " . __METHOD__;
            return;
        }
        $socket = stream_socket_server(PASM::$string, PASM::$err, PASM::$err_str);
        if (!$socket) {
            echo "PASM::err_str(" . PASM::${$err} . ")<br />\n";
            PASM::$cl = 0;
            return new static;
        } else {
            while ($conn = stream_socket_accept($socket)) {
                PASM::$add_to_buffer(fread($conn, 2));
                fclose($conn);
            }
            fclose($socket);
        }
        PASM::$cl = 1;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function in_q()  // read quad word at a time
    {
        PASM::setup_chain(__METHOD__);

        if (!is_string(PASM::$string) || 0 == strlen(PASM::$string)) {
            echo "\$string must be numeric for " . __METHOD__;
            return;
        }
        $socket = stream_socket_server(PASM::$string, PASM::$err, PASM::$err_str);
        if (!$socket) {
            echo PASM::$err_str . "(" . PASM::$err . ")<br />\n";
            PASM::$cl = 0;
            return;
        } else {
            while ($conn = stream_socket_accept($socket)) {
                PASM::add_to_buffer(fread($conn, 8));
                fclose($conn);
            }
            fclose($socket);
        }
        PASM::$cl = 1;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function interrupt($async_filename)  // push $ecx into $file->signal for interrupts and async calls
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_string($async_filename) || !is_numeric(PASM::$ah)) {
            return new static;
        }
        $async = file_get_contents($async_filename);
        $async = json_encode($async);
        $async->signal = PASM::$ecx;
        file_put_contents($async_filename, json_decode($async));
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function write() // write to file $string from $buffer
    {
        PASM::setup_chain(__METHOD__);

        if (!is_string(PASM::$buffer) && !is_numeric(PASM::$buffer)) {
            return;
        }
        file_put_contents(PASM::$string, PASM::$buffer);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function read()     // read from file PASM::$string
    {
        PASM::setup_chain(__METHOD__);

        if (!file_exists(PASM::$string)) {
            echo "Missing file: " . PASM::$string;
            return;
        }
        PASM::$buffer = file_get_contents(PASM::$string);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function mov_buffer()    // (really) move $buffer to stack
    {
        PASM::setup_chain(__METHOD__);

        array_push(PASM::$stack, PASM::$buffer);
        PASM::$buffer = "";
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function ja()    // from here down to next letter, is jmp commands (obvious to anyone)
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah > PASM::$ecx) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jae()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah >= PASM::$ecx) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jb()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah < PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jbe()
    {
        PASM::setup_chain(__METHOD__);

        if (PASM::$chain != null && PASM::$chain[PASM::$lop] == '' && PASM::$jbl == 1) {
            PASM::$ecx = 0;
        } else {
            return false;
        }
        echo PASM::$chain[PASM::$lop]['function'];
        if (PASM::$ah <= PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
        }
        PASM::coast();
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " " ;
        }
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jc()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx == 1 && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jcxz()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah == PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function je()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah == PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jg()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah > PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jge()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah >= PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jl()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah < PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jle()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah < PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jmp()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];

        PASM::$lop -= PASM::$ldp;
        PASM::$args[] = func_get_args();
        if (PASM::$ah != null && PASM::$ecx != null && PASM::$lop < count(PASM::$chain)) {
            $func = PASM::$chain[PASM::$lop%count(PASM::$chain)+1];
            if ($func == 'set') {
                PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
            } else {
                PASM::$func();
            }
        }

        PASM::debug_loop_count();
        return new static;
    }

    // compare any registers
    
    /**
     *	
     *
     * 
     */
    public static function cmp_any(string $a, string $b)
    {
        PASM::$bitcmp = (PASM::${$a} == PASM::${$b});
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jmpcmp()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!PASM::$bitcmp)
        {
            PASM::debug_loop_count();
            return new static;
        }

        PASM::$lop -= PASM::$ldp;
        PASM::$args[] = func_get_args();
        if (PASM::$ah != null && PASM::$ecx != null && PASM::$lop < count(PASM::$chain)) {
            $func = PASM::$chain[PASM::$lop%count(PASM::$chain)+1];
            if ($func == 'set') {
                PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
            } else {
                PASM::$func();
            }
        }

        PASM::$bitcmp = 0;
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jnae()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah < PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jnb()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah >= PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jnbe()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah > PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                if (count(PASM::$chain) <= PASM::$lop%count(PASM::$chain)) {
                    $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                } else {
                    $func = PASM::$chain[PASM::$lop%count(PASM::$chain)+1];
                }
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jnc()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx == 0 && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jne()
    {
        PASM::setup_chain(__METHOD__);
        //print_r(PASM::$chain);
        if (PASM::$ah != PASM::$ecx && PASM::$ah != null) {
            //PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop%count(PASM::$args)][0], PASM::$args[PASM::$lop%count(PASM::$args)][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jng()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ah < PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jnl()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx > PASM::$ecx && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jno()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx == 0 && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jns()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx >= 0 && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jnz()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx != 0 && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jgz()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx > 0 && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jlz()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx < 0 && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jzge()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx >= 0 && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jzle()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx <= 0 && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jo()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx == 1 && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jpe()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx%2 == 0 && PASM::$ah%2 && PASM::$ecx%2 == 0) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jpo()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx%2 == 1 && PASM::$ah%2 == 1 && PASM::$ecx%2 == 1) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function jz()
    {
        PASM::setup_chain(__METHOD__);


        if (PASM::$ecx == 0 && PASM::$ah != null) {
            PASM::$lop -= PASM::$ldp;
            if (PASM::$ah != null && PASM::$ecx != null) {
                $func = PASM::$chain[PASM::$lop%count(PASM::$chain)];
                if ($func == 'set') {
                    PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
                } else {
                    PASM::$func();
                }
            }
            PASM::$jbl = 1;
            PASM::coast();
        }

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function load_all_flags()    // load all flags to $ah
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ah = (PASM::$OF) + (PASM::$CF * 2) + (PASM::$ZF * 4);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function end()
    {     // reset all command chains
        PASM::$chain = [];
        PASM::$args = array();
        PASM::$lop = 0;
        PASM::$counter = 0;
    }

    
    /**
     *	
     *
     * 
     */
    public static function leave() // exit program
    {
        PASM::setup_chain(__METHOD__);

        exit();
    }

    
    /**
     *	
     *
     * 
     */
    public static function mov_ecx()   // move ecx to ah
    {
        PASM::setup_chain(__METHOD__);
        PASM::$ah = PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function mov_ah()    // move ah to ecx
    {
        PASM::setup_chain(__METHOD__);
        PASM::$ecx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function load_str($str = "")  // mov ecx to $string
    {
        PASM::setup_chain(__METHOD__);
        PASM::$string = empty($str) ? PASM::$ecx : $str;
        if (PASM::$ecx == PASM::$string && $str != PASM::$ecx)
        {
            echo "Function load_str failed.";
            return;
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function coast()     // the secret sauce. Go thru rest of commands after $ldp drop
    {
        $counted = 0;
        $count = count(PASM::$chain);
        while (PASM::$lop + $counted < $count && PASM::$lop + $counted > 0) {
            $func = PASM::$chain[PASM::$lop + $counted];
            if ($func == 'set') {
                PASM::$func(PASM::$args[PASM::$lop+$counted][0], PASM::$args[PASM::$lop+$counted][1]);
            } else {
                PASM::$func();
            }
            $counted++;
        }
        PASM::$ldp = 0;
        PASM::$counter = 0;
    }

    /**
     * @method This function requires that PASM::$ecx
     * be filled with a value > counter. Otherwise
     * it will not work out.
      */
    
    /**
     *	
     *
     * 
     */
    public static function loop()      // loop til $counter == $ecx
    {
        PASM::setup_chain(__METHOD__);

        $count = count(PASM::$chain);
        PASM::$lop -= PASM::$ldp;
        if (PASM::$counter < PASM::$ecx && PASM::$lop + PASM::$counter < $count) {
            $func = PASM::$chain[PASM::$lop + PASM::$counter];
            if ($func == 'set') {
                PASM::$func(PASM::$args[PASM::$lop + PASM::$counter][0], PASM::$args[PASM::$lop + PASM::$counter][1]);
            } else {
                PASM::$func();
            }
            PASM::$counter++;
            if (PASM::$pdb == 1) {
                echo PASM::$lop++ . " ";
            }
            PASM::coast();
                return new static;
        }
        PASM::coast();
        PASM::$counter = 0;
        return new static;
    }

    /**
     * @method This function requires that PASM::$ecx
     * be filled with a value == PASM::$ah. Otherwise
     * it will not work out. Change PASM::$ecx
     * in the previous function
      */
    
    /**
     *	
     *
     * 
     */
    public static function loope()     // loop while ah == ecx
    {
        PASM::setup_chain(__METHOD__);
        $counter = 0;
        $count = count(PASM::$chain);
        PASM::$lop -= PASM::$ldp;
        if (PASM::$ah == PASM::$ecx && PASM::$lop + PASM::$counter < $count) {
            $func = PASM::$chain[PASM::$lop + PASM::$counter];
            if ($func == 'set') {
                PASM::$func(PASM::$args[PASM::$lop + PASM::$counter][0], PASM::$args[PASM::$lop + PASM::$counter][1]);
            } else {
                PASM::$func();
            }
            PASM::$counter++;
            if (PASM::$pdb == 1) {
                echo PASM::$lop++ . " ";
            }
        }
        PASM::coast();

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function loopne()    // loop while ah and ecx are not equal
    {
        PASM::setup_chain(__METHOD__);
        $counter = 0;
        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$ah)) {
            return new static;
        }

        $count = count(PASM::$chain);
        PASM::$lop -= PASM::$ldp;
        if (PASM::$ah != PASM::$ecx && PASM::$lop + PASM::$counter < $count) {
            $func = PASM::$chain[PASM::$lop + PASM::$counter];
            if ($func == 'set') {
                PASM::$func(PASM::$args[PASM::$lop + PASM::$counter][0], PASM::$args[PASM::$lop + PASM::$counter][1]);
            } else {
                PASM::$func();
            }
            PASM::$counter++;
            if (PASM::$pdb == 1) {
                PASM::coast();
            }
            echo PASM::$lop++ . " ";
            return new static;
        }
        PASM::coast();

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function loopnz()    // loop while ecx is not 0
    {
        PASM::setup_chain(__METHOD__);

        $counter = 0;
        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$ah)) {
            return new static;
        }

        $count = count(PASM::$chain);
        PASM::$lop -= PASM::$ldp;
        if (0 != PASM::$ecx && PASM::$lop + PASM::$counter < $count) {
            $func = PASM::$chain[PASM::$lop + PASM::$counter];
            if ($func == 'set') {
                PASM::$func(PASM::$args[PASM::$lop + PASM::$counter][0], PASM::$args[PASM::$lop + PASM::$counter][1]);
            } else {
                PASM::$func();
            }
            PASM::$counter++;
            if (PASM::$pdb == 1) {
                echo PASM::$lop++ . " ";
            }
            PASM::coast();
                return new static;
        }
        PASM::coast();

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function loopz()     // loop while ecx == 0
    {
        PASM::setup_chain(__METHOD__);

        $counter = 0;
        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$ah)) {
            return new static;
        }
        $count = count(PASM::$chain);
        PASM::$lop -= PASM::$ldp;
        if (0 == PASM::$ecx && PASM::$lop + PASM::$counter < $count) {
            $func = PASM::$chain[PASM::$lop + PASM::$counter];
            if ($func == 'set') {
                PASM::$func(PASM::$args[PASM::$lop + PASM::$counter][0], PASM::$args[PASM::$lop + PASM::$counter][1]);
            } else {
                PASM::$func();
            }
            PASM::$counter++;
            PASM::coast();
        }
        PASM::coast();

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function mul()   // another ah * ecx
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx *= PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function movs()  // move $string to stack and clear
    {
        PASM::setup_chain(__METHOD__);

        array_push(PASM::$stack, PASM::$string);
        PASM::$string = "";
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function reset_sp()
    {
        PASM::setup_chain(__METHOD__);

        end(PASM::$stack);
        PASM::$sp = current(PASM::$stack);
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setup_chain(string $METHOD)
    {
        $method_del = explode("::", $METHOD);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];
                PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
    }

    
    /**
     *	
     *
     * 
     */
    public static function movr()  // move $string to stack and clear
    {
        PASM::setup_chain(__METHOD__);
        foreach (PASM::$array as $kv) {
            PASM::$stack[count(PASM::$stack)] = ($kv);
        }
        PASM::$array = [];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function debug_loop_count()
    {
        // $pdb is whether to show debug hints
        if (PASM::$pdb == 1) {
            // $lop is a counter of which function in the
            // chain has been executed. And by what iteration.
            echo PASM::$lop++ . " ";
        }
    }

    
    /**
     *	
     *
     * 
     */
    public static function addr(array $ar)  // move $string to stack and clear
    {
        PASM::setup_chain(__METHOD__);

        array_push(PASM::$array, $ar);
        
        debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function mwait()   // wait $wait microseconds
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        usleep(PASM::$wait);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function nop()
    {
        PASM::setup_chain(__METHOD__);
        return new static;
    }    //

    
    /**
     *	
     *
     * 
     */
    public static function not()   // performs a not on $ah ad ecx
    {
        PASM::setup_chain(__METHOD__);
        if (PASM::$ecx != PASM::$ah) {
            PASM::$cl = 1;
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function or()    // performs a or on ecx and ah
    {
        PASM::setup_chain(__METHOD__);
        if (PASM::$ecx or PASM::$ah) {
            PASM::$cl = 1;
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function out()   // moves buffer to site $string
    {
        PASM::setup_chain(__METHOD__);

        $socket = stream_socket_server(PASM::$string, $err, $err_str);
        if (!$socket) {
            echo "PASM::$err (PASM::$err_str)<br />\n";
        } else {
            while ($conn = stream_socket_accept($socket)) {
                fwrite($conn, PASM::$buffer, strlen(PASM::$buffer));
                fclose($conn);
            }
            fclose($socket);
        }
        
        PASM::debug_loop_count();
        return new static;
    }


    
    /**
     *	
     *
     * 
     */
    public static function obj_push(string $object, array $args) // push object to stack
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        $x = new \ReflectionClass($object);
        $x->newInstanceArgs($args);
        array_push(PASM::$stack, array("obj" => $x));
    }

    
    /**
     *	
     *
     * 
     */
    public static function pop()   // pop stack
    {
        PASM::setup_chain(__METHOD__);

        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function push()  // push ecx to stack
    {
        PASM::setup_chain(__METHOD__);

        array_push(PASM::$stack, PASM::$ecx);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function shift_left()    // shift ah left ecx times
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ah = decbin(PASM::$ah);
        if (strlen(PASM::$ah) == 1) {
            PASM::$OF = 1;
            return new static;
        }
        while (PASM::$ecx-- > 0) {
            PASM::$ah = rtrim(PASM::$ah, "0");
            $t = &PASM::$ah[strlen(PASM::$ah)-1];
            array_unshift(PASM::$ah, $t);
            PASM::$CF = PASM::$CF ^ $t;
        }
        PASM::$ah = bindec(PASM::$ah);
        $t = 0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function shift_right()   // shift ah right ecx times
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ah = decbin(PASM::$ah);
        if (strlen(PASM::$ah) == 1) {
            PASM::$OF = 1;
            return new static;
        }
        while (PASM::$ecx-- > 0) {
            PASM::$ah = rtrim(PASM::$ah, "0");
            $t = &PASM::$ah[strlen(PASM::$ah)-1];
            $s = &PASM::$ah[strlen(PASM::$ah)-2];
            array_push(PASM::$ah, $t);
            array_shift(PASM::$ah);
            array_unshift(PASM::$ah, $t);
            PASM::$CF = PASM::$CF ^ $t ^ $s;
        }
        PASM::$ah = bindec(PASM::$ah);
        $t = 0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function mv_shift_left() // pull bit around ecx times on ah (left)
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ah = decbin(PASM::$ah);
        if (strlen(PASM::$ah) == 1) {
            PASM::$OF = 1;
            return new static;
        }
        while (PASM::$ecx-- > 0) {
            PASM::$ah = rtrim(PASM::$ah, "0");
            $t = &PASM::$ah[strlen(PASM::$ah)-1];
            array_push(PASM::$ah, $t);
            array_unshift(PASM::$ah, $t);
            PASM::$CF = PASM::$CF ^ $t;
        }
        PASM::$ah = bindec(PASM::$ah);
        $t = 0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function mv_shift_right()    // same as above but (right)
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ah = decbin(PASM::$ah);
        if (strlen(PASM::$ah) == 1) {
            PASM::$OF = 1;
            return new static;
        }
        while (PASM::$ecx-- > 0) {
            PASM::$ah = rtrim(PASM::$ah, "0");
            $t = &PASM::$ah[strlen(PASM::$ah)-1];
            $s = &PASM::$ah[strlen(PASM::$ah)-2];
            array_push(PASM::$ah, $t);
            array_shift(PASM::$ah);
            array_unshift(PASM::$ah, $t);
            PASM::$CF = PASM::$CF ^ $t ^ $s;
        }
        PASM::$ah = bindec(PASM::$ah);
        $t = 0;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function run()
    {     // run file on linux $ST0 is command and arguments are $string
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];// $rdx is the output file to show what happened.
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B ". PASM::$ST0 . " " . PASM::$string, "r"));
        } else {
            exec(PASM::$ST0 . " " . PASM::$string . " > /dev/null &", PASM::$output, PASM::$cl);
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function run_pop()
    {     // same as above but pop
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];// again, $rdx is the output
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B ". PASM::$ST0 . " " . PASM::$string . " > " . PASM::$rdx, "r"));
        } else {
            exec(PASM::$ST0 . " " . PASM::$string . " > /dev/null &", PASM::$output, PASM::$cl);
        }
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_flags()     // set flags from ah bits [0,2]
    {
        PASM::setup_chain(__METHOD__);

        PASM::$OF = PASM::$ah%2;
        PASM::$ah >>= 1;
        PASM::$CF = PASM::$ah%2;
        PASM::$ah >>= 1;
        PASM::$ZF = PASM::$ah%2;
        PASM::$ah >>= 1;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function bitwisel()  // bitewise left
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx <<= PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function bitewiser() // same right
    {
        PASM::setup_chain(__METHOD__);

        PASM::$ecx >>= PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function scan_str()  // next(string);
    {
        PASM::setup_chain(__METHOD__);

        PASM::$strp = next(PASM::$string);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function reset_str()  // next(string);
    {
        PASM::setup_chain(__METHOD__);

        reset(PASM::$string);
        PASM::$strp = current(PASM::$string);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set($key, $new_value)   // set ${$key} with $new_value
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];
            //foreach (func_get_args() as ${$key})
            PASM::$args[PASM::$counter] = array($key => $new_value);
            PASM::$counter++;
        }
        try {
            ${$key} = $new_value;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ecx_adx()   // copy adx to ecx
    {
        PASM::setup_chain(__METHOD__);

        try {
            PASM::$ecx = PASM::$adx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ecx_rdx()
    {
        PASM::setup_chain(__METHOD__);
        try {
            PASM::$ecx = PASM::$rdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ecx_bdx()
    {
        PASM::setup_chain(__METHOD__);

        try {
            PASM::$ecx = PASM::$bdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ecx_cdx()
    {
        PASM::setup_chain(__METHOD__);

        try {
            PASM::$ecx = PASM::$cdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ecx_ddx()
    {
        PASM::setup_chain(__METHOD__);

        try {
            PASM::$ecx = PASM::$ddx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ecx_edx()
    {
        PASM::setup_chain(__METHOD__);

        try {
            PASM::$ecx = PASM::$edx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ah_adx()   // copy adx to ecx
    {
        PASM::setup_chain(__METHOD__);

        try {
            PASM::$ah = PASM::$adx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ah_rdx()
    {
        PASM::setup_chain(__METHOD__);

        try {
            PASM::$ah = PASM::$rdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ah_bdx()
    {
        PASM::setup_chain(__METHOD__);

        try {
            PASM::$ah = PASM::$bdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ah_cdx()
    {
        PASM::setup_chain(__METHOD__);

        try {
            PASM::$ah = PASM::$cdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ah_ddx()
    {
        PASM::setup_chain(__METHOD__);

        try {
            PASM::$ah = PASM::$ddx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function set_ah_edx()
    {
        PASM::setup_chain(__METHOD__);

        try {
            PASM::$ah = PASM::$edx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function seta()  // set if ah is above ecx
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah > PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setae()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah >= PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setb()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah < PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setbe()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah <= PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setc()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$CF != 0) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function sete()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah == PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setg()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah > PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setge()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah >= PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setl()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah < PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setle()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah <= PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setna()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah < PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setnae()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah > PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setnb()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah > PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setnbe()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah >= PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setnc()
    {
        PASM::setup_chain(__METHOD__);

        if (PASM::$CF == 0) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setne()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah != PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setng()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah <= PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setnge()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah < PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setnl()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah >= PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setnle()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah > PASM::$ecx) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setno()
    {
        PASM::setup_chain(__METHOD__);

        if (PASM::$OF != 1) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setnp()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (decbin(PASM::$ah) != decbin(PASM::$ecx)) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setns()  // if $ah >= 0 set rdx to ah
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah >= 0) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function seto()
    {
        PASM::setup_chain(__METHOD__);

        if (PASM::$OF == 1) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setp()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (decbin(PASM::$ecx) != decbin(PASM::$ah)) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setpe()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (decbin(PASM::$ecx) != decbin(PASM::$ah) && PASM::$cl%2 == 0) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setpo()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (decbin(PASM::$ecx) != decbin(PASM::$ah) && PASM::$cl%2 == 1) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function sets()  // if $ah < 0 set rdx to ah
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah < 0) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setz()  // if $ah == 0 set rdx to ah
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!is_int(PASM::$ah) || !is_int(PASM::$ecx)) {
            echo "Error in " . __METHOD__ . ": Incomparable types";
            exit(0);
        }
        PASM::$args[] = func_get_args();
        if (PASM::$ah == 0) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function setcf()     // set CF to 1
    {
        PASM::setup_chain(__METHOD__);

        PASM::$CF = 1;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function add_to_buffer() // continue buffer string
    {
        PASM::setup_chain(__METHOD__);

        PASM::$buffer .= PASM::$string;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function clear_buffer()  // clears $buffer
    {
        PASM::setup_chain(__METHOD__);
        PASM::$buffer = "";
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function save_stack_file()   // save state of $stack to file $string
    {
        PASM::setup_chain(__METHOD__);

        file_put_contents(PASM::$string, serialize((PASM::$stack)));
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function subtract_byte() // subtract 8 bits
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx = (PASM::$ecx - PASM::$ah)%256;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function subtract_word()     // subtract 16 bits
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx = (PASM::$ecx - PASM::$ah)%pow(2, 16);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function subtract_double()   // subtract 32 bits
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx = (PASM::$ecx - PASM::$ah)%pow(2, 32);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function subtract_quad() // subtract 64 bits
    {
        PASM::setup_chain(__METHOD__);

        PASM::$rdx = (PASM::$ecx - PASM::$ah)%pow(2, 64);
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function load_cl()   // push ah to cl
    {
        PASM::setup_chain(__METHOD__);

        PASM::$cl = PASM::$ah;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function test_compare() // peek at comparison
    {
        PASM::setup_chain(__METHOD__);

        if (PASM::$ah == PASM::$ecx) {
            PASM::$cl = 0;
        } elseif (PASM::$ah > PASM::$ecx) {
            PASM::$cl = 1;
        } elseif (PASM::$ah >= PASM::$ecx) {
            PASM::$cl = 2;
        } elseif (PASM::$ah < PASM::$ecx) {
            PASM::$cl = 3;
        } elseif (PASM::$ah <= PASM::$ecx) {
            PASM::$cl = 4;
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function thread() // thread php pages on demand on linux
    {
        PASM::setup_chain(__METHOD__);

        $x = "?";
        foreach (PASM::$ST0 as ${$key} => $value) {
            $x .= "&${$key}=$value";
        }
        exec("php PASM::$string/$x > /dev/null &");
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function xadd()  // ah = $ah + ecx && rdx = ah
    {
        PASM::setup_chain(__METHOD__);

        $temp = PASM::$ah;
        PASM::$rdx = PASM::$ah;
        PASM::$ah = $temp + PASM::$ecx;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function exchange()  // reverse ecx and ah
    {
        PASM::setup_chain(__METHOD__);

        $temp = PASM::$ah;
        PASM::$ecx = PASM::$ah;
        PASM::$ah = $temp;
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function xor() // xor $ah and ecx
    {
        PASM::setup_chain(__METHOD__);

        if (PASM::$ah xor PASM::$ecx) {
            PASM::$rdx = 1;
        }
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function popcnt()    // pop $ah times
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::$args[] = func_get_args();
        PASM::$args[] = func_get_args();
        $counter = count(PASM::$stack);
        while (count(PASM::$stack) > 0 && PASM::$ah < --$counter) {
            array_pop(PASM::$stack);
        }
        PASM::$cl = 1;
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function stack_func()
    {  // do top of stack as function
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::${$ST0}();

        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function stack_func_pos()
    {  // sync stack pointer
        PASM::$sp = current(PASM::$stack);
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::${$sp}();
        
        PASM::debug_loop_count();
        return new static;
    }

    
    /**
     *	
     *
     * 
     */
    public static function create_register(string $register, $value) // create a new variable "register"
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::$args[] = func_get_args();
        ${$register} = $value;
        
        PASM::debug_loop_count();
        return new static;
    }
}

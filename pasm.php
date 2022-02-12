<?php

namespace pasm;

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

    public static function get(string $var = "ah")
    {
        yield PASM::${$var};

        return new static;
    }

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
    // All functions are 100% ASM derived
    // Together there are 225+ functions
    // Do to obvious nature of names and
    // functionality they will have a small
    // amount of documentation.
    public static function char_adjust_addition()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
        PASM::$rdx = chr((PASM::$ecx + PASM::$ah)%256);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function carry_add()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$cl = PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function add()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$rdx = PASM::$ecx + PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function and()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$cl = PASM::$ecx & PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function chmod()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        chmod(PASM::$string, PASM::$ah);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function bit_scan_fwd()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$tp == null) {
            PASM::$tp = PASM::$qword;   // qword is used to look through a string via bit scanning
            PASM::$tp = decbin(PASM::$tp);
            PASM::$tp = str_split(PASM::$tp, 1);
            reset(PASM::$tp);
            return new static;
        }
        next(PASM::$tp);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function bit_scan_rvr()                  // reverse of above
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$tp == null) {
            PASM::$tp = PASM::$qword;
            PASM::$tp = decbin(PASM::$tp);
            PASM::$tp = str_split(PASM::$tp, 1);
            end(PASM::$tp);
            return new static;
        }
        prev(PASM::$tp);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function byte_rvr()                  // reverse byte
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        $temp = decbin(PASM::$ecx);
        PASM::$rdx = strrev($temp);
        PASM::$rdx = bindec(PASM::$rdx);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function bit_test()                  // bit is filled in pointer
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        return PASM::$tp[PASM::$ah];
    }

    public static function bit_test_comp()         // look thru byte and see the $ah'th bit
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        $bo = decbin(PASM::$ecx);
        $bo = $bo[PASM::$ah];
        PASM::$CF = (bool)($bo);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function bit_test_reset()    // Clear bit (ah) test flag
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        $bo = decbin(PASM::$ecx);
        $bo = $bo[PASM::$ah];
        PASM::$CF = (bool)($bo);
        PASM::$ecx = 0;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function bit_test_set()                  // Test bit
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        $bo = decbin(PASM::$ecx);
        $bo = $bo[PASM::$ah];
        PASM::$CF = (bool)($bo);
        PASM::$ecx[PASM::$ah] = 1;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function call() // call top of stack function
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (is_callable(PASM::$FUNC0)) {
            return call_user_func_array(PASM::$FUNC0(), PASM::$string);
        }
        return new static;
    }

    public static function cmp_mov_a()         // check ah against top of stack
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx = (PASM::$ah > PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_mov_ae()    // same (documenting will continue below)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx = (PASM::$ah >= PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_mov_b()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx = (PASM::$ah < PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_mov_be()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx = (PASM::$ah <= PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_mov_e()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx = (PASM::$ah == PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_mov_nz()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx = (PASM::$CF == 1 & PASM::$ah == PASM::$ST0) ? PASM::$ah : PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_mov_pe()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx = (PASM::$CF == 0) ? PASM::$ah : PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_mov_po()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx = (PASM::$CF == 1) ? PASM::$ah : PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_mov_s()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx = (PASM::$ah < 0) ? PASM::$ah : PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_mov_z()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx = (PASM::$ah > 0) ? PASM::$ah : PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function mov()   // move ah to ecx. Same as mov_ah()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx = PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function movabs()    // copy $ecx to stack
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        array_push(PASM::$stack, array("movabs" => PASM::$ecx));
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function clear_carry()   // clear $CF
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$CF = 0;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function clear_registers()   // make all registers 0
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$CF = PASM::$adx = PASM::$bdx = PASM::$cdx = PASM::$ddx = PASM::$edx = PASM::$rdx = 0;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function comp_carry()    // negate $CF
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$CF = !(PASM::$CF);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_e()         // bool of equality comparison (documentation continues below)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$cl = PASM::$ecx == PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_same()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$cl = PASM::$ecx == PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function cmp_xchg()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ZF = PASM::$ecx == PASM::$ah;
        list(PASM::$rdx, PASM::$ah) = array(PASM::$ah, PASM::$rdx);
        return new static;
    }


    public static function xchg(&$x, &$y)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
        list(PASM::$dx, PASM::$ah) = array(PASM::$ah, PASM::$rdx);
        return new static;
    }

    public static function decr(string $var = "ecx")                  // decrement ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::${$var}--;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function divide()    // $ecx/$ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (is_numeric(PASM::$ecx) && is_numeric(PASM::$ah)) {
            PASM::$rdx = round(PASM::$ecx/PASM::$ah);
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function absf()                  // absolute value of $ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$rdx = abs(PASM::$ah);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function addf()                  // add $ecx and $ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$rdx = PASM::$ecx + PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function round()         // round top stack to RC decimal
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::$ST0 = &PASM::$stack[array_key_last(PASM::$stack)];
        PASM::$ST0 = round(PASM::$ST0, PASM::$RC);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function neg()   // negate $ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (is_numeric(PASM::$ah)) {
            PASM::$rdx = PASM::$ah * (-1);
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function stack_cmov_b()                  // move on comparison (begins again below)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (count(PASM::$stack) > 0) {
            PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        } else {
            PASM::$ST0 = null;
        }
        if (PASM::$ST0 != null && PASM::$ah < PASM::$ST0) {
            PASM::$rdx = PASM::$ah;
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function stack_cmov_be()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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

    public static function stack_cmov_e()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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

    public static function stack_cmov_nb()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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

    public static function stack_cmov_nbe()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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

    public static function stack_cmov_ne()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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

    public static function fcomp()         // subtract top of stack from $ah and pop
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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

    public static function cosine()    // change top of stack to cosine of top of stack
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ST0 = &PASM::$stack[array_key_last(PASM::$stack)];
        PASM::$ST0 = (PASM::$ST0 != null) ? cos(PASM::$ST0) : null;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function stack_pnt_rev()         // go traverse the stack backward
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        prev(PASM::$stack);
        PASM::$sp = current(PASM::$stack);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fdiv()                  // divide ST0 into $ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$ah == 0) {
            echo "Denominator cannot be 0";
            PASM::$cl = 0;
            return new static;
        } elseif (!is_numeric(PASM::$ecx) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return new static;
        }
        PASM::$rdx = PASM::$ecx / PASM::$ST0;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fdiv_pop()                  // opposite as above and pop
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$ST0 == 0) {
            echo "Denominator cannot be 0";
            PASM::$cl = 0;
            return new static;
        }
        PASM::$rdx = PASM::$ST0 / PASM::$ecx;
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fdiv_rev()                  // opposite of fdiv
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$ST0 == 0) {
            echo "Denominator cannot be 0";
            PASM::$cl = 0;
            return new static;
        }
        PASM::$rdx = PASM::$ST0 / PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fdiv_rev_pop()                  // same as above with po
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$ST0 == 0) {
            echo "Denominator cannot be 0";
            PASM::$cl = 0;
            return new static;
        }
        PASM::$rdx = PASM::$ecx / PASM::$ST0;
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function add_stack()         // add top of stack to ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ah) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return new static;
        }
        PASM::$rdx = PASM::$ecx + PASM::$ST0;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function ficomp()    // compare and pop
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$ST0 == PASM::$ah) {
            PASM::$cl = 1;
        }
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function recvr_stack(string $filename)
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        if (!file_exists($filename)) {
            return false;
        }
        PASM::$stack = (unserialize(file_get_contents($filename)));
        //PASM::$addr()
        //  ->movr()
        //->end();
        return new static;
    }

    public static function stack_load() // stack with count on stack
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        ${$key} = "f" . count(PASM::$stack);
        array_push(PASM::$stack, array($key => PASM::$ecx));
        PASM::$ecx = null;
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function stack_mrg() // stack with count on stack
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        ${$key} = "f" . count(PASM::$stack);
        array_merge(PASM::$stack, PASM::$array);
        PASM::$ecx = null;
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fmul()                  // multiplies ecx and ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ah) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return new static;
        }
        PASM::$rdx = PASM::$ecx * PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function stack_pnt_fwd()         // moves stack pointer forward
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        next(PASM::$stack);
        PASM::$sp = current(PASM::$stack);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function store_int()         // subtracts $ST0 - 2-to-the-$ah and puts answer in $rdx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
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
        PASM::$rdx = PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function store_int_pop() // same as above, but with pop
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function subtract_rev() // like subtract but backwards
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$ecx) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return;
        }
        PASM::$rdx = PASM::$ecx - PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function subtract()  // $ah - $ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$ecx) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return;
        }
        PASM::$rdx = PASM::$ah - PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fld1()  // pushes ecx+1 to stack
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ecx)) {
            return;
        }
        array_push(PASM::$stack, array("inc" => (PASM::$ecx + 1)));
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function load_logl2() //
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        array_push(PASM::$stack, array("logl2" => log(log(2))));
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function load_logl2t()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        array_push(PASM::$stack, array("logl2t" => log(2, 10)));
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function load_loglg2()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ah)) {
            echo "\$ah must be numeric for load_loglg2";
            return;
        }
        array_push(PASM::$stack, array("loglg2" => log(2, log(PASM::$ah))));
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function load_ln2()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        $e = M_E;
        array_push(PASM::$stack, array("ln2" => log($e, 2)));
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function load_pi()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        array_push(PASM::$stack, array("pi" => M_PI));
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function float_test()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ah)) {
            echo "\$ah must be numeric for float_test";
            return;
        }
        PASM::$rdx = PASM::$ah + 0.0;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fmul_pop() // ah * ecx and pop
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ah) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return;
        }
        PASM::$rdx = PASM::$ah * PASM::$ecx;
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function clean_exceptions()  // clear exception bit
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ZF = 0;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function clean_reg() // clear cl
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$cl = 0;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fnop()  // counts as function, does nothing but takes up space (like in assembly)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fpatan()    // gets arctan of $ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


        PASM::$cl = atan(PASM::$ah);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fptan() // gets tangent of ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$cl = tan(PASM::$ah);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fprem() // look to documentation (Oracle Systems Manual)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$stack[count(PASM::$stack)-2]) || !is_numeric(PASM::$stack[array_key_last(PASM::$stack)])) {
            return new static;
        }
        if (count(PASM::$stack) > 1) {
            PASM::$ecx = PASM::$stack[array_key_last(PASM::$stack)] / PASM::$stack[count(PASM::$stack)-2];
        }
        PASM::$rdx = (round(PASM::$ecx, (PASM::$RC+1)) - (PASM::$ecx*10*(PASM::$RC+1)))/10/(1+PASM::$RC);
        PASM::$cl = 0;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function frndint()   // round top of stack into $rdx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ecx) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return new static;
        }
        PASM::$rdx = round(PASM::$stack[array_key_last(PASM::$stack)], PASM::$RC);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function frstor() // copy $ah to $rdx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$rdx = PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fsin() // change top of stack to sin of top of stack
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::$ST0 = &PASM::$stack[array_key_last(PASM::$stack)];
        PASM::$ST0 = (PASM::$ST0 != null) ? sin(PASM::$ST0) : null;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fsincos() // push cos of $ST0 to stack and fill $ST0 with sin of itself
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        array_push(PASM::$stack, array("cos" => cos(PASM::$ST0)));
        PASM::$ST0 = (PASM::$ST0 != null) ? sin(PASM::$ST0) : null;
        next(PASM::$stack);
        PASM::$ST0 = current(PASM::$stack);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fscale()    // round top 2 stack elements and push to rdx ans powers of 2
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        $sp1 = round(PASM::$stack[count(PASM::$stack)-2]);
        $sp0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (!is_numeric($sp1) || !is_numeric($sp0)) {
            echo "Top 2 stack registers must be numeric for fscale()";
            return;
        }
        PASM::$rdx = pow(2, $sp0+$sp1);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fsqrt() // push to stack top value's sqrt
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$stack[array_key_last(PASM::$stack)] = sqrt(PASM::$stack[array_key_last(PASM::$stack)]);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fst() // copy ST0 to another position ($ecx)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        PASM::$stack[PASM::$ecx] = PASM::$ST0;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fstcw() // push $ah to $rdx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$rdx = PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fstp()  // same as fst() but pops
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$stack[PASM::$ecx] = PASM::$stack[array_key_last(PASM::$stack)];
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function subtract_pop()  // like it says ($ah - $ST0)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$stack[array_key_last(PASM::$stack)])) {
            echo "\$ST0 & \$ah must be numeric for subtract_pop";
            return;
        }
        PASM::$rdx = PASM::$ah - (PASM::$ST0);
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function subtract_rev_pop() // (same only reverse)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ah) || !is_numeric(PASM::$stack[array_key_last(PASM::$stack)])) {
            echo "\$ST0 & \$ah must be numeric for subtract_rev_pop";
            return;
        }
        PASM::$rdx = PASM::$ST0 - PASM::$ah;
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function ftst()  // check that math works
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$rdx)) {
            echo "\$rdx must be numeric for ftst";
            return;
        }
        PASM::$rdx -= 0.0;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fucom() // ecx == $sp and $rdx = $ST0
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fucomp()    // above ith pop
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::fucom();
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fucompp()   // above with another pop
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::fucom();
        array_pop(PASM::$stack);
        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fxam()  // get decimal value, without integer
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ah) || !PASM::$stack[array_key_last(PASM::$stack)]) {
            return new static;
        }
        PASM::$rdx = PASM::$ah - round(PASM::$ah);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fxch()  // exchange values from one stack place to another (the top)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ST0 = &PASM::$stack[array_key_last(PASM::$stack)];
        $temp = PASM::$stack[PASM::$ecx];
        PASM::$stack[PASM::$ecx] = PASM::$ST0;  // goes into PASM::$ecx
        PASM::$ST0 = $temp;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fxtract()   // get highest significand and exponent of number
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fyl2x()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ecx) || !is_numeric(PASM::$ah)) {
            return new static;
        }
        PASM::$rdx = PASM::$ecx * log(PASM::$ah, 2);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function fyl2xp1()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ecx) || !is_numeric(PASM::$ah)) {
            return new static;
        }
        PASM::$rdx = PASM::$ecx * log(PASM::$ah, 2 + 1);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function idiv()  // divide $ah / $ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ecx) || !is_numeric(PASM::$ah)) {
            return new static;
        }
        PASM::$rdx = PASM::$ah / PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function imul()  // $ah * $ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ecx) || !is_numeric(PASM::$ah)) {
            return new static;
        }
        PASM::$rdx = PASM::$ah * PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function in()    // $string is server, collects in $buffer
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function inc()   // increment $ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_numeric(PASM::$ecx)) {
            return new static;
        }
        PASM::$ecx++;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function in_b()  // read 1 byte at a time
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function in_d() // read 1 dword at a time
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function in_w()  // read word at a time
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function in_q()  // read quad word at a time
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function write() // write to file $string from $buffer
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!is_string(PASM::$buffer) && !is_numeric(PASM::$buffer)) {
            return;
        }

        file_put_contents(PASM::$string, PASM::$buffer);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function read()     // read from file PASM::$string
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (!file_exists(PASM::$string)) {
            echo "Missing file: " . PASM::$string;
            return;
        }

        PASM::$buffer = file_get_contents(PASM::$string);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function mov_buffer()    // (really) move $buffer to stack
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        array_push(PASM::$stack, PASM::$buffer);
        PASM::$buffer = "";
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function ja()    // from here down to next letter, is jmp commands (obvious to anyone)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jae()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jb()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jbe()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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

    public static function jc()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jcxz()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function je()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jg()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jge()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jl()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jle()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jmp()
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];


        PASM::$lop -= PASM::$ldp;
        PASM::$args[] = func_get_args();

        if (PASM::$ecx != null && PASM::$ecx != null && PASM::$lop < count(PASM::$chain)) {
            $func = PASM::$chain[PASM::$lop%count(PASM::$chain)+1];
            if ($func == 'set') {
                PASM::$func(PASM::$args[PASM::$lop][0], PASM::$args[PASM::$lop][1]);
            } else {
                PASM::$func();
            }
        }

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jnae()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jnb()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jnbe()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jnc()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jne()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jng()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jnl()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jno()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jns()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jnz()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jgz()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jlz()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jzge()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jzle()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jo()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jpe()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jpo()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function jz()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }


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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function load_all_flags()    // load all flags to $ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ah = (PASM::$OF) + (PASM::$CF * 2) + (PASM::$ZF * 4);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function end()
    {     // reset all command chains
        PASM::$chain = [];
        PASM::$args = array();
        PASM::$lop = 0;
        PASM::$counter = 0;
    }

    public static function leave() // exit program
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        exit();
    }

    public static function mov_ecx()   // move ecx to ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
        PASM::$ah = PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function mov_ah()    // move ah to ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
        PASM::$ecx = PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function load_str($str = "")  // mov ecx to $string
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
        PASM::$string = empty($str) ? PASM::$ecx : $str;
        if (PASM::$ecx == PASM::$string && $str != PASM::$ecx)
        {
            echo "Function load_str failed.";
            return;
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
    public static function loop()      // loop til $counter == $ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
    public static function loope()     // loop while ah == ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function loopne()    // loop while ah and ecx are not equal
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function loopnz()    // loop while ecx is not 0
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function loopz()     // loop while ecx == 0
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function mul()   // another ah * ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx *= PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function movs()  // move $string to stack and clear
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        array_push(PASM::$stack, PASM::$string);
        PASM::$string = "";
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function reset_sp()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        end(PASM::$stack);
        PASM::$sp = current(PASM::$stack);
        return new static;
    }

    public static function movr()  // move $string to stack and clear
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        foreach (PASM::$array as $kv) {
            PASM::$stack[count(PASM::$stack)] = ($kv);
        }
        PASM::$array = [];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function addr(array $ar)  // move $string to stack and clear
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        array_push(PASM::$array, $ar);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function mwait()   // wait $wait microseconds
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        usleep(PASM::$wait);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function nop()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
        return new static;
    }    //

    public static function not()   // performs a not on $ah ad ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$ecx != PASM::$ah) {
            PASM::$cl = 1;
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function or()    // performs a or on ecx and ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$ecx or PASM::$ah) {
            PASM::$cl = 1;
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function out()   // moves buffer to site $string
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }


    public static function obj_push(string $object, array $args) // push object to stack
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        $x = new \ReflectionClass($object);
        $x->newInstanceArgs($args);
        array_push(PASM::$stack, array("obj" => $x));
    }

    public static function pop()   // pop stack
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        array_pop(PASM::$stack);
        PASM::$ST0 = PASM::$stack[array_key_last(PASM::$stack)];
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function push()  // push ecx to stack
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        array_push(PASM::$stack, PASM::$ecx);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function shift_left()    // shift ah left ecx times
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function shift_right()   // shift ah right ecx times
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function mv_shift_left() // pull bit around ecx times on ah (left)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function mv_shift_right()    // same as above but (right)
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function run()
    {     // run file on linux $ST0 is command and arguments are $string

        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];// $rdx is the output file to show what happened.
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B ". PASM::$ST0 . " " . PASM::$string, "r"));
        } else {
            exec(PASM::$ST0 . " " . PASM::$string . " > /dev/null &", PASM::$output, PASM::$cl);
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_flags()     // set flags from ah bits [0,2]
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$OF = PASM::$ah%2;
        PASM::$ah >>= 1;
        PASM::$CF = PASM::$ah%2;
        PASM::$ah >>= 1;
        PASM::$ZF = PASM::$ah%2;
        PASM::$ah >>= 1;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function bitwisel()  // bitewise left
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx <<= PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function bitewiser() // same right
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$ecx >>= PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function scan_str()  // next(string);
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$strp = next(PASM::$string);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function reset_str()  // next(string);
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        reset(PASM::$string);
        PASM::$strp = current(PASM::$string);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ecx_adx()   // copy adx to ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ecx = PASM::$adx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ecx_rdx()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ecx = PASM::$rdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ecx_bdx()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ecx = PASM::$bdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ecx_cdx()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ecx = PASM::$cdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ecx_ddx()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ecx = PASM::$ddx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ecx_edx()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ecx = PASM::$edx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ah_adx()   // copy adx to ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ah = PASM::$adx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ah_rdx()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ah = PASM::$rdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ah_bdx()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ah = PASM::$bdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ah_cdx()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ah = PASM::$cdx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ah_ddx()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ah = PASM::$ddx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function set_ah_edx()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        try {
            PASM::$ah = PASM::$edx;
        } catch (\Exception $e) {
            echo "#Register " . PASM::${$key} . " not in object...<br>Failing...";
            exit();
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function setnc()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$CF == 0) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function setno()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$OF != 1) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function seto()
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$OF == 1) {
            PASM::$cl = 1;
        } else {
            return new static;
        }
        PASM::$rdx = PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function setcf()     // set CF to 1
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$CF = 1;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function add_to_buffer() // continue buffer string
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$buffer .= PASM::$string;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function clear_buffer()  // clears $buffer
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }
        PASM::$buffer = "";
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function save_stack_file()   // save state of $stack to file $string
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        file_put_contents(PASM::$string, serialize((PASM::$stack)));
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function subtract_byte() // subtract 8 bits
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$rdx = (PASM::$ecx - PASM::$ah)%256;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function subtract_word()     // subtract 16 bits
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$rdx = (PASM::$ecx - PASM::$ah)%pow(2, 16);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function subtract_double()   // subtract 32 bits
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$rdx = (PASM::$ecx - PASM::$ah)%pow(2, 32);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function subtract_quad() // subtract 64 bits
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$rdx = (PASM::$ecx - PASM::$ah)%pow(2, 64);
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function load_cl()   // push ah to cl
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        PASM::$cl = PASM::$ah;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function test_compare() // peek at comparison
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function thread() // thread php pages on demand on linux
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        $x = "?";
        foreach (PASM::$ST0 as ${$key} => $value) {
            $x .= "&${$key}=$value";
        }
        exec("php PASM::$string/$x > /dev/null &");
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function xadd()  // ah = $ah + ecx && rdx = ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        $temp = PASM::$ah;
        PASM::$rdx = PASM::$ah;
        PASM::$ah = $temp + PASM::$ecx;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function exchange()  // reverse ecx and ah
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        $temp = PASM::$ah;
        PASM::$ecx = PASM::$ah;
        PASM::$ah = $temp;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function xor() // xor $ah and ecx
    {
        $method_del = explode("::", __METHOD__);
        {
            PASM::$chain[PASM::$counter] = $method_del[1];

            PASM::$args[PASM::$counter] = func_get_args() || null;
            PASM::$counter++;
        }

        if (PASM::$ah xor PASM::$ecx) {
            PASM::$rdx = 1;
        }
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

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
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function stack_func()
    {  // do top of stack as function
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::${$ST0}();

        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function stack_func_pos()
    {  // sync stack pointer
        PASM::$sp = current(PASM::$stack);
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::${$sp}();
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }

    public static function create_register(string $register, $value) // create a new variable "register"
    {
        $method_del = explode("::", __METHOD__);
        PASM::$chain[] = $method_del[1];
        PASM::$args[] = func_get_args();

        ${$register} = $value;
        if (PASM::$pdb == 1) {
            echo PASM::$lop++ . " ";
        }

        return new static;
    }
}

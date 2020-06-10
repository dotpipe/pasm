<?php

class PASM
{

    private $ZF = 0;        // Comparison Flag for Exchanges
    private $OF = 0;        // Overflow Flag
    private $CF = 0;        // Carry Flag
    private $counter = 0;   // Needed for loops
    private $chain = [];    // Chain of events in line use $this->end() to stop and start again
    private $args = [];     // Array to hold args for set variables
    public $stack = [];     // Stack
    public $array = [];     // array for stack formations
    public $sp;             // Stack pointer
    public $ST0;            // LAST STACK ELEMENT
    public $pdb = 0;        // debug flag (DEFAULT FALSE)
    // The stack is referenced under objects
    // The pairing, is to be justified, with
    // a simulacrum between the object and the
    // variable. Inside and out. This is an image.
    // You may make apps out of it. You may make
    // such PHP wrappers as to create formidable
    // language enhancements and have a fast,
    // easy to see connection to low level speed
    public $tp;     // holder for current bit
    public $ecx;    // RHS, DECR, INC, COMPARATOR
    public $adx;    // Registers
    public $bdx;    //
    public $cdx;    //
    public $ddx;    //
    public $edx;    //
    public $ah;     // LHS, COMPARATOR
    public $ldp;    // The amount of commands to go back in loops and jmps
    public $rdx;    // Holds answers to addition, and other math
    public $qword;  // String Register
    public $RC;     // Round to this decimal
    public $wait;   // wait variable
    public $strp;   // string pointer
    private $cl;    // BOOL ANSWER FOR JMP
    public $string; // STRING register
    private $lop;   // Place in $chain
    public $err;    // Error No.
    public $err_str;    // Error String

    /**
     * Useful for some testing.
     * Will be easier to just play around.
     * However this verifies all methods work.
     */
    public function get ()
    {
        foreach (get_class_methods($this) as $method)
        {
            if ($method == "get")
                continue;
            $r = new ReflectionMethod("PASM", $method);
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
                $this->ecx = 3;
                $this->ah = 3;
                $this->$method();
                continue;
            }
            catch (exception $e) {
                $this->ecx = 2;
                $this->ah = 3;
                $this->$method();
                continue;
            }
            finally {
                $this->ecx = [2];
                $this->ah = [3];
                $this->$method();
            }
        }
    }

    // All functions are 100% ASM derived
    // Together there are 225+ functions
    // Do to obvious nature of names and
    // functionality they will have a small
    // amount of documentation.
    public function char_adjust_addition()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->rdx = chr(($this->ecx + $this->ah)%256);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;

    }

    public function carry_add()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->cl = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function add()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->rdx = $this->ecx + $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function and()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->cl = $this->ecx & $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function chmod()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        chmod($this->string, $this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function bit_scan_fwd()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->tp == null) {

            // qword is used to look through a string via bit scanning
            $this->tp = $this->qword;

            $this->tp = decbin($this->tp);
            $this->tp = str_split($this->tp,1);
            reset($this->tp);
            return $this;
        }
        next($this->tp);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // bit_scan_rvr reverses bit_scan_fwd.
    public function bit_scan_rvr()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->tp == null) {
            $this->tp = $this->qword;
            $this->tp = decbin($this->tp);
            $this->tp = str_split($this->tp,1);
            end($this->tp);
            return $this;
        }
        prev($this->tp);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // reverse byte
    public function byte_rvr()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $temp = decbin($this->ecx);
        $this->rdx = strrev($temp);
        $this->rdx = bindec($this->rdx);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // bit is filled in pointer
    public function bit_test()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        return $this->tp[$this->ah];
    }

    // look through byte and see the $ah'nth bit
    public function bit_test_comp()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $bo = decbin($this->ecx);
        $bo = $bo[$this->ah];
        $this->CF = (bool)($bo);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // Clear bit test flag
    public function bit_test_reset()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $bo = decbin($this->ecx);
        $bo = $bo[$this->ah];
        $this->CF = (bool)($bo);
        $this->ecx = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // Test bit
    public function bit_test_set()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $bo = decbin($this->ecx);
        $bo = $bo[$this->ah];
        $this->CF = (bool)($bo);
        $this->ecx[$this->ah] = 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // call any function
    public function call()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (is_callable($this->ST0))
            return call_user_func($this->ST0(), $this->string, $this->ah);
    }

    // heck ah against top of stack
    public function cmp_mov_a()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx = ($this->ah > $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // same (documenting will continue below)
    public function cmp_mov_ae()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx = ($this->ah >= $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_b()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx = ($this->ah < $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_be()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);
        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx = ($this->ah <= $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_e()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx = ($this->ah == $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_nz()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx = ($this->CF == 1 & $this->ah == $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_pe()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx = ($this->CF == 0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_po()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx = ($this->CF == 1) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_s()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx = ($this->ah < 0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_z()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx = ($this->ah > 0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // move ah to ecx. Same as mov_ah()
    public function mov()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // copy $ecx to stack
    public function movabs()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        array_push($this->stack, array("movabs" => $this->ecx));
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // clear $CF
    public function clear_carry()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->CF = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // make all registers 0
    public function clear_registers()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->CF = $this->adx = $this->bdx = $this->cdx = $this->ddx = $this->edx = $this->rdx = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // negate $CF
    public function comp_carry()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->CF = !($this->CF);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // bool of equality comparison (documentation continues below)
    public function cmp_e()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->cl = $this->ecx == $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_same()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->cl = $this->ecx == $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_xchg()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx == $this->ah) {
            $this->rdx = $this->ah;
            $this->ZF = 1;
            return $this;
        }
        else {
            $this->rdx = $this->ah;
            $this->ZF = 0;
            return $this;
        }
    }

    // decrement ecx
    public function decr()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx--;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // $ecx/$ah
    public function divide()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (is_numeric($this->ecx) && is_numeric($this->ah))
        $this->rdx = round($this->ecx/$this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // absolute value of $ah
    public function absf()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->rdx = abs($this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // add $ecx and $ah
    public function addf()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->rdx = $this->ecx + $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // round top stack to RC decimal
    public function round()
    {
        $this->ST0 = &$this->stack[array_key_last($this->stack)];
        $this->ST0 = round($this->ST0, $this->RC);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // same but pop
    public function round_pop()
    {
        $this->ah = &$this->stack[array_key_last($this->stack)];
        $this->ah = round($this->ST0, $this->RC);
        array_pop($this->stack);
        if (count($this->stack) > 0)
            $this->ST0 = $this->stack[array_key_last($this->stack)];
        else
            $this->ST0 = null;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // negate $ah
    public function neg()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (is_numeric($this->ah))
            $this->rdx = $this->ah * (-1);
            if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // move on comparison (begins again below)
    public function stack_cmov_b()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (count($this->stack) > 0)
            $this->ST0 = $this->stack[array_key_last($this->stack)];
        else
            $this->ST0 = null;
        if ($this->ST0 != null && $this->ah < $this->ST0)
            $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function stack_cmov_be()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (count($this->stack) > 0)
            $this->ST0 = $this->stack[array_key_last($this->stack)];
        else
            $this->ST0 = null;
        if ($this->ST0 != null && $this->ah <= $this->ST0)
            $this->rdx = $this->ah;
            return $this;
    }

    public function stack_cmov_e()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (count($this->stack) > 0)
            $this->ST0 = $this->stack[array_key_last($this->stack)];
        else
            $this->ST0 = null;
        if ($this->ST0 != null && $this->ah == $this->ST0)
            $this->rdx = $this->ah;
            return $this;
    }

    public function stack_cmov_nb()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (count($this->stack) > 0)
            $this->ST0 = $this->stack[array_key_last($this->stack)];
        else
            $this->ST0 = null;
        if ($this->ST0 != null && $this->ah > $this->ST0)
            $this->rdx = $this->ah;
            return $this;
    }

    public function stack_cmov_nbe()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (count($this->stack) > 0)
            $this->ST0 = $this->stack[array_key_last($this->stack)];
        else
            $this->ST0 = null;
        if ($this->ST0 != null && $this->ah >= $this->ST0)
            $this->rdx = $this->ah;
            return $this;
    }

    public function stack_cmov_ne()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (count($this->stack) > 0)
            $this->ST0 = $this->stack[array_key_last($this->stack)];
        else
            $this->ST0 = null;
        if ($this->ST0 != null && $this->ah != $this->ST0)
            $this->rdx = $this->ah;
            return $this;
    }

    // subtract top of stack from $ah and pop
    public function fcomp()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah) || !$this->stack[array_key_last($this->stack)])
            return $this;
        $this->rdx = $this->ah - $this->stack[array_key_last($this->stack)];
        array_pop($this->stack);
        if (count($this->stack) > 0)
            $this->ST0 = $this->stack[array_key_last($this->stack)];
        else
            $this->ST0 = null;
        if ($this->ST0 != null && $this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // change top of stack to cosine of top of stack
    public function cosine()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ST0 = &$this->stack[array_key_last($this->stack)];
        $this->ST0 = ($this->ST0 != null) ? cos($this->ST0) : null;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // go reverse the stack
    public function stack_pnt_rev()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        prev($this->stack);
        $this->sp = current($this->stack);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // divide ST0 into $ecx
    public function fdiv()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah == 0) {
            echo "Denominator cannot be 0";
            $this->cl = 0;
            return $this;
        }
        else if (!is_numeric($this->ecx) || !$this->stack[array_key_last($this->stack)])
            return $this;
        $this->rdx = $this->ecx / $this->ST0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // opposite as above and pop
    public function fdiv_pop()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ST0 == 0) {
            echo "Denominator cannot be 0";
            $this->cl = 0;
            return $this;
        }
        $this->rdx = $this->ST0 / $this->ecx;
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // opposite of fdiv
    public function fdiv_rev()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ST0 == 0) {
            echo "Denominator cannot be 0";
            $this->cl = 0;
            return $this;
        }
        $this->rdx = $this->ST0 / $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // same as above with po
    public function fdiv_rev_pop()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ST0 == 0) {
            echo "Denominator cannot be 0";
            $this->cl = 0;
            return $this;
        }
        $this->rdx = $this->ecx / $this->ST0;
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // add top of stack to ecx
    public function add_stack()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah) || !$this->stack[array_key_last($this->stack)])
            return $this;
        $this->rdx = $this->ecx + $this->ST0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // compare and pop
    public function ficomp()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ST0 == $this->ah)
            $this->cl = 1;
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function recvr_stack(string $filename) {
        if (!file_exists($filename))
            return false;
        $this->stack = (unserialize(file_get_contents($filename)));
        //$this->addr()
          //  ->movr()
            //->end();
        return $this;
    }

    // stack with count on stack
    public function stack_load()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $key = "f" . count($this->stack);
        array_push($this->stack, array($key => $this->ecx));
        $this->ecx = null;
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // stack with count on stack
    public function stack_mrg()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $key = "f" . count($this->stack);
        array_merge($this->stack, $this->array);
        $this->ecx = null;
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // multiplies ecx and ah
    public function fmul()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah) || !$this->stack[array_key_last($this->stack)])
            return $this;
        $this->rdx = $this->ecx * $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // moves stack pointer forward
    public function stack_pnt_fwd()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        next($this->stack);
        $this->sp = current($this->stack);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // subtracts $ST0 - 2-to-the-$ah and puts answer in $rdx
    public function store_int()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (ctype_xdigit($this->stack[array_key_last($this->stack)]))
            $this->ST0 = hexdec($this->stack[array_key_last($this->stack)]);
        if (is_numeric($this->stack[array_key_last($this->stack)]))
            $this->ST0 = decbin($this->stack[array_key_last($this->stack)]);
        $test = rtrim($this->ST0,"01");
        if (strlen($test) == 0)
            $this->ST0 = $this->stack[array_key_last($this->stack)];
        else
            return;
        if (is_numeric($this->ah))
            $this->rdx = $this->ST0 - pow(2,8*$this->ah);
        else {
            echo "\$ah is not in numeric form";
            return;
        }
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // same as above, but with pop
    public function store_int_pop()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah) || !is_numeric($this->stack[array_key_last($this->stack)])) {
            echo "Invalid Operand in store_int_pop: \$ah = $this->ah & $ST0 = " . $this->stack[array_key_last($this->stack)];
            return;
        }
        if (ctype_xdigit($this->stack[array_key_last($this->stack)]))
            $this->ST0 = hexdec($this->stack[array_key_last($this->stack)]);
        if (is_numeric($this->stack[array_key_last($this->stack)]))
            $this->ST0 = decbin($this->stack[array_key_last($this->stack)]);
        $test = rtrim($this->ST0,"01");
        if (strlen($test) == 0)
            $this->ST0 = $this->stack[array_key_last($this->stack)];
        else
            return;
        if (is_numeric($this->ah))
            $this->rdx = $this->ST0 - pow(2,8*$this->ah);
        else {
            echo "\$ah is not in numeric form";
            return;
        }
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // like subtract but backwards
    public function subtract_rev()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah) || !is_numeric($this->ecx) || !$this->stack[array_key_last($this->stack)])
            return;
        $this->rdx = $this->ecx - $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // $ah - $ecx
    public function subtract()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah) || !is_numeric($this->ecx) || !$this->stack[array_key_last($this->stack)])
            return;
        $this->rdx = $this->ah - $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // pushes ecx+1 to stack
    public function fld1()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ecx))
            return;
        array_push($this->stack, array("inc" => ($this->ecx + 1)));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_logl2() //
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        array_push($this->stack, array("logl2" => log(log(2))));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_logl2t()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        array_push($this->stack, array("logl2t" => log(2,10)));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_loglg2()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah))
        {
            echo "\$ah must be numeric for load_loglg2";
            return;
        }
        array_push($this->stack, array("loglg2" => log(2,log($this->ah))));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_ln2()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $e = M_E;
        array_push($this->stack, array("ln2" => log($e,2)));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_pi()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        array_push($this->stack, array("pi" => M_PI));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function float_test()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah))
        {
            echo "\$ah must be numeric for float_test";
            return;
        }
        $this->rdx = $this->ah + 0.0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // ah * ecx and pop
    public function fmul_pop()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah) || !$this->stack[array_key_last($this->stack)])
            return;
        $this->rdx = $this->ah * $this->ecx;
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // clear exception bit
    public function clean_exceptions()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $thiz->ZF = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // clear cl
    public function clean_reg()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->cl = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // counts as function, does nothing but takes up space (like in assembly)
    public function fnop()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // gets arctan of $ah
    public function fpatan()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->cl = atan($this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // gets tangent of ah
    public function fptan()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->cl = tan($this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // look to documentation (Oracle Systems Manual)
    public function fprem()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->stack[count($this->stack)-2]) || !is_numeric($this->stack[array_key_last($this->stack)]))
            return $this;
        if (count($this->stack) > 1)
            $this->ecx = $this->stack[array_key_last($this->stack)] / $this->stack[count($this->stack)-2];
        $this->rdx = (round($this->ecx,($this->RC+1)) - ($this->ecx*10*($this->RC+1)))/10/(1+$this->RC);
        $this->cl = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // round top of stack into $rdx
    public function frndint()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ecx) || !$this->stack[array_key_last($this->stack)])
            return $this;
        $this->rdx = round($this->stack[array_key_last($this->stack)], $this->RC);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // copy $ah to $rdx
    public function frstor()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // change top of stack to sin of top of stack
    public function fsin()
    {
        $this->ST0 = &$this->stack[array_key_last($this->stack)];
        $this->ST0 = ($this->ST0 != null) ? sin($this->ST0) : null;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // push cos of $ST0 to stack and fill $ST0 with sin of itself
    public function fsincos()
    {
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        array_push($this->stack, array("cos" => cos($this->ST0)));
        $this->ST0 = ($this->ST0 != null) ? sin($this->ST0) : null;
        next($this->stack);
        $this->ST0 = current($this->stack);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // round top 2 stack elements and push to rdx ans powers of 2
    public function fscale()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $sp1 = round($this->stack[count($this->stack)-2]);
        $sp0 = $this->stack[array_key_last($this->stack)];
        if (!is_numeric($sp1) || !is_numeric($sp0))
        {
            echo "Top 2 stack registers must be numeric for fscale()";
            return;
        }
        $this->rdx = pow(2,$sp0+$sp1);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // push to stack top value's sqrt
    public function fsqrt()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->stack[array_key_last($this->stack)] = sqrt($this->stack[array_key_last($this->stack)]);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // copy ST0 to another position ($ecx)
    public function fst()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ST0 = $this->stack[array_key_last($this->stack)];
        $this->stack[$this->ecx] = $this->ST0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // push $ah to $rdx
    public function fstcw()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // same as fst() but pops
    public function fstp()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->stack[$this->ecx] = $this->stack[array_key_last($this->stack)];
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // like it says ($ah - $ST0)
    public function subtract_pop()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if (!is_numeric($this->ah) || !is_numeric($this->stack[array_key_last($this->stack)]))
        {
            echo "\$ST0 & \$ah must be numeric for subtract_pop";
            return;
        }
        $this->rdx = $this->ah - ($this->ST0);
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // (same only reverse)
    public function subtract_rev_pop()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah) || !is_numeric($this->stack[array_key_last($this->stack)]))
        {
            echo "\$ST0 & \$ah must be numeric for subtract_rev_pop";
            return;
        }
        $this->rdx = $this->ST0 - $this->ah;
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // check that math works
    public function ftst()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->rdx))
        {
            echo "\$rdx must be numeric for ftst";
            return;
        }
        $this->rdx -= 0.0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // ecx == $sp and $rdx = $ST0
    public function fucom()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ecx) || !$this->stack[array_key_last($this->stack)])
            return $this;
        if (!is_float($this->stack[$this->ecx]) || !is_float($this->ST0))
            $this->CF = 7;
        $this->ecx = $this->sp;
        $this->rdx = $this->ST0;
        if (0 != ($this->ecx - $this->ah))  // Now derive carry flag
            $this->CF = ($this->ecx < $this->ah) ? 0 : 1;
        else
            $this->CF = 4;
            if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // above ith pop
    public function fucomp()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->fucom();
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // above with another pop
    public function fucompp()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->fucom();
        array_pop($this->stack);
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // get decimal value, without integer
    public function fxam()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah) || !$this->stack[array_key_last($this->stack)])
            return $this;
        $this->rdx = $this->ah - round($this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // exchange values from one stack place to another (the top)
    public function fxch()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ST0 = &$this->stack[array_key_last($this->stack)];
        $temp = $this->stack[$this->ecx];
        $this->stack[$this->ecx] = $this->ST0;  // goes into $this->ecx
        $this->ST0 = $temp;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // get highest significand and exponent of number
    public function fxtract()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ah) || !is_numeric($this->stack[array_key_last($this->stack)]))
        {
            echo "\$ST0 & \$ah must be numeric for fxtract";
            return;
        }
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        $ot = $this->ST0;
        $t = 1;
        $this->ah = $this->ST0;
        $significand = 0;
        $exponent = 0;
        $worked = "";
        while (0 < $ot) {
            $t = $this->ah;
            while ($t > 0) {
                $exponent = $t;
                $significand = $ot;
                if ($this->ah == pow($significand,$exponent)) {
                    $temp_sig = $significand;
                    $temp_exp = $exponent;
                }
                $t -= 1;
            }
            $ot -= 1;
        }
        $this->ST0 = $exponent;
        $this->stack[array_key_last($this->stack)] = $significand;
        array_push($this->stack, array("exp" => $exponent));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fyl2x()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ecx) || !is_numeric($this->ah))
            return $this;
        $this->rdx = $this->ecx * log($this->ah,2);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fyl2xp1()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ecx) || !is_numeric($this->ah))
            return $this;
        $this->rdx = $this->ecx * log($this->ah,2 + 1);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function hlt(string $async_filename, string $signal = null) {

        // Push the "signal" variable into the $async_filename(.json)
        // If it is anything but the "signal", it will stay halted
        // Use SESSID or cURL to change the file. (Remote Hosted)
        // Being Deprecated
        go_again:
            usleep(2500);
            try {
                $async = file_get_contents($async_filename);
                $async = json_decode($async);
            }
            catch (exception $e)
            {}
            if ($async->signal != $signal)
                goto go_again;
                if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // divide $ah / $ecx
    public function idiv()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ecx) || !is_numeric($this->ah))
            return $this;
        $this->rdx = $this->ah / $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // $ah * $ecx
    public function imul()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ecx) || !is_numeric($this->ah))
            return $this;
        $this->rdx = $this->ah * $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // $string is server, collects in $buffer
    public function in()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $socket = stream_socket_server($this->string, $err, $err_str);
        if (!$socket) {
            echo "$this->err ($this->err_str)<br />\n";
            $this->cl = 0;
            return $this;
        }
        else {
            while ($conn = stream_socket_accept($socket)) {
              $this->add_to_buffer(fread($conn, 1000));
              fclose($conn);
            }
            fclose($socket);
        }
        $this->cl = 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // increment $ecx
    public function inc()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_numeric($this->ecx))
            return $this;
        $this->ecx++;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // read 1 byte at a time
    public function in_b()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_string($this->string) || 0 == count($this->string))
        {
            echo "\$string must be numeric for in_b";
            return;
        }
        $socket = stream_socket_server($this->string, $err, $err_str);
        if (!$socket) {
            echo "$this->err ($this->err_str)<br />\n";
            $this->cl = 0;
            return $this;
        }
        else {
            while ($conn = stream_socket_accept($socket)) {
                $this->add_to_buffer( fread($conn, 1) );
              fclose($conn);
            }
            fclose($socket);
        }
        $this->cl = 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // read 1 dword at a time
    public function in_d()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_string($this->string) || 0 == count($this->string))
        {
            echo "\$string must be numeric for in_d";
            return;
        }
        $socket = stream_socket_server($this->string, $err, $err_str);
        if (!$socket) {
            echo "$this->err ($this->err_str)<br />\n";
            $this->cl = 0;
            return $this;
        }
        else {
            while ($conn = stream_socket_accept($socket)) {
                $this->add_to_buffer( fread($conn, 4) );
              fclose($conn);
            }
            fclose($socket);
        }
        $this->cl = 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // read word at a time
    public function in_w()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_string($this->string) || 0 == count($this->string))
        {
            echo "\$string must be numeric for in_w";
            return;
        }
        $socket = stream_socket_server($this->string, $this->err, $this->err_str);
        if (!$socket) {
            echo "$this->err_str ($this->err)<br />\n";
            $this->cl = 0;
            return $this;
        }
        else {
            while ($conn = stream_socket_accept($socket)) {
                $this->add_to_buffer( fread($conn, 2) );
              fclose($conn);
            }
            fclose($socket);
        }
        $this->cl = 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // read quad word at a time
    public function in_q()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_string($this->string) || 0 == count($this->string))
        {
            echo "\$string must be numeric for in_q";
            return;
        }
        $socket = stream_socket_server($this->string, $this->err, $this->err_str);
        if (!$socket) {
            echo "$this->err_str ($this->err)<br />\n";
            $this->cl = 0;
            return;
        }
        else {
            while ($conn = stream_socket_accept($socket)) {
                $this->add_to_buffer( fread($conn, 8) );
              fclose($conn);
            }
            fclose($socket);
        }
        $this->cl = 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function interrupt($async_filename)  // push $ecx into $file->signal for interrupts and async calls
    {
        if (!is_string($async_filename) || !is_numeric($this->ah))
            return $this;
        $async = file_get_contents($async_filename);
        $async = json_encode($async);
        $async->signal = $this->ecx;
        file_put_contents($async_filename,json_decode($async));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // write to file $string from $buffer
    public function write()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!is_string($this->buffer) && !is_numeric($this->buffer))
            return;

        file_put_contents($this->string, $this->buffer);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // read from file $this->string
    public function read()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (!file_exists($this->string)) {
            echo "Missing file: $this->string";
            return;
        }

        $this->buffer = file_get_contents($this->string);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // (really) move $buffer to stack
    public function mov_buffer()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        array_push($this->stack, $this->buffer);
        $this->buffer = "";
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // from here down to next letter, is jmp commands (obvious to anyone)
    public function ja()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah > $this->ecx) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jae()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah >= $this->ecx) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jb()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah < $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jbe()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->chain != null && $this->chain[$this->lop] == '' && $this->jbl == 1)
            $this->ecxl = 0;
        else
            return false;
        echo $this->chain[$this->lop]['function'];

        if ($this->ah <= $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
        }$this->coast();
        if ($this->pdb == 1)
            echo $this->lop . " " ;
        $this->lop++;
        return $this;
    }

    public function jc()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx == 1 && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jcxz()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah == $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function je()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah == $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jg()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah > $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jge()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah >= $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jl()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah < $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jle()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah < $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jmp()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
            $this->lop -= $this->ldp;
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if ($this->ecx != null && $this->ecx != null && $this->lop < count($this->chain)) {
            $func = $this->chain[$this->lop];
            if ($func == 'set')
                $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
            else
                $this->$func();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jnae()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah < $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jnb()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah >= $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jnbe()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah > $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jnc()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx == 0 && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jne()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah != $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jng()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah < $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jnl()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx > $this->ecx && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jno()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx == 0 && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jns()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx >= 0 && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jnz()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx != 0 && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jgz()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx > 0 && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jlz()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx < 0 && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jzge()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx >= 0 && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jzle()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx <= 0 && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jo()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx == 1 && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jpe()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx%2 == 0 && $this->ah%2 && $this->ecx%2 == 0) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jpo()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx%2 == 1 && $this->ah%2 == 1 && $this->ecx%2 == 1) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function jz()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx == 0 && $this->ah != null) {
            $this->lop -= $this->ldp;
            if ($this->ah != null && $this->ecx != null) {
                $func = $this->chain[$this->lop];
                if ($func == 'set')
                    $this->$func($this->args[$this->lop][0],$this->args[$this->lop][1]);
                else
                    $this->$func();
            }
            $this->jbl = 1;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // load all flags to $ah
    public function load_all_flags()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ah = ($this->OF) + ($this->CF * 2) + ($this->ZF * 4);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // reset all command chains
    public function end()
    {
        $this->chain = [];
        $this->args = [];
        $this->lop = 0;
    }

    // exit program
    public function leave()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        exit();
    }

    // move ecx to ah
    public function mov_ecx()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ah = $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // move ah to ecx
    public function mov_ah()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_str($str = "")  // mov ecx to $string
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->string = empty($str) ? $this->ecx : $str;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // the secret sauce. Go through rest of the commands after $ldp drop
    public function coast()
    {
        $counted = 0;
        $count = count($this->chain);
        while ($this->lop + $counted < $count) {
            $func = $this->chain[$this->lop + $counted];
            if ($func == 'set')
                $this->$func($this->args[$this->lop + $counted][0],$this->args[$this->lop + $counted][1]);
            else
                $this->$func();
            $counted++;
        }
        $this->ldp = 0;
        $this->counter = 0;
    }

    /**
     * This function requires that $this->ecx
     * be filled with a value > counter. Otherwise
     * it will not work out.
     */
    // loop til $counter == $ecx
    public function loop()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);

        $count = count($this->chain);
            $this->lop -= $this->ldp;
        if ($this->counter < $this->ecx && $this->lop + $this->counter < $count) {
            $func = $this->chain[$this->lop + $this->counter];
            if ($func == 'set')
                $this->$func($this->args[$this->lop + $this->counter][0],$this->args[$this->lop + $this->counter][1]);
            else
                $this->$func();
            $this->counter++;
        }
        $this->coast();
        $this->counter = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        return $this;
    }

    /*
     * This function requires that $this->ecx
     * be filled with a value == $this->ah. Otherwise
     * it will not work out. Change $this->ecx
     * in the previous function
    */
    // loop while ah == ecx
    public function loope()
    {
        $counter = 0;

        $count = count($this->chain);
            $this->lop -= $this->ldp;
        if ($this->ah == $this->ecx && $this->lop < $count) {
            $func = $this->chain[$this->lop + $this->counter];
            if ($func == 'set')
                $this->$func($this->args[$this->lop + $this->counter][0],$this->args[$this->lop + $this->counter][1]);
            else
                $this->$func();
            $this->counter++;
        }

        $this->coast();

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // loop while ah and ecx are not equal
    public function loopne()
    {
        $counter = 0;
        if (!is_numeric($this->ah) || !is_numeric($this->ah))
            return $this;

        $count = count($this->chain);
            $this->lop -= $this->ldp;
        if ($this->ah != $this->ecx && $this->lop < $count) {
            $func = $this->chain[$this->lop + $this->counter];
            if ($func == 'set')
                $this->$func($this->args[$this->lop + $this->counter][0],$this->args[$this->lop + $this->counter][1]);
            else
                $this->$func();
            $this->counter++;
        }

        $this->coast();

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // loop while ecx is not 0
    public function loopnz()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $counter = 0;
        if (!is_numeric($this->ah) || !is_numeric($this->ah))
            return $this;

        $count = count($this->chain);
            $this->lop -= $this->ldp;
        if (0 != $this->ecx && $this->lop < $count) {
            $func = $this->chain[$this->lop + $this->counter];
            if ($func == 'set')
                $this->$func($this->args[$this->lop + $this->counter][0],$this->args[$this->lop + $this->counter][1]);
            else
                $this->$func();
            $this->counter++;
        }
        $this->coast();

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // loop while ecx == 0
    public function loopz()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $counter = 0;
        if (!is_numeric($this->ah) || !is_numeric($this->ah))
            return $this;

        $count = count($this->chain);
            $this->lop -= $this->ldp;
        if (0 == $this->ecx && $this->lop < $count) {
            $func = $this->chain[$this->lop + $this->counter];
            if ($func == 'set')
                $this->$func($this->args[$this->lop + $this->counter][0],$this->args[$this->lop + $this->counter][1]);
            else
                $this->$func();
            $this->counter++;
            $this->coast();
        }

        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // another ah * ecx
    public function mul()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx *= $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // move $string to stack and clear
    public function movs()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        array_push($this->stack, $this->string);
        $this->string = "";
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function reset_sp()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        end($this->stack);
        $this->sp = current($this->stack);
        return $this;
    }

    // move $string to stack and clear
    public function movr()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        foreach ($this->array as $kv)
            $this->stack[count($this->stack)] = ($kv);
        $this->array = [];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function addr(array $ar)  // move $string to stack and clear
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        array_push($this->array, $ar);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // wait $wait microseconds
    public function mwait()
    {
        usleep($this->wait);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function nop()
    {
    }

    // performs a not on $ah ad ecx
    public function not()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx != $this->ah)
            $this->cl = 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // performs a or on ecx and ah
    public function or()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ecx or $this->ah)
            $this->cl = 1;
            if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // moves buffer to site $string
    public function out()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $socket = stream_socket_server($this->string, $err, $err_str);
        if (!$socket) {
            echo "$this->err ($this->err_str)<br />\n";
        }
        else {
            while ($conn = stream_socket_accept($socket)) {
              fwrite($conn, $this->buffer, strlen($this->buffer));
              fclose($conn);
            }
            fclose($socket);
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function obj_push(string $object, array $args) // push object to stack
    {
        $x = new \ReflectionClass($object);
        $x->newInstanceArgs($args);
        array_push($this->stack, array("obj" => $x));
    }

    // pop stack
    public function pop()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // push ecx to stack
    public function push()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        array_push($this->stack, $this->ecx);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // shift ah left ecx times
    public function shift_left()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ah = decbin($this->ah);
        if (strlen($this->ah) == 1) {
            $this->OF = 1;
            return $this;
        }
        while ($this->ecx-- > 0)
        {
            $this->ah = rtrim($this->ah,"0");
            $t = &$rhs[strlen($this->ah)-1];
            array_unshift($this->ah,$t);
            $this->CF = $this->CF ^ $t;
        }
        $this->ah = bindec($this->ah);
        $t = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // shift ah right ecx times
    public function shift_right()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ah = decbin($this->ah);
        if (strlen($this->ah) == 1) {
            $this->OF = 1;
            return $this;
        }
        while ($this->ecx-- > 0)
        {
            $this->ah = rtrim($this->ah,"0");
            $t = &$this->ah[strlen($this->ah)-1];
            $s = &$this->ah[strlen($this->ah)-2];
            array_push($this->ah,$t);
            array_shift($this->ah);
            array_unshift($this->ah,$t);
            $this->CF = $this->CF ^ $t ^ $s;
        }
        $this->ah = bindec($this->ah);
        $t = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // pull bit around ecx times on ah (left)
    public function mv_shift_left()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ah = decbin($this->ah);
        if (strlen($this->ah) == 1) {
            $this->OF = 1;
            return $this;
        }
        while ($this->ecx-- > 0)
        {
            $this->ah = rtrim($this->ah,"0");
            $t = &$this->ah[strlen($this->ah)-1];
            array_push($this->ah,$t);
            array_unshift($this->ah,$t);
            $this->CF = $this->CF ^ $t;
        }
        $this->ah = bindec($this->ah);
        $t = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function create_register(string $register, $value)
    {
        
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->$register = $value;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }
    public function mv_shift_right()    // same as above but (right)
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ah = decbin($this->ah);
        if (strlen($this->ah) == 1) {
            $this->OF = 1;
            return $this;
        }
        while ($this->ecx-- > 0)
        {
            $this->ah = rtrim($this->ah,"0");
            $t = &$this->ah[strlen($this->ah)-1];
            $s = &$this->ah[strlen($this->ah)-2];
            array_push($this->ah,$t);
            array_shift($this->ah);
            array_unshift($this->ah,$t);
            $this->CF = $this->CF ^ $t ^ $s;
        }
        $this->ah = bindec($this->ah);
        $t = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // run file on linux $ST0 is command and arguments are $string
    public function run()
    {
                                // $rdx is the output file to show what happened.
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B ". $this->ST0 . " " . $this->string, "r"));
        } else {
            exec($this->ST0 . " " . $this->string . " > /dev/null &", $this->output, $this->cl);
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // same as above but pop
    public function run_pop()
    {
                                    // again, $rdx is the output
        if (substr(php_uname(), 0, 7) == "Windows") {
            pclose(popen("start /B ". $this->ST0 . " " . $this->string . " > " . $this->rdx, "r"));
        } else {
            exec($this->ST0 . " " . $this->string . " > /dev/null &", $this->output, $this->cl);
        }
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // set flags from ah bits [0,2]
    public function set_flags()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->OF = $this->ah%2;
        $this->ah >>= 1;
        $this->CF = $this->ah%2;
        $this->ah >>= 1;
        $this->ZF = $this->ah%2;
        $this->ah >>= 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // bitewise left
    public function bitwisel()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx <<= $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // same right
    public function bitewiser()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->ecx >>= $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // next(string);
    public function scan_str()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->strp = next($this->string);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // next(string);
    public function reset_str()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        reset($this->string);
        $this->strp = current($this->string);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set($key, $new_value)   // set $key with $new_value
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->$key = $new_value;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // copy adx to ecx
    public function set_ecx_adx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ecx = $this->adx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set_ecx_rdx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ecx = $this->rdx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set_ecx_bdx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ecx = $this->bdx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set_ecx_cdx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ecx = $this->cdx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set_ecx_ddx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ecx = $this->ddx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set_ecx_edx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ecx = $this->edx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // copy adx to ecx
    public function set_ah_adx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ah = $this->adx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set_ah_rdx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ah = $this->rdx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set_ah_bdx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ah = $this->bdx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set_ah_cdx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ah = $this->cdx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set_ah_ddx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ah = $this->ddx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set_ah_edx()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        try {
            $this->ah = $this->edx;
        }
        catch (exception $e)
        {
            echo "#Register $key not in object...<br>Failing...";
            exit();
        }
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // set if ah is above ecx
    public function seta()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah > $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setae()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah >= $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setb()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah < $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setbe()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah <= $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setc()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->CF != 0)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function sete()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah == $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setg()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah > $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setge()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah >= $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setl()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah < $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setle()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah <= $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setna()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah < $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setnae()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah > $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setnb()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah > $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setnbe()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah >= $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setnc()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->CF == 0)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setne()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah != $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setng()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah <= $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setnge()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah < $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setnl()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah >= $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setnle()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah > $this->ecx)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setno()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->OF != 1)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setnp()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (decbin($this->ah) != decbin($this->ecx))
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setns()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah >= 0)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function seto()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->OF == 1)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setp()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (decbin($this->ecx) != decbin($this->ah))
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setpe()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (decbin($this->ecx) != decbin($this->ah) && $this->cl%2 == 0)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setpo()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if (decbin($this->ecx) != decbin($this->ah) && $this->cl%2 == 1)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function sets()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah < 0)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function setz()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah == 0)
            $this->cl = 1;
        else
            return $this;
        $this->rdx = $ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function stc()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->CF = 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // continue buffer string
    public function add_to_buffer()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->buffer .= $this->string;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function clear_buffer()
    {
        $this->buffer = "";
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // save state of $stack to file $string
    public function save_stack_file()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        file_put_contents($this->string, serialize(($this->stack)));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function subtract_byte() //
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->rdx = ($this->ecx - $this->ah)%256;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function subtract_word()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->rdx = ($this->ecx - $this->ah)%pow(2,16);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function subtract_double()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->rdx = ($this->ecx - $this->ah)%pow(2,32);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function subtract_quad()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->rdx = ($this->ecx - $this->ah)%pow(2,8);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_cl()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $this->cl = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function test_compare()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah == $this->ecx)
            $this->cl = 0;
        else if ($this->ah > $this->ecx)
            $this->cl = 1;
        else if ($this->ah >= $this->ecx)
            $this->cl = 2;
        else if ($this->ah < $this->ecx)
            $this->cl = 3;
        else if ($this->ah <= $this->ecx)
            $this->cl = 4;
            if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // thread php pages on demand on linux
    public function thread()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $x = "?";
        foreach ($this->ST0 as $key => $value)
        {
            $x .= "&$key=$value";
        }
        exec("php $this->string/$x > /dev/null &");
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // ah = $ah + ecx && rdx = ah
    public function xadd()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $temp = $this->ah;
        $this->rdx = $this->ah;
        $this->ah = $temp + $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // reverse ecx and ah
    public function exchange()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $temp = $this->ah;
        $this->ecx = $this->ah;
        $this->ah = $temp;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // xor $ah and ecx
    public function xor()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        if ($this->ah xor $this->ecx)
            $this->rdx = 1;
            if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // pop $ah times
    public function popcnt()
    {
        // Here we collect the current function name (all functions contain 1/2)
        array_push($this->chain, __METHOD__);

        $argv = [];
        for ($i = 0; $i < func_num_args(); $i++)
            array_push($argv,func_get_arg($i));

        // And if there are args we are putting them in $this->args
        array_push($this->args, $argv);

        $counter = count($this->stack);
        while (count($this->stack) > 0 && $this->ah < --$counter)
            array_pop($this->stack);
        $this->cl = 1;
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // do top of stack as function
    public function stack_func()
    {
        $this->ST0();
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // sync stack pointer
    public function stack_func_pos()
    {
        $this->sp = current($this->stack);
        $this->sp();
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }
}

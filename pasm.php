<?php

class PASM
{

    private $ZF = 0;    // Comparison Flag for Exchanges
    private $OF = 0;    // Overflow Flag
    private $CF = 0;    // Carry Flag
    private $counter = 0;   // Needed for loops
    private $chain = array();   // Chain of events in line use $this->end() to stop and start again
    private $args = array();    // Array to hold args for set variables
    public $stack = array();    // Stack
    public $array = array();    // Public array
    public $sp;         // Stack pointer
    public $ST0;        // LAST STACK ELEMENT
    public $pdb = 0;    // debug flag (DEFAULT FALSE)
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
    public $adx;    // Register
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

    public function get () {    // Useful for some testing
                                // Will be easier to just play around
                                // However this verifies all methods work
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
            $y = "$" . implode(',$',$results);
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

    public function print_var($var)  // mov ecx to $string
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        echo $this->$var . " ";
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    // All functions are 100% ASM derived
    // Together there are 225+ functions
    // Do to obvious nature of names and
    // functionality they will have a small
    // amount of documentation.
    public function char_adjust_addition()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);  // Here we collect the current function name (all functions contain 1/2)
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);    // And if there are args we are putting them in $this->args
        $this->rdx = chr(($this->ecx + $this->ah)%256);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this; 

    }

    public function addr(array $var)  // mov ecx to $string
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        echo $this->array = $var;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function movr()  // mov ecx to $string
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
       
        foreach ($this->array as $key => $val)
        {
            $this->stack[$key] = $val;
        }
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function carry_add()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->cl = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function add()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->rdx = $this->ecx + $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function and()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->cl = $this->ecx & $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function chmod()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        chmod($this->string, $this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function bit_scan_fwd()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if ($this->tp == null) {
            $this->tp = $this->qword;   // qword is used to look through a string via bit scanning
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

    public function bit_scan_rvr()                  // reverse of above
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function byte_rvr()                  // reverse byte
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $temp = decbin($this->ecx);
        $this->rdx = strrev($temp);
        $this->rdx = bindec($this->rdx);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function bit_test()                  // bit is filled in pointer
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        return $this->tp[$this->ah];
    }

    public function bit_test_comp()         // look thru byte and see the $ah'th bit
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $bo = decbin($this->ecx);
        $bo = $bo[$this->ah];
        $this->CF = (bool)($bo);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function bit_test_reset()    // Clear bit test flag
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $bo = decbin($this->ecx);
        $bo = $bo[$this->ah];
        $this->CF = (bool)($bo);
        $this->ecx = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function bit_test_set()                  // Test bit
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $bo = decbin($this->ecx);
        $bo = $bo[$this->ah];
        $this->CF = (bool)($bo);
        $this->ecx[$this->ah] = 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function call()                  // call any function
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (is_callable($this->ST0))
            return call_user_func($this->ST0(), $this->string, $this->ah);
    }

    public function cmp_mov_a()         // heck ah against top of stack
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = ($this->ah > $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_ae()    // same (documenting will continue below)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = ($this->ah >= $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_b()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = ($this->ah < $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_be()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = ($this->ah <= $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_e()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = ($this->ah == $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_nz()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = ($this->CF == 1 & $this->ah == $this->ST0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_pe()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = ($this->CF == 0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_po()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = ($this->CF == 1) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_s()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = ($this->ah < 0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_mov_z()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = ($this->ah > 0) ? $this->ah : $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function mov()   // move ah to ecx. Same as mov_ah()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function movabs()    // copy $ecx to stack
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        array_push($this->stack, array("movabs" => $this->ecx));
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function clear_carry()   // clear $CF
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->CF = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function clear_registers()   // make all registers 0
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->CF = $this->adx = $this->bdx = $this->cdx = $this->ddx = $this->edx = $this->rdx = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function comp_carry()    // negate $CF
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->CF = !($this->CF);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_e()         // bool of equality comparison (documentation continues below)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->cl = $this->ecx == $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_same()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->cl = $this->ecx == $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function cmp_xchg()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function decr()                  // decrement ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx--;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function divide()    // $ecx/$ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (is_numeric($this->ecx) && is_numeric($this->ah))
        $this->rdx = round($this->ecx/$this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function absf()                  // absolute value of $ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->rdx = abs($this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function addf()                  // add $ecx and $ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->rdx = $this->ecx + $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function round()         // round top stack to RC decimal
    {
        $this->ST0 = &$this->stack[array_key_last($this->stack)];
        $this->ST0 = round($this->ST0, $this->RC);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function round_pop()         // same but pop
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

    public function neg()   // negate $ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (is_numeric($this->ah))
            $this->rdx = $this->ah * (-1);
            if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function stack_cmov_b()                  // move on comparison (begins again below)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (count($this->stack) > 0)
            $this->ST0 = $this->stack[array_key_last($this->stack)];
        else
            $this->ST0 = null;
        if ($this->ST0 != null && $this->ah != $this->ST0)
            $this->rdx = $this->ah;
            return $this;
    }

    public function fcomp()         // subtract top of stack from $ah and pop
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);  
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

    public function cosine()    // change top of stack to cosine of top of stack
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ST0 = &$this->stack[array_key_last($this->stack)];
        $this->ST0 = ($this->ST0 != null) ? cos($this->ST0) : null;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function stack_pnt_rev()         // go reverse the stack
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        prev($this->stack);
        $this->sp = current($this->stack);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fdiv()                  // divide ST0 into $ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function fdiv_pop()                  // opposite as above and pop
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function fdiv_rev()                  // opposite of fdiv
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function fdiv_rev_pop()                  // same as above with po
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function add_stack()         // add top of stack to ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (!is_numeric($this->ah) || !$this->stack[array_key_last($this->stack)])
            return $this;
        $this->rdx = $this->ecx + $this->ST0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function ficomp()    // compare and pop
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if ($this->ST0 == $this->ah)
            $this->cl = 1;
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function stack_load() // stack with count on stack
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $key = "f" . count($this->stack);
        array_push($this->stack, array($key => $this->ecx));
        
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }
    
    public function fmul()                  // multiplies ecx and ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (!is_numeric($this->ah) || !$this->stack[array_key_last($this->stack)])
            return $this;
        $this->rdx = $this->ecx * $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function stack_pnt_fwd()         // moves stack pointer forward
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        next($this->stack);
        $this->sp = current($this->stack);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function store_int()         // subtracts $ST0 - 2-to-the-$ah and puts answer in $rdx 
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function store_int_pop() // same as above, but with pop
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function subtract_rev() // like subtract but backwards
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (!is_numeric($this->ah) || !is_numeric($this->ecx) || !$this->stack[array_key_last($this->stack)])
            return;
        $this->rdx = $this->ecx - $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function subtract()  // $ah - $ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (!is_numeric($this->ah) || !is_numeric($this->ecx) || !$this->stack[array_key_last($this->stack)])
            return;
        $this->rdx = $this->ah - $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fld1()  // pushes ecx+1 to stack
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        array_push($this->stack, array("logl2" => log(log(2))));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_logl2t()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        array_push($this->stack, array("logl2t" => log(2,10)));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_loglg2()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $e = M_E;
        array_push($this->stack, array("ln2" => log($e,2)));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_pi()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        array_push($this->stack, array("pi" => M_PI));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function float_test()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function fmul_pop() // ah * ecx and pop
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function clean_exceptions()  // clear exception bit
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $thiz->ZF = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function clean_reg() // clear cl
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->cl = 0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fnop()  // counts as function, does nothing but takes up space (like in assembly)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fpatan()    // gets arctan of $ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);

        $this->cl = atan($this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fptan() // gets tangent of ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->cl = tan($this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fprem() // look to documentation (Oracle Systems Manual)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function frndint()   // round top of stack into $rdx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (!is_numeric($this->ecx) || !$this->stack[array_key_last($this->stack)])
            return $this;
        $this->rdx = round($this->stack[array_key_last($this->stack)], $this->RC);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function frstor() // copy $ah to $rdx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fsin() // change top of stack to sin of top of stack
    {
        $this->ST0 = &$this->stack[array_key_last($this->stack)];
        $this->ST0 = ($this->ST0 != null) ? sin($this->ST0) : null;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fsincos() // push cos of $ST0 to stack and fill $ST0 with sin of itself
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

    public function fscale()    // round top 2 stack elements and push to rdx ans powers of 2
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function fsqrt() // push to stack top value's sqrt
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->stack[array_key_last($this->stack)] = sqrt($this->stack[array_key_last($this->stack)]);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fst() // copy ST0 to another position ($ecx)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        $this->stack[$this->ecx] = $this->ST0;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fstcw() // push $ah to $rdx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->rdx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fstp()  // same as fst() but pops
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->stack[$this->ecx] = $this->stack[array_key_last($this->stack)];
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function subtract_pop()  // like it says ($ah - $ST0)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function subtract_rev_pop() // (same only reverse)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function ftst()  // check that math works
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function fucom() // ecx == $sp and $rdx = $ST0
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function fucomp()    // above ith pop
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->fucom();
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fucompp()   // above with another pop
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->fucom();
        array_pop($this->stack);
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fxam()  // get decimal value, without integer
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (!is_numeric($this->ah) || !$this->stack[array_key_last($this->stack)])
            return $this;
        $this->rdx = $this->ah - round($this->ah);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fxch()  // exchange values from one stack place to another (the top)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ST0 = &$this->stack[array_key_last($this->stack)];
        $temp = $this->stack[$this->ecx];
        $this->stack[$this->ecx] = $this->ST0;  // goes into $this->ecx
        $this->ST0 = $temp;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function fxtract()   // get highest significand and exponent of number
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function idiv()  // divide $ah / $ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (!is_numeric($this->ecx) || !is_numeric($this->ah))
            return $this;
        $this->rdx = $this->ah / $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function imul()  // $ah * $ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (!is_numeric($this->ecx) || !is_numeric($this->ah))
            return $this;
        $this->rdx = $this->ah * $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function in()    // $string is server, collects in $buffer
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function inc()   // increment $ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (!is_numeric($this->ecx))
            return $this;
        $this->ecx++;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function in_b()  // read 1 byte at a time
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function in_d() // read 1 dword at a time
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function in_w()  // read word at a time
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function in_q()  // read quad word at a time
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function write() // write to file $string from $buffer
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if (!is_string($this->buffer) && !is_numeric($this->buffer))
            return;
    
        file_put_contents($this->string, $this->buffer);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function read()     // read from file $this->string
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function mov_buffer()    // (really) move $buffer to stack
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        array_push($this->stack, $this->buffer);
        $this->buffer = "";
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function ja()    // from here down to next letter, is jmp commands (obvious to anyone)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        
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

    public function load_all_flags()    // load all flags to $ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ah = ($this->OF) + ($this->CF * 2) + ($this->ZF * 4);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function end() {     // reset all command chains
        $this->chain = array();
        $this->args = array();
        $this->lop = 0;
    }

    public function leave() // exit program
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        exit();
    }

    public function mov_ecx()   // move ecx to ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ah = $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function mov_ah()    // move ah to ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_str()  // mov ecx to $string
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->string = $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function coast()     // the secret sauce. Go thru rest of commands after $ldp drop
    {
        $counted = 0;
        $count = count($this->chain);
        while ($this->lop + $counted < $count) {
            $func = $this->chain[$this->lop + $counted];
            if ($func == 'set')
                PAS::$func($this->args[$this->lop + $counted][0],$this->args[$this->lop + $counted][1]);
            else
                PAS::$func();
            $counted++;
        }
        $this->ldp = 0;
        $this->counter = 0;
    }

    /*
     * This function requires that $this->ecx
     * be filled with a value > counter. Otherwise
     * it will not work out.
    */
    public function loop()      // loop til $counter == $ecx
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
    public function loope()     // loop while ah == ecx
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

    public function loopne()    // loop while ah and ecx are not equal
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

    public function loopnz()    // loop while ecx is not 0
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function loopz()     // loop while ecx == 0
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function mul()   // another ah * ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx *= $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function movs()  // move $string to stack and clear
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        array_push($this->stack, $this->string);
        $this->string = "";
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function mwait()   // wait $wait microseconds
    {
        usleep($this->wait);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function nop() {}    //

    public function not()   // performs a not on $ah ad ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if ($this->ecx != $this->ah)
            $this->cl = 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function or()    // performs a or on ecx and ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if ($this->ecx or $this->ah)
            $this->cl = 1;
            if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function out()   // moves buffer to site $string
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function pop()   // pop stack
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        array_pop($this->stack);
        $this->ST0 = $this->stack[array_key_last($this->stack)];
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function push()  // push ecx to stack
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        array_push($this->stack, $this->ecx);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function shift_left()    // shift ah left ecx times
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function shift_right()   // shift ah right ecx times
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function mv_shift_left() // pull bit around ecx times on ah (left)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function mv_shift_right()    // same as above but (right)
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function run() {     // run file on linux $ST0 is command and arguments are $string
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

    public function run_pop() {     // same as above but pop
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

    public function set_flags()     // set flags from ah bits [0,2]
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function bitwisel()  // bitewise left
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx <<= $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function bitewiser() // same right
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->ecx >>= $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function scan_str()  // next(string);
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->strp = next($this->string);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function reset_str()  // next(string);
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        reset($this->string);
        $this->strp = current($this->string);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function set($key, $new_value)   // set $key with $new_value
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function set_ecx_adx()   // copy adx to ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function set_ah_adx()   // copy adx to ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function seta()  // set if ah is above ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->CF = 1;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function add_to_buffer() // continue buffer string
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function save_stack_file()   // save state of $stack to file $string
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        file_put_contents($this->string, serialize($this->stack));
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function subtract_byte() // 
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->rdx = ($this->ecx - $this->ah)%256;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function subtract_word()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->rdx = ($this->ecx - $this->ah)%pow(2,16);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function subtract_double()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->rdx = ($this->ecx - $this->ah)%pow(2,32);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function subtract_quad()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->rdx = ($this->ecx - $this->ah)%pow(2,8);
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function load_cl()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $this->cl = $this->ah;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function test_compare()
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function thread() // thread php pages on demand on linux
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function xadd()  // ah = $ah + ecx && rdx = ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $temp = $this->ah;
        $this->rdx = $this->ah;
        $this->ah = $temp + $this->ecx;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function exchange()  // reverse ecx and ah
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        $temp = $this->ah;
        $this->ecx = $this->ah;
        $this->ah = $temp;
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function xor() // xor $ah and ecx
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
        if ($this->ah xor $this->ecx)
            $this->rdx = 1;
            if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function popcnt()    // pop $ah times
    {
        array_push($this->chain, debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS)[0]['function']);
        array_push($this->args, debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT)[0]['args']);
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

    public function stack_func() {  // do top of stack as function
        $this->ST0();
        if ($this->pdb == 1)
            echo $this->lop . " ";
        $this->lop++;
        return $this;
    }

    public function stack_func_pos() {  // sync stack pointer
        $this->sp = current($this->stack);
        $this->sp();
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
}

?>

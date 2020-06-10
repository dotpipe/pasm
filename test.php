<?php

include_once('pasm.php');

    $x = new PASM();

    $x->set('ecx',3)    // REGISTER
        ->set('ldp',2)  // NUMBER OF COMMANDS TO GO BACK
        ->set('pdb',1)  // DEBUG FIELD
        ->set('rdx',3)  // REGISTER
        ->set('ah',2)   // REGISTER
        ->end();
    
    $y = "ecx";
    //print_r($x);
    $x->mov_ecx()->decr()->jne()->loope()->end();

    //print_r($x);
    $x->set('ecx',3)->set('ldp',1)->decr()->jne()->loope()->end();

    $x->set('ecx',3)->set('ldp',2)->decr()->mov_ecx()->decr()->jne()->loop();
    
    $x->set('ecx',3)->set('ldp',1)->decr()->mov_ecx()->decr()->jmp();
    
    $x->decr()->decr()->jgz()->decr()->set('ldp',7)->loopnz();
    
    $x->set('ecx',3)->set('ldp',1)->decr()->mov_ecx()->decr()->jmp()->loop()->create_register("eed", 3);
    
    print_r($x);
?> 

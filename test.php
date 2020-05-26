<?php

include_once('pasm.php');

    $x = new PASM();

    $x->set('ecx',3)
        ->set('ldp',2)
        ->set('pdb',1)
        ->set('rdx',3)
        ->set('ah',2)
        ->end();
    
    //print_r($x);
    $x->mov_ecx()->decr()->jne()->loope()->end();

    //print_r($x);
    $x->set('ecx',3)->set('ldp',1)->decr()->jne()->loope()->end();

    $x->set('ecx',3)->set('ldp',2)->decr()->mov_ecx()->decr()->jne()->loop();
    
    $x->set('ecx',3)->set('ldp',1)->decr()->mov_ecx()->decr()->jmp();
    
    $x->decr()->decr()->jgz()->decr()->set('ldp',7)->loopnz();
    
    $x->set('ecx',3)->set('ldp',1)->decr()->mov_ecx()->decr()->jmp()->loop();
    
    print_r($x);
?> 
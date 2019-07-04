<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_1 extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("General");
    }

function test () {
$a = $this->input->get('a');
$b = $this->input->get('b');
$c = $this->input->get('c');

$ary_a = explode(',', $a);
$ary_b = explode(',', $b);
$ary_c = explode(',', $c);

for ($i=0; $i < sizeof($ary_a); $i++) { 
$p1 = $ary_a[$i] * $ary_b[$i] ; 
$p2 = $ary_b[$i] * $ary_c[$i] ; 
$p3 = $ary_c[$i] * $ary_a[$i] ; 
$result[$i] = ( $p1 + $p2 + $p3 ) * 2 ; 
}

echo json_encode(array('ret' => 10000, 'msg' => 'Success', 'result' => $result));

}

}
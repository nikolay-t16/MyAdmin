<?php
require_once 'Transform.php';

/* @var $a Image_Transform_Driver_GD */
// factory pattern - returns an object
$a = Image_Transform::factory('GD');

// load the image file
$a->load("teste.jpg");


// scale image by percentage - 40% of its original size
// $a->scalebyPercentage(99);
 // $a->rotate(9);
  // $a->flip();
  // $a->mirror();
  // $a->fitX(100);
   // $a->fitY(100);
   // $a->crop(100,100,40,0);
   //$a->scaleByFactor(0.5);
    $a->scaleByX(300);

// displays the image
$a->display();


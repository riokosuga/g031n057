<?php
// 3で割り切れるならFizz
// 5で割り切れるならBuzz
// 両方で割り切れるならFizzBuzz　と表示

// 1から50まで試行
for($i = 1;$i <= 50;$i++){
  if($i % 15 == 0){
    echo "FizzBuzz<br />\n";
  }
  else if($i % 3 == 0){
    echo "Fizz<br />\n";
  }
  else if($i % 5 == 0){
    echo "Buzz<br />\n";
  }
  else{
    echo $i."<br />\n";
  }
}

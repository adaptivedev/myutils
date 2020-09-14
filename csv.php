<?php
$row = 0;
$names=[];
$total=0;
$ctotal=[];
$conv=array("CAD"=>0.76, "EUR"=>1.18, "JPY"=>0.0094, "USD"=>1);
echo "loading csv file: $argv[1]\n";
if (($handle = fopen($argv[1], "r")) !== FALSE) {
 while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
  $num = count($data);
  $name;
  $gross;
  $currency;
  for ($col=0; $col < $num; $col++) {
   $d = $data[$col];
   //echo "R".$row." L".$col." ".$d."\n";
   if($col==3) { 
    if($d!="") $name = $d;
   };
   if($col==6) $currency = $d;
   if($col==7) {
    $gross = $d;
    //if($name=="Antonios Danezis")
    if($row%2==0) {
     $convert = preg_replace("/[^0-9.]+/", "", $gross);
     $ctotal[$currency] += $convert ;
     $gross = $conv[$currency] * $convert;
     //if($currency!="USD") echo "$currency $gross from $convert at rate $conv[$currency]\n";
     $total += $gross;
     $names[$name] += (float) $gross;	
     //if($name=="Igor Zayarny")
     //echo "gross:$".$gross." name:".$name." currency:".$currency." ut:".$names[$name]." total:".$total."\n";
    }
   }
  }
  $row++;
 }
 fclose($handle);
}
//var_dump ($names);
//var_dump ($ctotal);
// CHECK C/TOTAL

$ct = 0;
$i=0;
echo "\ntotal per each currency:\n";
foreach($ctotal as $key => $val) {
 echo "$key ".number_format($val,2).", conv: $conv[$key]\n";
 $ct += $conv[$key] * $val;
 $i++;
}
echo "ctotal = $".number_format($ct,2)." check by currency totals\n";

$nt = 0;
$i=0;
echo "\ntotal by names:\n";
foreach($names as $key => $val) {
 echo "gross: $".number_format($names[$key],2)." $key\n";
 $nt += $names[$key];
 $i++;
}

echo "\nntotal = $".number_format($nt,2)." check by names totals\n";
echo "ctotal = $".number_format($ct,2)." check by currency totals\n";
echo "total  = $".number_format($total,2)."\n";

?>

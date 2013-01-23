<?PHP
/*
 +----------------------------------------------------------------------+
 | GOOSEBERRY SOURCEPAD --> Advanced Sourcecode - Editor                |
 +----------------------------------------------------------------------+
 | Copyright (c) 2002 Björn Brockmann. All rights reserved.             |
 +----------------------------------------------------------------------+
 | This source file is subject to the QPL Software License, Version 1.0,|
 | that is bundled with this package in the file QPL.txt. If you did    |
 | not receive a copy of this file, you may send an email to            |
 | license@project-gooseberry.de, so I can mail you a copy.             |
 | In short this license allows for free use of GOOSEBERRY SOURCEPAD    |
 | for all free open-source projects. Please note that QPL 1.0 does not | 
 | allow you to use GOOSEBERRY SOURCEPAD in a closed source commercial  |
 | product/project. If you would like to use GOOSEBERRY SOURCEPAD in a  |
 | commercial context please contact me for further details.            |
 +----------------------------------------------------------------------+
 | Author: Björn Brockmann < bjoern@project-gooseberry.de >             |
 | -------------------------------------------------------------------- |
 | Homepage: http://www.project-gooseberry.de                           |
 +----------------------------------------------------------------------+
*/


/*
Templateclass
it's simple, it's stupid... but works very well

Known Bugs/ Problems:
- In einem Loop sind mehrere Werte. Unterscheiden sich die Werte von der Länge, wird der
  Loop nur solange ausgeführt, bis das Ende des kleinsten Wertes erreicht ist.
  Ausnahme: Der Grösste Wert wird als erstes in den Loop eingefügt.
  Diese Einschränkung steckt in der function make in den 3 aufeinander folgenden
  for - schleifen. Es wird immer nur der erste Wert eines Array geprüft. Wenn dieser NULL ist,
  bricht die Abarbeitung ab.

ToDo:
- oben genanntes ausbessern
- eigene Methoden für Loops und normale Ersetzungen
- Es können auch Array übergeben werden
*/
class totemplate
{
	var $TemplateArray;
	var $TemplateLoopArray;
	
	function insert($Loop, $TemplateName, $ToInsert)
	{

	  if($Loop ==""){
	  $this -> TemplateArray[$TemplateName] = $ToInsert;
	  }

	  else{
	  $this -> TemplateLoopArray[$Loop][$TemplateName][] = $ToInsert;
	  }
	}


	function make($File)
	{

	  $Matrix = implode("",(file($File)));

	  if(is_array($this -> TemplateLoopArray)){
	    $KeysLoopname = array_keys($this -> TemplateLoopArray);
	    for($f = 0; $f < count($KeysLoopname); $f++){
              $Start = strpos($Matrix, "<!--{start:".$KeysLoopname[$f]."}-->");
	      $Stop = strpos($Matrix, "<!--{stop:".$KeysLoopname[$f]."}-->");
	      $LoopLength = $Stop - $Start;
	      $Loop = substr( $Matrix, $Start, $LoopLength);
	      $KeysLoopTemplate = array_keys($this -> TemplateLoopArray[$KeysLoopname[$f]]);
	      $KeysLoopValue = array_keys($this -> TemplateLoopArray[$KeysLoopname[$f]][$KeysLoopTemplate[0]]);
	      for($t = 0; $t < count($KeysLoopValue); $t++){
	        $Loopb = $Loop;
	        for($s = 0; $s < count($KeysLoopTemplate); $s++){
		  $Loopb = str_replace("<!--{".$KeysLoopTemplate[$s]."}-->", $this -> TemplateLoopArray[$KeysLoopname[$f]][$KeysLoopTemplate[$s]][$t], $Loopb);
	        }
	        $LoopFinal = $LoopFinal.$Loopb;
	      }
	      $Matrix = str_replace ($Loop, $LoopFinal, $Matrix);
	      $Matrix = str_replace ("<!--{start:".$KeysLoopname[$f]."}-->", "", $Matrix);
	      $Matrix = str_replace ("<!--{stop:".$KeysLoopname[$f]."}-->", "", $Matrix);
	      $LoopFinal ="";
	      $Matrix = str_replace ($Loop, $Start, $Matrix);
	    }
	  }

	  if(is_array($this -> TemplateArray)){
	    $Keys = array_keys($this -> TemplateArray);
	    for($i = 0; $i < count($Keys); $i++){
	      $Matrix = str_replace("<!--{".$Keys[$i]."}-->", $this -> TemplateArray[$Keys[$i]], $Matrix);
	    }
	  }
          return $Matrix;
        }

	 function flush()
	 {
		 unset($this -> TemplateArray);
		 unset($this -> TemplateLoopArray);
	 }


}
?>
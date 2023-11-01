<?php

$m = $_POST['m'];
$n =  $_POST['n'];
$tamGrilla = $m*$n;
$r1 = $_POST['r1'];
$c1 = $_POST['c1'];
$r2 = $_POST['r2'];
$c2 = $_POST['c2'];
$r3 = $_POST['r3'];
$c3 = $_POST['c3'];
$ans = 0;
$checkpoint1 = floor($m*$n/4);
$checkpoint2 = floor($m*$n/2);
$checkpoint3 = floor(3*$m*$n/4);

$grilla = matrix($m, $n);

function enRango($i,$j,&$grilla) {
    global $m, $n;
    if(($i >= 0 && $i < $m) && ($j >= 0 && $j < $n)) {
      return ($grilla[$i][$j] == 0);
    }
        
    return false;
}


function tieneUnicaSalida($i, $j, &$grilla) {

  if(!enRango($i,$j,$grilla) || ($i == 0 && $j == 1)) {
    return false;
  }
    
  $sum = 0;


  if(enRango($i+1, $j, $grilla)) {
    $sum += ($grilla[$i+1][$j] == 0);
  }
      
      
  if(enRango($i-1, $j, $grilla)) {
    $sum += ($grilla[$i-1][$j] == 0);
  }
      
      
  if(enRango($i, $j+1, $grilla)) {
    $sum += ($grilla[$i][$j+1] == 0);
  }
      
      
  if(enRango($i, $j-1, $grilla)) {
    $sum += ($grilla[$i][$j-1] == 0);
  }
      

  return ($sum == 1);

}


function distancia($i, $j, $k, $l) {
  return (abs($i-$k) + abs($j-$l));
}


function fuerzaBruta($i, $j, $paso, $grilla){

    global $m, $n, $tamGrilla, $r1, $c1, $r2, $c2, $r3, $c3, $ans, $checkpoint1, $checkpoint2, $checkpoint3;


    if($i == 0 && $j==1) {
        if($paso == $tamGrilla) {
            $grilla[$i][$j] = $paso;
            echo "<h3>Tour ";
            echo $ans+1;
            echo "</h3>";
            echo "<table>";

            for ($h=-1; $h<$n;$h++) {
              echo "<th>";
              if ($h != -1) {
                echo $h;
              }
              
              echo "</th>";
            }

            for ($k=$m-1; $k>=0; $k--) {
              echo "<tr><th>";
              echo $k;
              echo "</th>";
              for ($h=0; $h<$n;$h++) {
                if ($k == $r1 && $h == $c1) {
                  echo "<td class='rojo'>";
                } else if ($k == $r2 && $h == $c2) {
                  echo "<td class='rojo'>";
                } else if ($k == $r3 && $h == $c3) {
                  echo "<td class='rojo'>";
                } else {
                  echo "<td>";
                }
                
                echo $grilla[$k][$h];
                echo "</td>";
              }
              echo "</tr>";
            }
            echo "</table>";
            /*
            echo '<pre>'; print_r($grilla); echo '</pre>';
            */
            return 1;
        }
        else {
          return 0;
        }
          
    }

    //particion de mapa
    $arr = enRango($i+1, $j, $grilla); 
    $ab = enRango($i-1,$j,$grilla);
    $izq = enRango($i,$j-1,$grilla);
    $der = enRango($i,$j+1,$grilla);

    if( (($arr && $ab) && (!$izq && !$der)) || ((!$arr && !$ab) && ($izq && $der)) ) {
      return 0;
    }
        

    if(distancia($i, $j, 0, 1) > $tamGrilla - $paso) {
      return 0;
    }
        
    if($grilla[$r1][$c1] == 0 && distancia($i, $j, $r1, $c1) > $checkpoint1 - $paso) {
      return 0;
    }
        
    if($grilla[$r2][$c2] == 0 && distancia($i, $j, $r2, $c2) > $checkpoint2 - $paso) {
      return 0;
    }
        
    if($grilla[$r3][$c3] == 0 && distancia($i, $j, $r3, $c3) > $checkpoint3 - $paso) {
      return 0;
    }
        

    if($paso == $checkpoint1 && ($i != $r1 || $j != $c1)) {
      return 0;
    }
        
    if($paso == $checkpoint2 && ($i != $r2 || $j != $c2)) {
      return 0;
    }
        
    if($paso == $checkpoint3 && ($i != $r3 || $j != $c3)) {
      return 0;
    }
        
    
    if($i == $r1 && $j == $c1 && $paso != $checkpoint1) {
      return 0;
    }
        
    if($i == $r2 && $j == $c2 && ($grilla[$r1][$c1] == 0 || $paso != $checkpoint2)) {
      return 0;
    }
        
    if($i == $r3 && $j == $c3 && ($grilla[$r2][$c2] == 0 || $paso != $checkpoint3)) {
      return 0;
    }
        
    $grilla[$i][$j] = $paso;
    // EXPLICACIÓN DE ESTA PODA: Si algunos de los lugares adyacentes donde está actualmente el (i,j) tiene
    // ÚNICA SALIDA entonces voy directo a esa dirección (ya que no voy a poder volver para atrás si entro por esa otra salida después
    //  y se va a atorar de lo contrario)
    if((tieneUnicaSalida($i+1,$j, $grilla)+tieneUnicaSalida($i-1, $j, $grilla)+tieneUnicaSalida($i, $j+1, $grilla)+tieneUnicaSalida($i, $j-1, $grilla)) == 1){
        if(tieneUnicaSalida($i+1,$j, $grilla)) {
          $ans += fuerzaBruta($i+1, $j, $paso + 1, $grilla);

        }
            
        if(tieneUnicaSalida($i-1, $j, $grilla)) {
          $ans += fuerzaBruta($i-1, $j, $paso + 1, $grilla);
        }
            
        if(tieneUnicaSalida($i, $j+1, $grilla)) {
          $ans += fuerzaBruta($i, $j+1, $paso + 1, $grilla);
        }
            
        if(tieneUnicaSalida($i, $j-1, $grilla)) {
          $ans += fuerzaBruta($i, $j-1, $paso + 1, $grilla);
        }
            
    } else {
        if($arr) {
          $ans += fuerzaBruta($i+1, $j, $paso + 1, $grilla);
        }
            

        if($ab) {
          $ans += fuerzaBruta($i-1, $j, $paso + 1, $grilla);
        }
            

        if($der) {
          $ans += fuerzaBruta($i, $j+1, $paso + 1, $grilla);
        }
            

        if($izq) {
          $ans += fuerzaBruta($i, $j-1, $paso + 1, $grilla);
        }
            
    }

    return 0;

}

function matrix($m, $n, $value = 0) {
  return array_fill(0, $m, array_fill(0, $n, $value));
}

fuerzaBruta(0,0,1,$grilla);

if ($ans == 0) {
  echo "<p>There are no valid tours for the given input.</p>";
}

?>
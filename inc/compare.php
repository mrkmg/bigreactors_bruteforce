<?php

function compareReactorsResults($rA, $rB)
{

    $powerA = $rA->getResult()['power'];
    $powerB = $rB->getResult()['power'];

    $efficiencyA = $rA->getResult()['efficiency'];
    $efficiencyB = $rB->getResult()['efficiency'];

    $bothA = $powerA + ($efficiencyA / 10);
    $bothB = $powerB + ($efficiencyB / 10);

//    if (abs(($powerA - $powerB)/$powerA) < .1)
//        return $efficiencyA > $efficiencyB;

    return $bothA > $bothB;
}

function secs_to_h($secs)
{
    $units = array(
        "week"   => 7*24*3600,
        "day"    =>   24*3600,
        "hour"   =>      3600,
        "minute" =>        60,
        "second" =>         1,
    );

    // specifically handle zero
    if ( $secs == 0 ) return "0 seconds";

    $s = "";

    foreach ( $units as $name => $divisor ) {
        if ( $quot = intval($secs / $divisor) ) {
            $s .= "$quot $name";
            $s .= (abs($quot) > 1 ? "s" : "") . ", ";
            $secs -= $quot * $divisor;
        }
    }

    return substr($s, 0, -2);
}

function factorial($x) {
    $y = $x;
    while($y > 1) {
        $x *= ($y - 1);
        $y--;
    }
    return $x;
}



function perm($count1, $count2, $type1, $type2, $callable)
{
    $total = $count1 + $count2;

    $arr = array_fill(0, $count1, $type1) + array_fill($count1, $count2, $type2);

    $slide = array($count1);

    //Main Loop
    while (true)
    {

        $callable(implode('', $arr));


        //move furthest type 1
        $continue = false;
        for($i = $slide[0] - 1; $i >= 0; $i--)
        {
            if ($arr[$i] == $type1 && $arr[$i+1] == $type2)
            {
                array_unshift($slide, $i+1);
                $arr[$i] = $type2;
                $arr[$i+1] = $type1;
                $continue = true;
                break;
            }
        }

        if ($continue) continue;


        while (count($slide))
        {
            $i = $slide[0];

            if ($i+1 == $total) break 2;// all done

            if ($arr[$i + 1] == $type2)
            {
                array_shift($slide);
                $arr[$i] = $type2;
                $arr[$i+1] = $type1;
                array_unshift($slide, $i+1);

                //reset under

                for ($ii = $i; $ii >= 0; $ii--)
                {
                    if ($arr[$ii] == $type1)
                    {
                        for ($iii = 0; $iii < $ii; $iii++)
                        {
                            if ($arr[$iii] == $type2)
                            {
                                $arr[$iii] = $type1;
                                $arr[$ii] = $type2;
                                break;
                            }
                        }
                    }
                }


                break;
            }
            else
            {
                array_shift($slide);
            }
        }

    }

}

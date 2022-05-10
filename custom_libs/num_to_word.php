<?php if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']) header("Location: noPage");

function num_to_word($no)
{
    $words = array(
        '0'        => '' ,
        '1'        => 'One',
        '2'        => 'Two',
        '3'        => 'Three',
        '4'        => 'Four',
        '5'        => 'Five',
        '6'        => 'Six',
        '7'        => 'Seven',
        '8'        => 'Eight',
        '9'        => 'Nine',
        '10'       => 'Ten',
        '11'       => 'Eleven',
        '12'       => 'Twelve',
        '13'       => 'Thirteen',
        '14'       => 'Fouteen',
        '15'       => 'Fifteen',
        '16'       => 'Sixteen',
        '17'       => 'Seventeen',
        '18'       => 'Eighteen',
        '19'       => 'Nineteen',
        '20'       => 'Twenty',
        '30'       => 'Thirty',
        '40'       => 'Fourty',
        '50'       => 'Fifty',
        '60'       => 'Sixty',
        '70'       => 'Seventy',
        '80'       => 'Eighty',
        '90'       => 'Ninty',
        '100'      => 'Hundred',
        '1000'     => 'Thousand',
        '100000'   => 'Lakh',
        '10000000' => 'Crore'
        );
    if($no == 0) {
        return ' ';
    } else {
        $novalue  = '';
        $highno   = $no;
        $remainno = 0;
        $value    = 100;
        $value1   = 1000;

        while($no>=100) {
            if(($value <= $no) &&($no < $value1)) {
                $novalue  = $words["$value"];
                $highno   = (int)($no/$value);
                $remainno = $no % $value;
                break;
            }
            $value  = $value1;
            $value1 = $value * 100;
        }
        if(array_key_exists("$highno",$words)) {
            return $words["$highno"]." ".$novalue." ".num_to_word($remainno) .' only';
        } else {
            $unit = $highno%10;
            $ten  = (int)($highno/10)*10;
            return $words["$ten"]." ".$words["$unit"]." ".$novalue." ".num_to_word($remainno) .' only';
        }
    }
}
?>
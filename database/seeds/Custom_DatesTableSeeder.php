<?php

use Illuminate\Database\Seeder;

class Custom_DatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    function feiertage($jahr) {
		$tage = 60 * 60 * 24;
		$ostersonntag = easter_date($jahr);
		$Feiertage=array(); 
		// Feste Feiertage   ----IF einfügen
		
		    $Feiertage[0][1]=date("Y-m-d", mktime(0, 0, 0, 1, 1, $jahr));
		    $Feiertage[1][1]="Neujahr";
		    $Feiertage[2][1]="nj";
		    $Feiertage[3][1]="1";
		
		    $Feiertage[0][]=date("Y-m-d", mktime(0, 0, 0, 1, 6, $jahr));
		    $Feiertage[1][]="Heilige drei Könige";
		    $Feiertage[2][]="hk";
			$Feiertage[3][]="2";

		    $Feiertage[0][]=date("Y-m-d", mktime(0, 0, 0, 5, 1, $jahr));
		    $Feiertage[1][]="Tag der Arbeit";
		    $Feiertage[2][]="ta";
			$Feiertage[3][]="3";

		    $Feiertage[0][]=date("Y-m-d",mktime(0, 0, 0, 8, 8, $jahr));
		    $Feiertage[1][]="Augsburger Friedensfest";
		    $Feiertage[2][]="af";
		    $Feiertage[3][]="4";
		
		    $Feiertage[0][]=date("Y-m-d",mktime(0, 0, 0, 8, 15, $jahr));
		    $Feiertage[1][]="Mariä Himmelfahrt";
		    $Feiertage[2][]="mh";
		    $Feiertage[3][]="5";
		
		    $Feiertage[0][]=date("Y-m-d",mktime(0, 0, 0, 10, 3, $jahr));
		    $Feiertage[1][]="Tag der Deutschen Einheit";
		    $Feiertage[2][]="te";
		    $Feiertage[3][]="6";
		
		    $Feiertage[0][]=date("Y-m-d",mktime(0, 0, 0, 10, 31, $jahr));
		    $Feiertage[1][]="Reformatsionstag";
		    $Feiertage[2][]="rt";
		    $Feiertage[3][]="7";
		
		    $Feiertage[0][]=date("Y-m-d",mktime(0, 0, 0, 11, 1, $jahr));
		    $Feiertage[1][]="Allerheiligen";
		    $Feiertage[2][]="ah";
		    $Feiertage[3][]="8";
		
		    $Feiertage[0][]=date("Y-m-d",mktime(0, 0, 0, 11, 26+(7-date('w', mktime(0, 0, 0, 11, 26, $jahr)))-11, $jahr)); //aus dem Internet=>nicht überprüft
		    $Feiertage[1][]="Buß- und Bettag";
		    $Feiertage[2][]="bb";
		    $Feiertage[3][]="9";
		
		    $Feiertage[0][]=date("Y-m-d",mktime(0, 0, 0, 12, 24, $jahr));
		    $Feiertage[1][]="Heiligabend";
		    $Feiertage[2][]="ha";
		    $Feiertage[3][]="10";
		
		    $Feiertage[0][]=date("Y-m-d",mktime(0, 0, 0, 12, 25, $jahr));
		    $Feiertage[1][]="1. Weihnachtsfeiertag";
		    $Feiertage[2][]="w1";
		    $Feiertage[3][]="11";
		
		     $Feiertage[0][]=date("Y-m-d",mktime(0, 0, 0, 12, 26, $jahr));
		     $Feiertage[1][]="2. Weihnachtsfeiertag";
		     $Feiertage[2][]="w2";
		     $Feiertage[3][]="12";
		
		    $Feiertage[0][]=date("Y-m-d",mktime(0, 0, 0, 12, 31, $jahr));
		    $Feiertage[1][]="Sylvester";
		    $Feiertage[2][]="sy";
		    $Feiertage[3][]="13";
		
		 //Bewegliche Feiertage
		
		    $Feiertage[0][]=date("Y-m-d",$ostersonntag - 3 * $tage);
		    $Feiertage[1][]="Gründonnerstag";
		    $Feiertage[2][]="gd";
		    $Feiertage[3][]="14";
		
		    $Feiertage[0][]=date("Y-m-d",$ostersonntag - 2 * $tage);
		    $Feiertage[1][]="Karfreitag";
		    $Feiertage[2][]="kf";
		    $Feiertage[3][]="15";
		
		    $Feiertage[0][]=date("Y-m-d",$ostersonntag);
		    $Feiertage[1][]="Ostersonntag";
		    $Feiertage[2][]="os";
		    $Feiertage[3][]="16";
		
		    $Feiertage[0][]=date("Y-m-d",$ostersonntag + 1 * $tage);
		    $Feiertage[1][]="Ostermontag";
		    $Feiertage[2][]="om";
		    $Feiertage[3][]="17";
		
		    $Feiertage[0][]=date("Y-m-d",$ostersonntag + 39 * $tage);
		    $Feiertage[1][]="Christi Himmelfahrt";
		    $Feiertage[2][]="ch";
		    $Feiertage[3][]="18";
		
		    $Feiertage[0][]=date("Y-m-d",$ostersonntag + 50 * $tage);
		    $Feiertage[1][]="Pfingstmontag";
		    $Feiertage[2][]="pm";
		    $Feiertage[3][]="19";
		
		    $Feiertage[0][]=date("Y-m-d",$ostersonntag + 60 * $tage);
		    $Feiertage[1][]="Fronleichnam";
		    $Feiertage[2][]="fl";
		    $Feiertage[3][]="20";
		
		return $Feiertage;
	}
        for ($i=2014; $i < 2032; $i++) 
        { 
			$ft=feiertage($i);
        	foreach ($ft[0] as $key => $date) { 
        		DB::table('custom_dates')->insert([
        		    'date' => $date,
        		    'name' => $ft[1][$key],
        		    'shortname' => $ft[2][$key],
        		    'items_id' => $ft[3][$key],
        		]);
        	}
        }
    }
}

<?php

use Illuminate\Database\Seeder;

class CustomDatesItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        function feiertage() {
		
		// Feste Feiertage   ----IF einfügen
				    
		    $Feiertage[1][]="Neujahr";
		    $Feiertage[2][]="nj";		
		    
		    $Feiertage[1][]="Heilige drei Könige";
		    $Feiertage[2][]="hk";
			
		    
		    $Feiertage[1][]="Tag der Arbeit";
		    $Feiertage[2][]="ta";
			
		    $Feiertage[1][]="Augsburger Friedensfest";
		    $Feiertage[2][]="af";
		    
		    $Feiertage[1][]="Mariä Himmelfahrt";
		    $Feiertage[2][]="mh";
		    
		    $Feiertage[1][]="Tag der Deutschen Einheit";
		    $Feiertage[2][]="te";
		    
		    $Feiertage[1][]="Reformatsionstag";
		    $Feiertage[2][]="rt";
		    
		    $Feiertage[1][]="Allerheiligen";
		    $Feiertage[2][]="ah";
		   
		    $Feiertage[1][]="Buß- und Bettag";
		    $Feiertage[2][]="bb";
		    
		    $Feiertage[1][]="Heiligabend";
		    $Feiertage[2][]="ha";
		    
		    $Feiertage[1][]="1. Weihnachtsfeiertag";
		    $Feiertage[2][]="w1";
		   
		     $Feiertage[1][]="2. Weihnachtsfeiertag";
		     $Feiertage[2][]="w2";
		    
		    $Feiertage[1][]="Sylvester";
		    $Feiertage[2][]="sy";
		    
		
		 //Bewegliche Feiertage
		
		    $Feiertage[1][]="Gründonnerstag";
		    $Feiertage[2][]="gd";
		    
		    $Feiertage[1][]="Karfreitag";
		    $Feiertage[2][]="kf";
		    
		    $Feiertage[1][]="Ostersonntag";
		    $Feiertage[2][]="os";
		    
		    $Feiertage[1][]="Ostermontag";
		    $Feiertage[2][]="om";
		    
		    $Feiertage[1][]="Christi Himmelfahrt";
		    $Feiertage[2][]="ch";
		    
		    $Feiertage[1][]="Pfingstmontag";
		    $Feiertage[2][]="pm";
		    
		    $Feiertage[1][]="Fronleichnam";
		    $Feiertage[2][]="fl";
		
			return $Feiertage;
		}

        $ft=feiertage();
        foreach ($ft[1] as $key => $name) { 
        	DB::table('custom_dates_items')->insert([
        	    'name' => $ft[1][$key],
        	    'shortname' => $ft[2][$key],
        	]);
        }
    }
}

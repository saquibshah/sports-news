﻿#!/usr/local/bin/php
<?php 
//$xmlFile = 'livescore_betitbest.xml'; 
//$xmlFile='../scripts/xmlimporter/errors/livescore_betitbest.xml';
$xmlFile='/opt/users/www/betitbest/xmls/sportradar/ls/livescore_betitbest.xml';
//echo time()."<br>";
$xmltime=0;
$sessionTime=-1;
$tournament="";
$country="";
$m_timestamp=0;
$p_timestamp=0;
$teamnamehome="";
$teamnameaway="";
$currenthome="-";
$currentaway="-";
$scorehome="-";
$scoreaway="-";
$status="";
$matchId=0;
$LigaId=0;
$UniqueTournamentId=0;
$CategoryId=0;
$winner="";
$winnername="";
$lastgoalteam=0;
$lastgoaltime=0;
$minscored=0;
$lastgoalscoredby="";
echo time()."- start importing livescores.\n";
if (file_exists($xmlFile)) { 
    $xml = simplexml_load_file($xmlFile); 
	/*
										$eintragen=date('d.M.Y-H:i:s',time())." - delta_xml Datei vorhanden.\nBeginne Verarbeitung..";
										$logname="/opt/users/www/betitbest-news/livescores/importer/log/darts_import_main_xml2db.log";
										$datei=fopen($logname,"w");
										fputs($datei,"$eintragen\n\n");
										fclose($datei);
	*/
     echo "<br>xml Datei vorhanden\n";
$link = mysql_connect('localhost', 'db1029865-news', '8WYYMBxQDyj5');
	//$link = mysql_connect('localhost', 'root', '');
	    if (!$link) {
	    //if (!$dbh) {
	        die('<br/>Verbindung schlug fehl: ' . mysql_error());
	    } else {
	        echo '<br/>Verbindung steht';
	    }
	//mysql_select_db('test');
	mysql_select_db('db1029865-sportnews');
	$query = "SET NAMES 'utf8'";
	mysql_query($query);

	if($xml) { 
           		  //if($BetradarLivescoreData->generatedAt[0]!="") { Den ganzen Block bitte nur direkt unter der if($xml) Schleife legen. Es werden keine anderen Schelifen benötigt
					$generatedTime=$xml['generatedAt'];
					//echo "<br>test".$generatedTime;
					$XMLDATE=$generatedTime;
					$x_jahr=substr($XMLDATE,0,4);
					$x_monat=substr($XMLDATE,5,2);
					$x_tag=substr($XMLDATE,8,2);
					$x_stunde=substr($XMLDATE,11,2);
					$x_minute=substr($XMLDATE,14,2);
					$xmltime= mktime($x_stunde,$x_minute,0,$x_monat,$x_tag,$x_jahr);
			echo "<br>XMLZEIT: ".$xmltime;   // hier hast du also deine XML Zeit, die du weiter unten in die DB einbauen kannst!!!
			
			//			foreach($xml->Tournament as $sport) {
			foreach ($xml->children() as $sport) {
			if ($sport->Name=="Darts" ){echo "<br>Sportart:Darts";
				foreach ($sport->children() as $category) {
						//echo "category: ";
					$CategoryId=0;
					$country="No name found";
					
						//echo "category: ";
					if($category->Name!=""){
					    
						$country=$category->Name;
						$CategoryId = $category->attributes();
						echo "<br>Organisation: ".$country;
						echo "<br>CategoryId: ".$CategoryId;
					    
						foreach ($category->children() as $tournament) {
							
							$LigaId=0;
							$UniqueTournamentId2db=0;
							$tournamentname="No name found";
							
							$LigaId=$tournament->attributes();
							$UniqueTournamentId=$tournament->attributes();
							$UniqueTournamentId2db=$UniqueTournamentId['UniqueTournamentId'];
								if($LigaId['BetradarTournamentId']!=""){
									echo "<br>TurnierId: ".$LigaId['BetradarTournamentId'];	
									echo "<br>UniqueTournamentId: ".$UniqueTournamentId['UniqueTournamentId'];
									//echo $tournament->Name;
									//echo time();
						$tournamentname=$tournament->Name[0];		
						echo "<br>tournamentname: ".$tournamentname;	
							foreach ($tournament->children() as $match){
								$p1_home=0;
													$p1_away=0;
													$p2_home=0;
													$p2_away=0;
													$p3_home=0;
													$p3_away=0;
													$p4_home=0;
													$p4_away=0;
													$p5_home=0;
													$p5_away=0;
													$pointhome=0;
													$pointaway=0;
                                                                $matchId=$match->attributes();
                                                                
                                                                //Ignore tournament name node
                                                                if (isset($matchId['language'])) continue;
                                                                
								$uniqueTeamIdHome=$match->Team1->attributes();
								$uniqueTeamIdAway=$match->Team2->attributes();
								if($match->MatchDate[0]!="" && $match->Team1->Name[0]!="" && $match->Team2->Name[0]!=""){
									//normalisiere Datum aus Form: 2014-03-28T20:30:00 CET
									$DateFromXml=$match->MatchDate[0];
									$jahr=substr($DateFromXml,0,4);
									$monat=substr($DateFromXml,5,2);
									$tag=substr($DateFromXml,8,2);
									$stunde=substr($DateFromXml,11,2);
									$minute=substr($DateFromXml,14,2);
									$m_timestamp= mktime($stunde,$minute,0,$monat,$tag,$jahr);
									//echo $match->MatchDate[0];
									
									//start of replace
									$teamnamehome=$match->Team1->Name[0];													
									$teamnamehome=substr($teamnamehome,0,50);
									//to replace quote mark from xml attribut as string value
										$findstr  = "'";
										$pos = strpos($teamnamehome, $findstr);
										
										if ($pos !== false) {
											$teamnamehome = str_replace($find, " ", $teamnamehome);;
										}
									echo "<br>".$teamnamehome;
									//end of replace
									
									$teamnameaway=$match->Team2->Name[0];
									$teamnameaway=substr($teamnameaway,0,50);
									//to replace quote mark from xml attribut as string value
										$findstr  = "'";
										$pos = strpos($teamnameaway, $findstr);
										
										if ($pos !== false) {
											$teamnameaway = str_replace($find, " ", $teamnameaway);;
										}
									echo " - ".$teamnameaway;
									//end of replace
									
									if ($match->Winner=="0"){
									$winner=$match->Winner;
									}
									if ($match->Winner=="1"){
									$winner=$match->Winner;
									$winnername=$match->Team1->Name[0];
									}
									else if ($match->Winner=="2"){
									$winner=$match->Winner;
									$winnername=$match->Team2->Name[0];
									}
									
									//echo $match->Team1->Name[0]."</td><td class=\"ls_team2\">".$match->Team2->Name[0]."</td><td class=\"ls_status_result\">";							
										if ($match->Status->Name=="Not started"){
										$status=$match->Status->Name;
										//echo " - : - </td></tr>";
										echo "<br>Status: ".$status;
										echo "<br>Start: ".$match->MatchDate[0];
										}
										else {
											$status=$match->Status->Name;
											echo "<br>Matchstatus:".$status;
											
												//wenn status erste oder zweite HZ, dann ermittle acttime-periodstarttime
												/*if($status=="1st half" || $status=="2nd half" || $status=="1st extra" || $status=="2nd extra"){
													//normiere PeriodStartZeit und wandel in timestamp
													$timePlayedXml=$match->CurrentPeriodStart;
													$p_jahr=substr($timePlayedXml,0,4);
													$p_monat=substr($timePlayedXml,5,2);
													$p_tag=substr($timePlayedXml,8,2);
													$p_stunde=substr($timePlayedXml,11,2);
													$p_minute=substr($timePlayedXml,14,2);
													$p_timestamp= mktime($p_stunde,$p_minute,0,$p_monat,$p_tag,$p_jahr);
													$sessionTime=round((time()-$p_timestamp)/60);
													//echo "<tr><th colspan=\"3\">timeplayed".$p_timestamp."-".$sessionTime."</th></tr>";
													echo $sessionTime;
													/*$lastgoalteam=$match->LastGoal->Team;
													
													$lastgoaltime_raw=$match->LastGoal->Time;
													
													$lg_jahr=substr($lastgoaltime_raw,0,4);
													$lg_monat=substr($lastgoaltime_raw,5,2);
													$lg_tag=substr($lastgoaltime_raw,8,2);
													$lg_stunde=substr($lastgoaltime_raw,11,2);
													$lg_minute=substr($lastgoaltime_raw,14,2);
													$lastgoaltime= mktime($lg_stunde,$lg_minute,0,$lg_monat,$lg_tag,$lg_jahr);
													
												
													
												}*/
												
											if($status=="Ended" || $status=="Started" || $status=="1st set" || $status=="2nd set" || $status=="3rd set" || $status=="4th set" || $status=="5th set" || $status=="Pause" || $status=="AGS"){	
										
												$lastgoalteam=$match->LastGoal->Team;
										
											foreach($match->children() as $scores){
													
													
													
										
												if($scores->Score[0]->Team1!="" && $scores->Score[0]->Team2!=""){
										//			
													foreach($scores->children() as $satzergebnis){
														echo "<br>Satzergebnis ->".$satzergebnis->Team1." : ".$satzergebnis->Team2."<br>";
													}
													//echo $scores->Score->Team1." : ".$scores->Score->Team2."</td></tr>";
													echo "aktueller Satz:".$satzergebnis->Team1." : ".$satzergebnis->Team2."</td></tr>";
													$currenthome=$satzergebnis->Team1;
													echo "aktueller Satz home:".$currenthome."</td></tr>";
													$currentaway=$satzergebnis->Team2;
													echo "aktueller Satz away:".$currentaway."</td></tr>";
													$scorehome=$scores->Score->Team1;
													$scoreaway=$scores->Score->Team2;
													echo "<br>Score: ".$scorehome." : ".$scoreaway;
													//if($scores->Score->Team1!="0" || $scores->Score->Team2!="0"){
													$count=count($scores->children());
													echo "<br>Count: ".$count;
													$count = (int) $count;
													$c=0;
													/*$p1_home=0;
													$p1_away=0;
													$p2_home=0;
													$p2_away=0;
													$p3_home=0;
													$p3_away=0;
													$p4_home=0;
													$p4_away=0;
													$p5_home=0;
													$p5_away=0;
													*/
													$lastgoalteam=$match->LastGoal->Team;
													$lastscoring=0;
													$actserving=0;
														while ($c<$count){
															/*if($c!=0){
															echo "<br>Satz ".$c." -> ".$scores->Score[$c]->Team1." : ".$scores->Score[$c]->Team2;
															}*/
															
																$scoreattr=$scores->Score[$c]->attributes();
																if ($scoreattr=="Period1")
																{
																	$p1_home=$scores->Score[$c]->Team1;
																	$p1_away=$scores->Score[$c]->Team2;
																	echo "<br>Satz ".$c." -> ".$scores->Score[$c]->Team1." : ".$scores->Score[$c]->Team2;
																	}	
																else if ($scoreattr=="Period2")
																{
																	$p2_home=$scores->Score[$c]->Team1;
																	$p2_away=$scores->Score[$c]->Team2;
																	echo "<br>Satz ".$c." -> ".$scores->Score[$c]->Team1." : ".$scores->Score[$c]->Team2;
																	}	
																	else if ($scoreattr=="Period3")
																{
																	$p3_home=$scores->Score[$c]->Team1;
																	$p3_away=$scores->Score[$c]->Team2;
																	echo "<br>Satz ".$c." -> ".$scores->Score[$c]->Team1." : ".$scores->Score[$c]->Team2;
																	}
																	else if ($scoreattr=="Period4")
																{
																	$p4_home=$scores->Score[$c]->Team1;
																	$p4_away=$scores->Score[$c]->Team2;
																	echo "<br>Satz ".$c." -> ".$scores->Score[$c]->Team1." : ".$scores->Score[$c]->Team2;
																	}
																	else if ($scoreattr=="Period5")
																{
																	$p5_home=$scores->Score[$c]->Team1;
																	$p5_away=$scores->Score[$c]->Team2;
																	echo "<br>Satz ".$c." -> ".$scores->Score[$c]->Team1." : ".$scores->Score[$c]->Team2;
																	}
																	else if ($scoreattr=="Darts")
																{	
																		echo "<br>Hier Darts<br>";
																		$lastgoalteam=$match->LastGoal->Team;
																		$lastscoring=0;
																		$actserving=0;
																		$scorestring=0;
																		$scorestringarray=0;
																		$actualpoints=array_pop($scorestringarray);
																		//echo "<br>AP:".$actualpoints;
																		//$splitpointsarray=explode(":",$actualpoints);
																		$pointhome=0;
																		$pointaway=0;
														
																					$countpoints=count($satzergebnis->children());
																					echo "<br>Countpoints: ".$countpoints;
																					$countpoints = (int) $countpoints;
																					//$cp=0;
//																					$pointattr=$satzergebnis->Point[$countpoints]->attributes();
																		$index=0;	
																		foreach ($satzergebnis->children() as $points){
																			//$pointattr=$Point['$countpoints']->attributes();
																			//echo "<br>Score: ".$pointattr['score'];
																		echo "<br>Hier Points<br>";
																		$index++;
																		if ($index==$countpoints){
																		echo "<br>Hier letztes Element!->";
																		$pointattr=$points->attributes();
																		echo "<br>SCORE: ".$pointattr['score'];
																		echo "<br>SCORING: ".$pointattr['scoring'];
																		echo "<br>SERVING: ".$pointattr['serving'];
																		$lastscoring=$pointattr['scoring'];
																		$actserving=$pointattr['serving'];
																		$scorestring=$pointattr['score'];
																		$scorestringarray=explode(",", $scorestring);
																		$actualpoints=array_pop($scorestringarray);
																		echo "<br>AP:".$actualpoints;
																		$splitpointsarray=explode(":",$actualpoints);
																		$pointhome=$splitpointsarray[0];
																		$pointaway=$splitpointsarray[1];
																		}
																		
																		}
																	
																	}
																
															/*	if($c==1){
																$score1attr=$scores->Score[$c]->attributes();
																echo $score1attr['type'];
																if ($score1attr['type']=="Normaltime"){}
																else{
																	$p1_home=$scores->Score[$c]->Team1;
																	$p1_away=$scores->Score[$c]->Team2;
																	echo "<br>Satz ".$c." -> ".$scores->Score[$c]->Team1." : ".$scores->Score[$c]->Team2;
																	}
																	
																}
																else if($c==2){
																$score2attr=$scores->Score[$c]->attributes();
																echo $score2attr['type'];
																	$p2_home=$scores->Score[$c]->Team1;
																	$p2_away=$scores->Score[$c]->Team2;
																	echo "<br>Satz ".$c." -> ".$scores->Score[$c]->Team1." : ".$scores->Score[$c]->Team2;
																}
																else if($c==3){
																$score3attr=$scores->Score[$c]->attributes();
																if($score3attr['type']=="Normaltime"){ echo $score3attr['type'];}
																else { echo "nein";
																	$p3_home=$scores->Score[$c]->Team1;
																	$p3_away=$scores->Score[$c]->Team2;
																	echo "<br>Satz ".$c." -> ".$scores->Score[$c]->Team1." : ".$scores->Score[$c]->Team2;
																}
																}
																else if($c==4){
																$score4attr=$scores->Score[$c]->attributes();
																echo $score4attr['type'];
																	$p4_home=$scores->Score[$c]->Team1;
																	$p4_away=$scores->Score[$c]->Team2;
																	echo "<br>Satz ".$c." -> ".$scores->Score[$c]->Team1." : ".$scores->Score[$c]->Team2;
																}
																else if($c==5){
																$score5attr=$scores->Score[$c]->attributes();
																echo $score5attr['type'];
																	$p5_home=$scores->Score[$c]->Team1;
																	$p5_away=$scores->Score[$c]->Team2;
																	echo "<br>Satz ".$c." -> ".$scores->Score[$c]->Team1." : ".$scores->Score[$c]->Team2;
																}
															*/
															
														$c++;
														}
													//$count = $match->Goals->Goal->count();
													
													//$minscored=$match->Goals->Goal[($count-1)]->Time;
													//$lastgoalscoredby=$match->Goals->Goal[($count-1)]->Player;
													//}
												}
											}
											}
												//if($sessionTime>0){
														if ($status=="1st set"){
															echo "<br>1. Satz";
														}
														else if ($status=="2nd set"){
															echo "<br>2. Satz";
														}
														else if ($status=="3rd set"){
															echo "<br>3. Satz";
														}
														else if ($status=="4th set"){
															echo "<br>4. Satz";
														}
														else if ($status=="5th set"){
															echo "<br>5. Satz";
														}
														else if ($status=="Started"){
															echo "<br>started";
														}
														else if ($status=="2nd half"){
															//$sessionTime=$sessionTime+45;
												//			echo "2. Halbzeit / ".$sessionTime." min";
														}
														else if ($status=="1st extra"){
															//$sessionTime=$sessionTime+90;
												//			echo "2. Halbzeit / ".$sessionTime." min";
														}
														else if ($status=="2nd extra"){
															//$sessionTime=$sessionTime+105;
												//			echo "2. Halbzeit / ".$sessionTime." min";
														}
														else if ($status=="Halftime"){
													//		echo "Halbzeit";
														}
														else if ($status=="Interrupted"){
													//		echo "Unterbrochen";
														}
														else {
														 //$sessionTime=$sessionTime+1;
														}

													//}
										}
								}

								$matchId2db=$matchId['Id'];
								$league=0;
								$uniqueTeamIdHome2db=$uniqueTeamIdHome['UniqueTeamId']."_";
								$uniqueTeamIdAway2db=$uniqueTeamIdAway['UniqueTeamId']."_";
								if($LigaId!=""){$league==$tournamentname;}
								if ($league=="0") {
								 $league=$tournamentname;	
								}
								
								/* causing problems
								//to check matches for Live Coverage Abandoned
								$live_match_query = mysql_query("SELECT * FROM `sportnews_livescores_darts` where `matchstatus` = '1st set' || `matchstatus` = '2nd set' || `matchstatus` = '3rd set' || `matchstatus` = '4th set' || `matchstatus` = '5th set'");
									
								while($row = mysql_fetch_array($live_match_query)){
									$match_id = $row['matchid'];
									if ($match_id!=$matchId2db){
										echo "LCA match ID:".$match_id."<br/>";
										//$status_update_query = mysql_query("UPDATE sportnews_livescores_darts SET matchstatus='LCA' WHERE matchid='$match_id'");
										}
									}
								//end LCA check
								*/
								if($teamnamehome=="" || $teamnameaway==""){}
								else{
									$jetzt=time();
									echo "<br>p1h:".$p1_home;
									//$eintragen=date('d.M.Y-H:i:s',$jetzt)." - Main:_/scoremin:".$minscored."/ScoredBy:".$lastgoalscoredby."/LastGoalTeam:".$lastgoalteam."/LastGoaltime:".$lastgoaltime."/Contry:".$country."/League:".$league."/Matchtime:".$m_timestamp."/Status:".$status."/MatchId:".$matchId['Id']."TeamHome:".$teamnamehome."/TeamAway:".$teamnameaway."/Period:".$p_timestamp."/Scorehome:".$scorehome."/Scoreaway:".$scoreaway;
									mysql_query("INSERT INTO `sportnews_livescores_darts` (matchid, leagueid, categoryid, uniquetournamentid, country, league, matchdate, matchstatus, hometeam, awayteam, scorehome, scoreaway, p1_scorehome, p1_scoreaway, p2_scorehome, p2_scoreaway, p3_scorehome, p3_scoreaway, p4_scorehome, p4_scoreaway, p5_scorehome, p5_scoreaway, uniqueTeamHome, uniqueTeamAway, lastgoalteam, servingplayer, pointhome, pointaway, winner, winnername, lastchangeby, xmltime) VALUES ('$matchId2db', '$LigaId', '$CategoryId', '$UniqueTournamentId2db', '$country', '$league', '$m_timestamp','$status','$teamnamehome','$teamnameaway','$scorehome','$scoreaway', '$p1_home', '$p1_away', '$p2_home', '$p2_away', '$p3_home', '$p3_away', '$p4_home', '$p4_away', '$p5_home', '$p5_away','$uniqueTeamIdHome2db','$uniqueTeamIdAway2db','$lastgoalteam', '$actserving', '$currenthome','$currentaway', '$winner', '$winnername', 'main', $xmltime) ON DUPLICATE KEY 
									UPDATE `matchid` = VALUES(matchid),`leagueid` = VALUES(leagueid), `categoryid` = VALUES(categoryid), `uniquetournamentid` = VALUES(uniquetournamentid), `country` = VALUES(country),`league` = VALUES(league),  `matchdate` = VALUES(matchdate), `matchstatus` = VALUES(matchstatus), `hometeam` = VALUES(hometeam), `awayteam` = VALUES(awayteam), `scorehome` = VALUES(scorehome),`scoreaway` = VALUES(scoreaway),`p1_scorehome` = VALUES(p1_scorehome),`p1_scoreaway` = VALUES(p1_scoreaway),`p2_scorehome` = VALUES(p2_scorehome),`p2_scoreaway` = VALUES(p2_scoreaway),`p3_scorehome` = VALUES(p3_scorehome),`p3_scoreaway` = VALUES(p3_scoreaway),`p4_scorehome` = VALUES(p4_scorehome),`p4_scoreaway` = VALUES(p4_scoreaway),`p5_scorehome` = VALUES(p5_scorehome),`p5_scoreaway` = VALUES(p5_scoreaway),`uniqueTeamHome` = VALUES(uniqueTeamHome),`uniqueTeamAway` = VALUES(uniqueTeamAway),`lastgoalteam` = VALUES(lastgoalteam),`servingplayer` = VALUES(servingplayer),`pointhome` = VALUES(pointhome),`pointaway` = VALUES(pointaway), `winner` = VALUES(winner), `winnername` = VALUES(winnername), `lastchangeby` = VALUES(lastchangeby),`xmltime` = VALUES(xmltime)");
									echo mysql_errno(),
									"<br>",
									mysql_error();									
									//$logname="main_xml2db.log";
										//$datei=fopen($logname,"a");
										//fputs($datei,"$eintragen\n\n");
										//fclose($datei);
										
									$checkrows = mysql_query("SELECT `matchid` FROM `sportnews_livescores_darts` where `matchid` = '$matchId2db' Limit 1");
									echo mysql_errno(),
									"<br>",
									mysql_error();
									$num_rows = mysql_num_rows($checkrows);
									$row = mysql_fetch_array($checkrows);
									
									if ($num_rows<1){
									mysql_query("INSERT INTO `sportnews_livescores_darts` (matchid, leagueid, categoryid, uniquetournamentid, country, league, matchdate, matchstatus, hometeam, awayteam, scorehome, scoreaway, p1_scorehome, p1_scoreaway, p2_scorehome, p2_scoreaway, p3_scorehome, p3_scoreaway, p4_scorehome, p4_scoreaway, p5_scorehome, p5_scoreaway, uniqueTeamHome, uniqueTeamAway, lastgoalteam, servingplayer, pointhome, pointaway, winner, winnername, lastchangeby, xmltime) VALUES ('$matchId2db', '$LigaId', '$CategoryId', '$UniqueTournamentId2db', '$country', '$league', '$m_timestamp','$status','$teamnamehome','$teamnameaway','$scorehome','$scoreaway', '$p1_home', '$p1_away', '$p2_home', '$p2_away', '$p3_home', '$p3_away', '$p4_home', '$p4_away', '$p5_home', '$p5_away','$uniqueTeamIdHome2db','$uniqueTeamIdAway2db','$lastgoalteam', '$actserving', '$currenthome','$currentaway', '$winner', '$winnername', 'main', $xmltime) ON DUPLICATE KEY 
									UPDATE `matchid` = VALUES(matchid),`leagueid` = VALUES(leagueid), `categoryid` = VALUES(categoryid), `uniquetournamentid` = VALUES(uniquetournamentid), `country` = VALUES(country),`league` = VALUES(league),  `matchdate` = VALUES(matchdate), `matchstatus` = VALUES(matchstatus), `hometeam` = VALUES(hometeam), `awayteam` = VALUES(awayteam), `scorehome` = VALUES(scorehome),`scoreaway` = VALUES(scoreaway),`p1_scorehome` = VALUES(p1_scorehome),`p1_scoreaway` = VALUES(p1_scoreaway),`p2_scorehome` = VALUES(p2_scorehome),`p2_scoreaway` = VALUES(p2_scoreaway),`p3_scorehome` = VALUES(p3_scorehome),`p3_scoreaway` = VALUES(p3_scoreaway),`p4_scorehome` = VALUES(p4_scorehome),`p4_scoreaway` = VALUES(p4_scoreaway),`p5_scorehome` = VALUES(p5_scorehome),`p5_scoreaway` = VALUES(p5_scoreaway),`uniqueTeamHome` = VALUES(uniqueTeamHome),`uniqueTeamAway` = VALUES(uniqueTeamAway),`lastgoalteam` = VALUES(lastgoalteam),`servingplayer` = VALUES(servingplayer),`pointhome` = VALUES(pointhome),`pointaway` = VALUES(pointaway), `winner` = VALUES(winner), `winnername` = VALUES(winnername), `lastchangeby` = VALUES(lastchangeby),`xmltime` = VALUES(xmltime)");
									echo mysql_errno(),
									"<br>",
									mysql_error();
									$num_rows=0;																																																																																																																																																										 																																			
									echo "test".$status;
									}
									else if($row[matchstatus]=="Not started" && $status=="1st half" || $row[matchstatus]=="Not started" && $status=="Started" || $row[matchstatus]=="1st half" && $status=="Halftime" || $row[matchstatus]=="Halftime" && $status=="2nd half" || $row[matchstatus]=="2nd half" && $status=="Ended" ){
									
									
									mysql_query("INSERT INTO `sportnews_livescores_darts` (matchid, leagueid, categoryid, uniquetournamentid, country, league, matchdate, matchstatus, hometeam, awayteam, scorehome, scoreaway, p1_scorehome, p1_scoreaway, p2_scorehome, p2_scoreaway, p3_scorehome, p3_scoreaway, p4_scorehome, p4_scoreaway, p5_scorehome, p5_scoreaway, uniqueTeamHome, uniqueTeamAway, lastgoalteam, servingplayer, pointhome, pointaway, winner, winnername, lastchangeby, xmltime) VALUES ('$matchId2db', '$LigaId', '$CategoryId', '$UniqueTournamentId2db', '$country', '$league', '$m_timestamp','$status','$teamnamehome','$teamnameaway','$scorehome','$scoreaway', '$p1_home', '$p1_away', '$p2_home', '$p2_away', '$p3_home', '$p3_away', '$p4_home', '$p4_away', '$p5_home', '$p5_away','$uniqueTeamIdHome2db','$uniqueTeamIdAway2db','$lastgoalteam', '$actserving', '$currenthome','$currentaway', '$winner', '$winnername', 'main', $xmltime) ON DUPLICATE KEY 
									UPDATE `matchid` = VALUES(matchid),`leagueid` = VALUES(leagueid), `categoryid` = VALUES(categoryid), `uniquetournamentid` = VALUES(uniquetournamentid), `country` = VALUES(country),`league` = VALUES(league),  `matchdate` = VALUES(matchdate), `matchstatus` = VALUES(matchstatus), `hometeam` = VALUES(hometeam), `awayteam` = VALUES(awayteam), `scorehome` = VALUES(scorehome),`scoreaway` = VALUES(scoreaway),`p1_scorehome` = VALUES(p1_scorehome),`p1_scoreaway` = VALUES(p1_scoreaway),`p2_scorehome` = VALUES(p2_scorehome),`p2_scoreaway` = VALUES(p2_scoreaway),`p3_scorehome` = VALUES(p3_scorehome),`p3_scoreaway` = VALUES(p3_scoreaway),`p4_scorehome` = VALUES(p4_scorehome),`p4_scoreaway` = VALUES(p4_scoreaway),`p5_scorehome` = VALUES(p5_scorehome),`p5_scoreaway` = VALUES(p5_scoreaway),`uniqueTeamHome` = VALUES(uniqueTeamHome),`uniqueTeamAway` = VALUES(uniqueTeamAway),`lastgoalteam` = VALUES(lastgoalteam),`servingplayer` = VALUES(servingplayer),`pointhome` = VALUES(pointhome),`pointaway` = VALUES(pointaway), `winner` = VALUES(winner), `winnername` = VALUES(winnername), `lastchangeby` = VALUES(lastchangeby),`xmltime` = VALUES(xmltime)");
									
									}
									else {
									/*
										$logname="/opt/users/www/betitbest-news/livescores/importer/log/voleyball_import_main_xml2db.log";
										$datei=fopen($logname,"w");
										$eintragen=date('d.M.Y-H:i:s',$jetzt)."- Match bereits von delta aktualisiert<br>!";
										fputs($datei,"$eintragen\n\n");
										fclose($datei);
										*/
									}
									echo mysql_errno(),"<br>",mysql_error();
									//$scorehome="-";
									//$scoreaway="-";
									$lastgoaltime=0;
									$lastgoalteam=0;
									$minscored=0;
									$lastgoalscoredby="";
								}
							}
							}
						}
					}
				}	
			}
			
			
			
			
			
			
			
			
			
			
			}
	}
mysql_close();
	}

	//var_dump($xml);
	//echo $xml->getName() . "<br>";
	//echo $xml->Matchdate;
	//echo $xml->Matchdate[0];
	//print_r($xml);
?>

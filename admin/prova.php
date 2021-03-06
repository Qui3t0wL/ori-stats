<?php

	require_once('connect.php');
	require ('./../functions/dados.php');
	include ('./../header.php');
	include ('./menu_lateral.php');
    
    //$id_prova = trim($_GET['id']);
    $id_prova = 449;
    //$id_prova = trim($_POST['id_prova']);
    $dados_prova = mysqli_query($link, "SELECT DATE_FORMAT(date,'%d/%m/%Y') as data, event.local, event_name, s.season_desc se, d.dist_desc di, o.abbrev cl, r.race_type_desc tp, c.class_desc esc, re.length le FROM race, distance d, season s, clubs o, race_type r, results re, class c, event WHERE race.id_race = re.id_race AND race.id_season = s.id_season AND race.id_dist = d.id_dist AND event.id_club_org1 = o.id_club AND race.id_race_type = r.id_race_type AND re.id_class = c.id_class AND race.id_event = event.id_event AND race.id_race = $id_prova") or die(mysqli_error($link));
    
    $club2 = mysqli_query($link, "SELECT o.abbrev FROM race, clubs o, event WHERE event.id_club_org2 = o.id_club AND race.id_event = event.id_event AND race.id_race = $id_prova") or die(mysqli_error($link));

    $race = mysqli_query($link, "SELECT classif, name, id_country, club, time, time_behind, mp, nc FROM data WHERE id_race = $id_prova ORDER BY nc, mp, time ASC") or die(mysqli_error($link));
?>
    <center>    
    <table id="prova">
        <tr>
            <td style="text-align:right"><b>Evento: </b></td><td><?php $r = mysqli_fetch_assoc($dados_prova); echo utf8_encode($r['event_name']);?></td> 			
            <td style="text-align:right"><b>&Eacute;poca: </b></td><td><?php echo $r['se'];?></td>
        </tr>
        <tr>
            <td style="text-align:right"><b>Local: </b></td><td><?php echo utf8_encode($r['local']);?></td>
            <td style="text-align:right"><b>Data: </b></td><td><?php echo $r['data'];?></td>
        </tr>
        <tr>
            <td style="text-align:right"><b>Organiza&ccedil;&atilde;o: </b></td><td><?php echo utf8_encode($r['cl']);
            if(mysqli_num_rows($club2) == 1){$cl2 = mysqli_fetch_assoc($club2); echo "e ".$cl2['abbrev'];}
            ?></td>
            <td style="text-align:right"><b>Dist&acirc;ncia: </b></td><td><?php echo utf8_encode($r['di']);?></td>
        </tr>
        <tr>
            <td style="text-align:right"><b>Escal&atilde;o: </b></td><td><?php echo $r['esc'];?></td>
            <td style="text-align:right"><b>Tipo de prova: </b></td><td><?php echo utf8_encode($r['tp']);?></td>
        </tr>
    </table>
    
    <table width="55%" id="listar_tabela">
        <tr>
			<td id="rank_table_header" style="text-align:center"><b>Cl.</b></td>
			<td id="rank_table_header" style="text-align:center"><b>Nome</b></td>
			<td id="rank_table_header" style="text-align:center" colspan="2"><b>Clube</b></td>
			<td id="rank_table_header" style="text-align:center"><b>Tempo</b></td>
			<td id="rank_table_header" style="text-align:center"><b>Dif. de tempo</b></td>
            <td id="rank_table_header" style="text-align:center"><b>Velocidade<br>(min/km)</b></td>
        </tr>
    <?php while ($row = mysqli_fetch_assoc($race)) {?>
        <tr>
            <td style="text-align:center"><?php 
            if($row['mp'] == 0) { if($row['nc'] == 0) { echo $row['classif'];} else { echo 'nc';}} else { echo '';} ?></td>
            <td style="text-align:"><?php echo utf8_encode($row['name']);?></td>
            <td style="text-align:center"><img src="./../flags/<?php echo $row['id_country'];?>.gif" title="<?php echo $row['id_country'];?>"/></td>
            <td><?php echo utf8_encode($row['club']);?></td>
            <td style="text-align:center"><?php if($row['mp'] == 0) { echo $row['time'];} else {echo 'mp';}?></td>
            <td style="text-align:center"><?php if($row['mp'] == 0) { echo "+".$row['time_behind'];} else {echo '';}?></td>
            <td style="text-align:center"><?php if($row['mp'] == 0) { echo velocidade($row['time'],$r['le']);} else {echo '';}?></td>
        </tr>
    <?php }?>
	</table><br>
	</center><br><br>
    <?php include ('./../footer.php');?>
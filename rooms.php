<?php

if(isset($_POST['btnShowRoom']) && !empty($_POST['hotelId']))
{
            $selRoom = "select  r.room_id, r.roomnumber, r.description 'beschreibung',
                                r.maxPerson 'Anzahl Personen', r.roomsize 'Zimmergroesse',
                                r.price_per_day 'Preis_pro_Tag', rt.titel 'Zimmertyp'
        from room r
        left join booking b on r.room_id = b.room_id
        right join hotel h on  h.hotel_id = r.hotel_id
        join roomtype rt on r.roomtype_id = rt.roomtype_id
        join city c on c.city_id = h.city_id
         where r.hotel_id = :hotelId
        and r.maxPerson >=  :anzPers
        and (ifnull(b.check_in, '') not between :checkIn and :checkOut)
        and (ifnull(b.check_out,'') not between :checkIn and :checkOut)";
    $roomStmt = $dbCon->prepare($selRoom);
    $roomStmt->bindParam(':hotelId', $_POST['hotelId']);
    $roomStmt->bindParam(':anzPers', $_POST['anzPers']);
    $roomStmt->bindParam(':checkOut', $_POST['checkOut']);
    $roomStmt->bindParam(':checkIn', $_POST['checkIn']);
    $roomStmt->execute();
    $countColumn = $roomStmt->columnCount();
?>

<table class="table">
    <thead>
    <?php
    for($i=0; $i<$countColumn; $i++)
    {
        /* holt die Überschriften der Spaltennamen raus */
        $meta[] = $roomStmt->getColumnMeta($i);
        echo '<th>'.$meta[$i]['name'].'</th>';
    }
    ?>
    <th>Details</th>
    </thead>
    <tbody>
    <tr>
        <?php
        while($row = $roomStmt->fetch(PDO::FETCH_OBJ))
        {?>
        <form method="post" action="?page=booking">
            <?php
            foreach($row as $col)
            {?>
                <td> <?php echo $col ?></td>

                <?php
            }?>
            <input type="hidden" name="roomId" value="<?php echo $row->room_id ?>">
            <input type="hidden" name="hotelId" value="<?php echo $_POST['hotelId'] ?>">
            <input type="hidden" name="checkIn" value="<?php echo $_POST['checkIn'] ?>">
            <input type="hidden" name="checkOut" value="<?php echo $_POST['checkOut'] ?>">
            <input type="hidden" name="anzPers" value="<?php echo $_POST['anzPers'] ?>">
            <td>
                <input class="btn btn-warning" type="submit" value="zur Buchung" name="booking">
            </td>
    </tr>
    <form>
        <?php
        }?>

    </tbody>
</table>

<?php
}
?>
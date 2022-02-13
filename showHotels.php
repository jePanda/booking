<?php
//anzeige der gelisteten hotels
    $selHotelQuery = "select distinct h.hotel_id, h.hotelname, h.website, h.email,  h.phoneno, c.zipcode, c.cityname
                        from room r
                        left join booking b on r.room_id = b.room_id
                       right join hotel h on h.hotel_id = r.hotel_id
                        join city c on c.city_id = h.city_id
                        and r.maxPerson >= :anzPers
                        and (ifnull(b.check_in, '') not between :checkIn and :checkOut)
                        and (ifnull(b.check_out,'') not between :checkIn and :checkOut)";
    $hotelStmt = $dbCon->prepare($selHotelQuery);
    $hotelStmt->bindParam(':checkIn', $_POST['checkIn']);
    $hotelStmt->bindParam(':checkOut', $_POST['checkOut']);
    $hotelStmt->bindParam(':anzPers', $_POST['anzPers']);
    $hotelStmt->execute();

    $countColumn = $hotelStmt->columnCount();
?>
<div class="container-fluid">
    <form method="post">
        <label for="checkIn"><b>Check-In:</b></label>
        <input type="date" name="checkIn">

        <label for="checkOut"><b>Check-Out:</b></label>
        <input  type="date" name="checkOut">

        <label for="anzPers"><b>Anzahl Person:</b></label>
        <input required type="number" step="1" min="1" max="10" name="anzPers">
        <input class="btn btn-info" type="submit" value="Hotel suchen" name="btnSearchHotel">
    </form>
    <?php
    if(isset($_POST['btnSearchHotel']))
    {
    ?>
<table class="table">
  <thead>
    <?php
    for($i=0; $i<$countColumn; $i++)
    {
        /* holt die Überschriften der Spaltennamen raus */
        $meta[] = $hotelStmt->getColumnMeta($i);
        echo '<th>'.$meta[$i]['name'].'</th>';
    }
    ?>
    <th>Details</th>
    <th></th>
  </thead>
    <tbody>
    <tr>
        <?php
        //holt die Werte aus der Zeile
        while($row = $hotelStmt->fetch(PDO::FETCH_OBJ))
        {?>
        <!-- jede Zeile bekommt eine eigene Form um die richtigen Daten im Post-array abzuspeichern-->
        <form method="post" action="?page=rooms">
            <?php
            //holt die einzelnen Werte aus der Spalte und speichert sie im nameSchema
            foreach($row as $col)
            {?>
                <td><?php echo $col ?></td>

                <?php
            }?>
            <td><input class="btn btn-warning" type="submit" value="Zimmer anzeigen" name="btnShowRoom"></td>
            <input type="hidden" name="hotelId" value="<?php echo $row->hotel_id ?>">
            <input type="hidden" name="anzPers" value="<?php echo $_POST['anzPers'] ?>">
            <input type="hidden" name="checkIn" value="<?php echo$_POST['checkIn'] ?>">
            <input type="hidden" name="checkOut" value="<?php echo$_POST['checkOut'] ?>">
        </form>
    </tr>
    <?php
    }
    ?>
    </form>
    </tbody>
</table>
</div>
<?php
    }
    ?>

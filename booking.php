<?php

if(isset($_POST['booking']) && !empty($_POST['hotelId']) && !empty($_POST['roomId']))
{
    $selCity = 'select * from city';
    $cityStmt = $dbCon->prepare($selCity);
    $cityStmt->execute();

?>
<div class="container-fluid">
    <form method="post" >
        <input type="hidden" name="roomId" value="<?php echo $_POST['roomId']?>">
        <input type="hidden" name="hotelId" value="<?php echo $_POST['hotelId']?>">
        <input type="hidden" name="checkIn" value="<?php echo $_POST['checkIn']?>">
        <input type="hidden" name="checkOut" value="<?php echo $_POST['checkOut']?>">
        <input type="hidden" name="anzPers" value="<?php echo $_POST['anzPers']?>">
        <label for="firstName"><b>Vorname:</b></label>
        <input required placeholder="Max" type="text" name="firstName"><br>

        <label for="lastname"><b>Nachname:</b></label>
        <input required placeholder="Mair" type="text" name="lastname"><br>

        <label for="email"><b>E-Mail:</b></label>
        <input required placeholder="max@mail.com" type="email" name="email"><br>

        <label for="phoneno"><b>Telefonnr:</b></label>
        <input type="text" placeholder="07712 20 30 60" name="phoneno"><br>

        <label for="street"><b>Strasse:</b></label>
        <input type="text" placeholder="Musterstr." name="street"><br>

        <label for="houseno"><b>Hausnummer:</b></label>
        <input type="text" name="houseno"><br>

        <label for="city"><b>Ort auswählen</b></label>
        <select name="city">
            <?php
            while($row = $cityStmt->fetch(PDO::FETCH_OBJ))
            {?>
                <option name="city" value="<?php echo $row->city_id?>"> <?php echo $row->zipcode . ' ' . $row->cityname ?></option>
            <?php
            }
            ?>
        </select>

        <input class="btn btn-warning" type="submit" name="btnBook" value="Buchung abschließen">
    </form>
</div>
<?php
}
else if(isset($_POST['btnBook']))
{
    $insPerson = ' insert into person (firstname, lastname, street, houseno, email, phoneno, city_id)
                        values(:firstname, :lastname, :street, :houseno, :email, :phoneno, :city_id)';
    $insStmt = $dbCon->prepare($insPerson);
    $insStmt->bindParam(':firstname', $_POST['firstName']);
    $insStmt->bindParam(':lastname', $_POST['lastname']);
    $insStmt->bindParam(':street', $_POST['street']);
    $insStmt->bindParam(':houseno', $_POST['houseno']);
    $insStmt->bindParam(':email', $_POST['email']);
    $insStmt->bindParam(':phoneno', $_POST['phoneno']);
    $insStmt->bindParam(':city_id', $_POST['city']);
    $insStmt->execute();

    $lastPersonId =  $dbCon->lastInsertId();
    echo 'Person wurde mit ID: ' .$lastPersonId . ' angelegt' ;

    $insBooking = ' insert into booking(person_id, room_id, check_in, check_out, amountPerson)
 values (:personId, :roomId, :checkIn, :checkout, :amountPerson);';
    $insBookingStmt = $dbCon->prepare($insBooking);
    $insBookingStmt->bindParam(':personId', $lastPersonId);
    $insBookingStmt->bindParam(':roomId', $_POST['roomId']);
    $insBookingStmt->bindParam(':checkIn', $_POST['checkIn']);
    $insBookingStmt->bindParam(':checkout', $_POST['checkOut']);
    $insBookingStmt->bindParam(':amountPerson', $_POST['anzPers']);
    $insBookingStmt->execute();

    echo '<br> Buchung wurde abgeschlossen';
;
}
?>
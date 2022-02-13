<?php

$dbCon = getDBCon();

function getDBCon()
{
    try {
        $host='localhost';
        $user='root';
        $passw='';
        $dbName='booking';

        $dbCon = new PDO('mysql:host=' . $host . ';dbname=' . $dbName, $user, $passw);
        $dbCon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        ?>
        <br>
        <div class="alert alert-success">
            <strong>Success!</strong> Connection zur DB <?php echo $dbName?>
        </div>
        <?php

        return $dbCon;
    }
    catch(PDOException $e)
    {
        echo 'ERROR: ' . $e->getMessage() . ' Message: ' . $e->getCode();
    }
}

?>
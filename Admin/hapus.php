<?php

include '../Include/koneksi.php';

if(!isset($_GET['id'])){
    die("ID tidak ditemukan");
}

$id = intval($_GET['id']);

$sql = "DELETE FROM pekerjaan
WHERE id_pekerjaan = '$id'";

$query = mysqli_query($conn, $sql);

if($query){

    if(mysqli_affected_rows($conn) > 0){

        echo "
        <script>
        alert('Data berhasil dihapus');
        window.location='Dashboard.php';
        </script>
        ";

    }else{

        echo "Data tidak ditemukan di database";

    }

}else{

    echo mysqli_error($conn);

}

?>
<?php
include('baglan.php');

//print_r(unserialize($_SESSION['uye']));
//üye giriş yapmış mı? ve üye admin değilse, yönlendirelim
//üye tablosunda admin değeri 1, normal üye değeri 2 ile tanımlanmıştı
if (isset($_SESSION['uye'])) {
	$uye = unserialize($_SESSION['uye']);
    if ($uye['durum'] == 2) {
        header('Location: index.php');
    }
} else {
    header('Location: logout.php');
    exit();
}
//Kayıt istek biçimlerine göre SQL sorgusu belirleyelim
if (isset($_POST['ekle'])) {
    $sql = "INSERT INTO blog(baslik,yazi,uye_id) VALUES(?,?,?)";
} else if (isset($_POST['guncelle'])) {
    $sql = "UPDATE blog SET baslik=?,yazi=? WHERE blog_id=?";
} else if (isset($_GET['sil'])) {
    $sql = "DELETE FROM blog WHERE blog_id=?";
} else if (isset($_GET['yorum_sil'])) {
    $sql = "DELETE FROM yorum WHERE yorum_id=?";
    $_GET['sil'] = $_GET['yorum_sil'];
}

if (isset($_POST['ekle']) || isset($_POST['guncelle'])) {
    //SQL sorgusunu hazırlayalım
    $stmt = $db->prepare($sql);
    if ($stmt === false)
        die('Sorgu hatası:' . $db->error);
    /*SQL deki ?,? için  veri tiplerini ve değişkenleri tanımlayalım */
    if (isset($_POST['ekle'])) {
        $stmt->bind_param("ssi", $_POST['baslik'], $_POST['yazi'], $uye['uye_id']);
    }
    if (isset($_POST['guncelle'])) {
        $stmt->bind_param("ssi", $_POST['baslik'], $_POST['yazi'], $_POST['blog_id']);
    }
    //Sorguyu çalıştıralım
    $stmt->execute();
    if ($stmt->affected_rows < 1) {
		printf("Error: %s. <br>\r\n", $stmt->error);
        die('Kayıt eklenmedi/güncellenmedi.');
    }
    $stmt->close();
} else if (isset($_GET['sil']) || isset($_GET['yorum_sil'])) {
    //Silme işlemi için SQL sorgusunu hazırlayalım
    $stmt = $db->prepare($sql);
    if ($stmt === false)
        die('Sorgu hatası:' . $db->error);
    /*SQL deki ? için  veri tipini ve değişkeni tanımlayalım */
    $stmt->bind_param("i", $_GET['sil']);
    //SQL Sorgusunu çalıştıralım
    $stmt->execute();
    if ($stmt->affected_rows < 1) {
        die('Kayıt silinmedi');
    }
    if (isset($_GET['yorum_sil']))
        header('Location: detay.php?id=' . $_GET['id']);
    $stmt->close();
}

if (isset($_GET['guncelle'])) {
    //Güncelleme isteğini elde etmek için SQL sorgusunu hazırlayalım
    $stmt = $db->prepare("SELECT * FROM blog WHERE blog_id=?");
    /*SQL deki ? için  veri tipini ve değişkeni tanımlayalım */
    $stmt->bind_param("i", $_GET['guncelle']);
    //SQL Sorgusunu çalıştıralım
    $stmt->execute();
    //Sonuçları alalım
    $sonuc = $stmt->get_result();
    //Sonuçları sütun adlarına göre elde edelim
    $row   = $sonuc->fetch_array();
    //Güncelleme için bilgileri forma yazalım
  $cikti = '<h3>Kayıt güncelle</h3>
  <form method="post" action="admin.php">
  <input type="hidden" name="blog_id" value="' . $row['blog_id'] . '"/>
  Başlık: <input type="text" class="form-control" name="baslik" value="' . $row['baslik'] . '" required>
  <br>Açıklama:<br>
  <textarea class="form-control" rows="5" name="yazi" id="yazi">' . $row['yazi'] . '</textarea>
  <br><input type="submit" name="guncelle" class="btn btn-success" value="Kaydet">
  </form>
  <script> var editorId = \'yazi\';</script>
  <script src=\'editor.js\'></script>';
    $stmt->close();
} else {
   $cikti = '<h3>Yeni Kayıt Ekle</h3>
   <form method="post" action="admin.php">
   Başlık: <input class="form-control" type="text" name="baslik" required>
  <br>Açıklama:<br>
   <textarea class="form-control" rows="5" name="yazi" id="yazi"></textarea>
   <br><input type="submit" name="ekle" class="btn btn-success" value="Kaydet">
  </form>
  <script> var editorId = \'yazi\';</script>
  <script src=\'editor.js\'></script>';
}

//Blog başlıklarını listelemek için SQL sorgusunu hazırlayalım
$blog = $db->prepare("SELECT * FROM blog");
//SQL sorgusunu çalıştıralım
$blog->execute();
//blog tablosunun sonuçlarını elde edelim
$blog_sonuc = $blog->get_result();
//blog tablosunun sonuçlarını sütun adlarına göre elde edelim
//Başlıkları okutup ,güncelleme ve silme için linkleri oluşturalım
$cikti .= '<hr><a href="admin.php" class="btn btn-primary">Yeni Ekle</a><br><br>
<table class="table table-striped table-hover">';
while ($row = $blog_sonuc->fetch_array()) {
  $cikti .= "<tr>
  <td>{$row['baslik']}</td>
  <td><a href='?guncelle={$row['blog_id']}' class='btn btn-sm btn-warning'>Güncelle</a></td>
  <td><a href='?sil={$row['blog_id']}' class='btn btn-sm btn-danger' onclick=\"return confirm('Silinsin mi?')\"> Sil</a></td>
  </tr>\n";
}
$cikti .= '</table>';
$blog->close();
$db->close();
include('sablon/sablon_old.php');
?>
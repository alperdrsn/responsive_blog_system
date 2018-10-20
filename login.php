<?php
require('baglan.php');

$kontrol = require('oturum_denetle.php');
if ($kontrol === true) {
   header('Location: admin.php');
}

$hata = isset($_POST['email']) ? 'Formu doldurun' : null;

if (!empty($_POST['email']) && !empty($_POST['sifre'])) {
   //SQL Sorgusunu hazırlayalım
   $stmt = $db->prepare("SELECT * FROM uye WHERE email=? AND sifre=MD5(?)");
   if ($stmt === false) {
      die('Sorgu hatası:' . $db->error);
   }
   
   /*SQL deki ?,? için  veri tiplerini ve değişkenleri tanımlayalım */
   $stmt->bind_param("ss", $_POST['email'], $_POST['sifre']);
   
   //SQL Sorgusunu çalıştıralım
   $stmt->execute();
   //Sonucu elde edelim
   $sonuc = $stmt->get_result();
   
   //num_rows 1 dönerse bilgiler doğrudur.Çerez ataması yapalım
   if ($sonuc->num_rows) {
      // şifreyi md5 ile ve birde $key ile birlikte şifreleyelim.
      $sifre = md5($_POST['sifre']) . ':' . md5(md5($_POST['sifre']) . $key);
      // email  biri normal, diğeri md5 ve $key ile şifreleyelim.
      $email = $_POST['email'] . ':' . md5($_POST['email'] . $key);
      
      //Beni hatırla işaretli ise 1 ay süreliğine çerez atayalım
      if ($_POST['hatirla']) {
         setcookie("uye", $sifre . ':' . $email, strtotime('+30 days'));
      }
      // üyenin bilgilerini sessiona kaydedelim.
      $_SESSION['uye'] = serialize($sonuc->fetch_array());
      
      // Her şey yolunda olduğu için admin.php yönlendirelim
      header('Location: admin.php');
   } else {
      $hata = '<h4>Eposta veya şifre hatalı</h4>';
   }   
}
?>
<!DOCTYPE html>
<html lang="tr">
 <head>
  <title> Üye Girişi </title>
  <meta charset="utf-8">
 </head>
 <body>
 <h2>Giriş Yapın</h2>
 <?php echo $hata; ?>
 <form method="post" action="">
  <p><input type="email" name="email" required> E-posta </p>
  <p><input type="password" name="sifre" required pattern="[^\s]+"> Şifre </p>
  <p><input type="checkbox" name="hatirla"> Beni hatırla </p>
  <input type="submit" value="Giriş">
</form>
</body>
</html>

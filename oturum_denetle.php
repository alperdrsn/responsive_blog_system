<?php
// Session yok ise, çerez varmı? kontrol edilir.
if (!isset($_SESSION['uye'])) {
  if (isset($_COOKIE['uye'])) {
    // Çerez var ise, çerezdeki bilgileri elde ediyoruz.
    list($sifre, $sifre2, $email, $email2) = explode(':', $_COOKIE['uye']);
    
    //gizli anahtar ile çerezdeki bilgiler ikinci bilgilerle karşılaştırılır
    if (md5($sifre . $key) == $sifre2 && md5($email . $key) == $email2) {
      
      /* bilgiler doğru ise veritabanından sorgulatılır */
      $stmt = $db->prepare("SELECT * FROM uye WHERE email=? AND sifre=?");
      if ($stmt === false) {
        die('Sorgu hatası:' . $db->error);
      }
      /*SQL deki ?,? için  veri tiplerini ve değişkenleri tanımlayalım */
      $stmt->bind_param("ss", $email, $sifre);
      
      //SQL Sorgusunu çalıştırılır
      $stmt->execute();
      
      //Sonuç elde edlir
      $sonuc = $stmt->get_result();
      
      //num_rows 1 ise bilgiler doğru,Session atayapalım ve true döndürelim
      if ($sonuc->num_rows == 1) {
        $_SESSION['uye'] = serialize($sonuc->fetch_array());
        return true;
      }
    }
  }
  //session ve çerezlerin her ikisi de yok ise false döndürelim
  return false;
}
//session var ise true döndürelim 
return true;
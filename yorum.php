<?php
include('baglan.php');
if( !empty($_POST['mesaj']) && !empty($_POST['yazan']) ){
  //Yorum tablosuna ekleme yapmak için SQL sorgusunu hazırlayalım
$yorum = $db->prepare("INSERT INTO yorum(mesaj,yazan,blog_id) VALUES(?,?,?)");
  if ($yorum === false){ 
        die('Sorgu hatası:'. $db->error); 
  }
  //Yoruma eklenen HTML kodlarını htmlspecialchars ile etkisizleştirelim
  $mesaj = htmlspecialchars($_POST['mesaj'], ENT_QUOTES);
  $yazan = htmlspecialchars($_POST['yazan'], ENT_QUOTES);

  /*SQL ?,? için  veri tiplerini ve değişkenleri tanımlayalım */
  $yorum->bind_param("ssi", $mesaj, $yazan, $_POST['blog_id']);

  //Yorum tablosu ekleme sorgusunu çalıştıralım
  $yorum->execute();

  //Acaba yorum eklendimi ? control edelim
  //Eklenmiş ise tekrar yorum yaptığı sayafa yönlendirelim
  if($db->affected_rows > 0){
     header('Location: detay.php?id='.$_POST['blog_id']);
  }else{
     die('Yorum eklenmedi');
  }

  $yorum->close();
}else{
  echo 'Formu boş gönderdiniz';
}
$db->close();
?>
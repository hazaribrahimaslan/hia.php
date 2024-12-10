# hia.php PHP Kütüphanesi
<p>Veritabanı işlemleri, kullanıcı sistemi ve diğer tüm özellikler tek kütüphanede.</p>
<p><b>Özellikleri</b></p>
<ul>
  <li>Veritabanı bağlantısı.</li>
  <li>Veri sorgulama,listeleme, ekleme, güncelleme ve silme.</li>
  <li>Metin kontrolü.</li>
  <li>Sayı kontrolü.</li>
  <li>E-posta adresi kontrolü.</li>
  <li>İnternet adresi kontrolü.</li>
  <li>Sayfa oluşturma ve yazdırma.</li>
  <li>PHPMailer ile E-posta gönderme.</li>
</ul>
<p><b>Dosyalar</b></p>
<ul>
  <li>hia.php : php kütüphanesi</li>
  <li>hia.css : HTML sayfalar için yalın tasarım.</li>
  <li>index.php : Örnek sayfa.</li>
</ul>
<p><b>Örnek kullanımı</b></p>
<code>
include "hia.php";
$hia = new hia();
$body = "Merhaba dünya!";
$hia->page_show("favicon.ico","Sayfa başlığı","Sayfa açıklaması","$body");
</code>

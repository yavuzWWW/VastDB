# VastDB — Türkçe Kurulum ve Kullanım Rehberi

## 1. Bu paket nedir?

VastDB, PHP ile yazılmış küçük bir dosya tabanlı veritabanıdır.

MySQL, MariaDB, PostgreSQL, Composer, Node.js veya ayrı bir veritabanı sunucusu istemez.

Veriler `data/` klasöründe tutulur.

Bu kopya temizlenmiştir:

- kullanıcı tablosu yok
- sunucu tablosu yok
- log tablosu yok
- test tablosu yok
- eski satırlar yok
- `data/tables.vast` boş
- PHP/JS/.htaccess koduna dokunulmadı

`data/info.vast` dosyası özellikle bırakıldı çünkü mevcut kod admin doğrulaması için bu dosyaya ihtiyaç duyuyor.

---

# 2. En basit kurulum anlatımı

Mantık sadece şu:

1. `vastdb` klasörünü web sunucuna koy.
2. PHP çalışıyor olsun.
3. Apache `.htaccess` ve URL rewrite kullanabilsin.
4. PHP, `vastdb/data/` içine dosya/klasör yazabilsin.
5. Admin şifreni ve auth key'ini ayarla veya mevcut değerlerini bil.
6. Admin adresini aç.
7. İlk tablona oluştur.

Temel kurulum bu kadar.

---

# 3. Gerekenler

## Gerekli

- PHP 7.0 veya daha yeni. PHP 8.x önerilir.
- Paketteki `.htaccess` kuralları için Apache web sunucusu.
- PHP'nin `data/` içinde dosya ve klasör oluşturma, okuma, düzenleme ve silme izni.
- Apache `mod_rewrite` açık olmalı.
- Apache yapılandırması `.htaccess` kullanımına izin vermeli (`AllowOverride`).

## Gerekmeyenler

Şunlara ihtiyacın YOK:

- MySQL
- MariaDB
- phpMyAdmin
- PostgreSQL
- Redis
- Composer
- npm
- Node.js

---

# 4. Klasör yapısı

Önemli dosyalar:

```text
vastdb/
├── admin.php                 Admin giriş sayfası
├── admin_commands.php        Geliştirme/örnek komutlar
├── db.php                    Ana VastDB fonksiyonları
├── functions.php             Yardımcı fonksiyonlar
├── .htaccess                 Apache erişim/rewrite kuralları
│
├── data/
│   ├── info.vast             Admin kullanıcı adı/şifre hash'i/auth-key hash'i
│   └── tables.vast           Tablo listesi; bu temiz kurulumda boş
│
├── handler/
│   ├── db_handler.php        Oluşturma/ekleme/güncelleme işlemleri
│   ├── delete_handler.php    Silme işlemleri
│   └── .htaccess
│
├── pages/
│   ├── login.php
│   ├── dashboard-content.php
│   └── tables.php
│
└── scripts/lib/htmx.js
```

Yeni tablo oluşturduğunda VastDB, `data/` içinde o tablo için klasör oluşturur.

Örnek:

```text
data/users/
├── meta.vast
├── next_index.vast
├── username/data.vastdb
└── email/data.vastdb
```

---

# 5. Windows + XAMPP ile kurulum

Bu, yerel kullanım için en kolay yöntemdir.

## Adım 1 — XAMPP'ı aç

XAMPP'ı aç ve **Apache** servisini başlat.

VastDB için MySQL'i başlatmana gerek yok.

## Adım 2 — Klasörü koy

`vastdb` klasörünün tamamını XAMPP web klasörüne koy.

Genellikle:

```text
C:\xampp\htdocs\vastdb
```

## Adım 3 — Apache rewrite çalışsın

XAMPP'ta `mod_rewrite` genellikle hazırdır.

Proje `.htaccess` kullandığı için Apache'nin bu dosyayı okuyabilmesi gerekir.

## Adım 4 — Admin bilgilerini ayarla

Şu dosyayı aç:

```text
vastdb/data/info.vast
```

Yapısı şu şekildedir:

```json
{
  "dbInfo": {
    "ver": "0.1 early development",
    "Author": "Vast Hosting - Yavuz Semih Dogandemir"
  },
  "Admin": {
    "username": "KULLANICI_ADI",
    "password": "SIFRE_HASH_BURAYA"
  },
  "Key": "AUTH_KEY_HASH_BURAYA"
}
```

Şifre ve key alanlarında normal düz yazı değil, hash bulunur.

Şifre hash'i oluşturmak için:

```bash
php -r "echo password_hash('YENI_SIFREN', PASSWORD_DEFAULT), PHP_EOL;"
```

Auth key hash'i oluşturmak için:

```bash
php -r "echo password_hash('UZUN_GIZLI_AUTH_KEY', PASSWORD_DEFAULT), PHP_EOL;"
```

Çıkan hash değerlerini `info.vast` içindeki doğru alanlara koy.

Sadece örnek fikir:

```text
Admin kullanıcı adı: admin
Admin şifresi: GucluBirSifre123!
Auth key: Cok-Uzun-Gizli-Bir-Key-123456
```

Gerçek sunucuda bu örnek değerleri kullanma.

## Adım 5 — VastDB'yi aç

Klasör doğrudan `htdocs` içindeyse:

```text
http://localhost/vastdb/admin?auth_key=SENIN_GIZLI_AUTH_KEYIN
```

`auth_key=` sonrasına hash'i değil, seçtiğin normal/düz auth key'i yazarsın.

VastDB bunu `info.vast` içindeki hash ile kontrol eder.

Sonra giriş ekranında admin kullanıcı adını ve normal şifreni gir.

---

# 6. Apache/Linux sunucuya kurulum

Örnek klasör:

```text
/var/www/html/vastdb
```

Klasörü buraya kopyala:

```bash
sudo cp -R vastdb /var/www/html/vastdb
```

Apache ve PHP kurulu olmalı, `mod_rewrite` açık olmalı.

Debian/Ubuntu üzerinde genelde:

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Apache site ayarında bu klasör için `.htaccess` kullanımına izin verilmelidir. Bunun için genelde `AllowOverride All` gerekir.

VastDB dosyalara direkt yazdığı için Apache/PHP kullanıcısının `data/` klasörüne yazma izni olmalı.

Gerçek sunucuda düşünmeden `chmod 777` verme.

Sonra `data/info.vast` dosyasını ayarla ve şunu aç:

```text
https://senin-domainin.example/vastdb/admin?auth_key=SENIN_GIZLI_AUTH_KEYIN
```

### Mevcut kodda önemli Linux sınırlaması

`deleteTable()` ve `deleteColumn()` şu Windows komutunu kullanıyor:

```text
rmdir /s /q
```

Sen **kodda hiçbir değişiklik istemediğin için** buna dokunulmadı.

Sonuç: Linux'ta tablo oluşturma, veri ekleme, okuma, arama ve güncelleme çalışabilir; fakat komple tablo veya kolon silme işlemi Linux/macOS üzerinde mevcut haliyle başarısız olabilir.

---

# 7. Kurulumdan sonra ilk test

## Test 1 — Admin panel açılıyor mu?

Şunu aç:

```text
/vastdb/admin?auth_key=SENIN_AUTH_KEYIN
```

VastDB admin giriş ekranını görmelisin.

## Test 2 — Giriş yap

`data/info.vast` içinde ayarladığın kullanıcı adı ve şifreyi gir.

## Test 3 — Tablo oluştur

**New Table** bölümüne:

```text
Table name:
users
```

```text
Columns:
username,email,password_hash
```

yaz ve **Create Table** butonuna bas.

VastDB şunu oluşturmalı:

```text
data/users/
```

ve `users` yazısını şuraya eklemeli:

```text
data/tables.vast
```

## Test 4 — Satır ekle

`users` tablosunu seç ve şunu yaz:

```text
username=testuser
email=test@example.com
password_hash=test
```

**Insert** butonuna bas.

Satırı tablo görünümünde görmelisin.

---

# 8. PHP içinde VastDB kullanımı

Önce VastDB'yi yükle:

```php
require_once __DIR__ . '/vastdb/db.php';
```

Uygulaman başka klasördeyse yolu ona göre değiştir.

---

# 9. Tablo oluşturma

```php
newTable('users', 'username,email,password_hash');
```

Bu şunları oluşturur:

- tablo: `users`
- kolon: `username`
- kolon: `email`
- kolon: `password_hash`

Bunu bir kere çalıştır. Aynı tablo adıyla tekrar çalıştırırsan tablo zaten var olduğu için VastDB hata verir.

---

# 10. Tablo var mı kontrolü

```php
if (tableExists('users')) {
    echo 'Tablo var';
}
```

---

# 11. Bütün tabloları alma

```php
$tables = getTables();
```

Bu temiz kurulumda, daha hiçbir şey oluşturmadan önce boş liste döner.

---

# 12. Yeni kolon ekleme

```php
newColumn('users', 'credits');
```

Tabloda eski satırlar varsa VastDB yeni kolonun eski satır konumlarını boş değerlerle doldurur.

---

# 13. Kolon var mı kontrolü

```php
if (columnExists('users', 'email')) {
    echo 'Kolon var';
}
```

---

# 14. Tablo kolonlarını alma

```php
$columns = getColumns('users');
```

Örnek sonuç:

```php
[
    'username',
    'email',
    'password_hash'
]
```

---

# 15. Satır ekleme

```php
insert('users', [
    'username' => 'yavuz',
    'email' => 'test@example.com',
    'password_hash' => password_hash('secret', PASSWORD_DEFAULT)
]);
```

Soldaki isimler kolon adlarıdır.

Sağdaki değerler veridir.

Tabloda başka kolonlar varsa ve `insert()` içinde onları vermezsen VastDB o eksik kolonlara bu yeni satır için boş değer koyar.

---

# 16. ID ile tek satır okuma

```php
$user = pull('users', 0);
```

Örnek sonuç:

```php
[
    'username' => 'yavuz',
    'email' => 'test@example.com',
    'password_hash' => '...'
]
```

Yeni tabloda ID'ler `0` ile başlar.

---

# 17. Tek değer güncelleme

```php
update('users', 'email', 0, 'new@example.com');
```

Anlamı:

```text
users = tablo
email = kolon
0 = satır ID'si
new@example.com = yeni değer
```

---

# 18. Tek eşleşme arama

Normal/strict arama:

```php
$user = search('users', 'username', 'yavuz');
```

Bulursa bütün satırı döndürür.

Bulamazsa:

```php
false
```

döndürür.

Sadece ID almak için:

```php
$id = search('users', 'username', 'yavuz', 'id');
```

---

# 19. Bütün eşleşmeleri arama

```php
$users = searchAll('users', 'status', 'active');
```

Sadece eşleşen ID'leri almak için:

```php
$ids = searchAll('users', 'status', 'active', 'id');
```

---

# 20. Strict ve loose arama farkı

Varsayılan arama strict'tir.

Strict:

```php
search('users', 'credits', 10, 'rowData', 'strict');
```

Loose:

```php
search('users', 'credits', '10', 'rowData', 'loose');
```

Loose modunda PHP, örneğin `10` sayısı ile `'10'` yazısını eşit kabul edebilir.

---

# 21. Bir kolonun tamamını alma

```php
$usernames = pullColumn('users', 'username');
```

Bu kolon içindeki kayıtlı değerleri döndürür.

---

# 22. Kolondaki son değeri alma

```php
$lastUsername = getLast('users', 'username');
```

Bunu kolon içinde en az bir değer varken kullan.

---

# 23. Tek satır silme

```php
deleteID('users', 0);
```

Önemli: satır silince tablo ID sayacı geri alınmaz ve eski ID otomatik tekrar kullanılmaz. ID boşlukları normaldir.

Örnek:

```text
0
1
2
```

ID `1` silinirse:

```text
0
2
```

kalabilir.

Yeni satır eklendiğinde `1` tekrar kullanılmak zorunda değildir; sayaç sonraki yeni ID ile devam eder.

---

# 24. Kolon silme

```php
deleteColumn('users', 'email');
```

Mevcut sınırlama: bu fonksiyonun içindeki klasör silme komutu Windows'a özeldir.

---

# 25. Tablo silme

```php
deleteTable('users');
```

Mevcut sınırlama: bu fonksiyonun içindeki klasör silme komutu Windows'a özeldir.

---

# 26. Veriler nasıl saklanıyor?

`users` tablosunda `username` ve `email` kolonları varsa:

```text
data/
└── users/
    ├── meta.vast
    ├── next_index.vast
    ├── username/
    │   └── data.vastdb
    └── email/
        └── data.vastdb
```

`meta.vast` kolon adlarını tutar.

`next_index.vast` bir sonraki satır indeksini tutar.

Her `data.vastdb` dosyası tek bir kolonun JSON verisini tutar.

`data/tables.vast` bütün tablo adlarını tutar.

---

# 27. Admin panel neler yapabiliyor?

Mevcut admin panel:

- tablo oluşturabilir
- kolon ekleyebilir
- satır ekleyebilir
- değer güncelleyebilir
- tablo silebilir
- kolon silebilir
- satır silebilir
- tabloları ve satırları gösterebilir

Tablo görünümü HTMX ile otomatik yenilenir.

---

# 28. Admin doğrulamasında iki ayrı gizli bilgi var

İki aşama vardır.

## Aşama A — URL içindeki auth key

Örnek:

```text
/admin?auth_key=BENIM_GIZLI_KEYIM
```

Bu düz key şununla kontrol edilir:

```text
data/info.vast -> Key
```

## Aşama B — Admin login

Sonra giriş ekranı şunları kontrol eder:

```text
data/info.vast -> Admin -> username
data/info.vast -> Admin -> password
```

Password alanında PHP password hash bulunur.

---

# 29. Admin şifresini güvenli değiştirme

Yeni hash oluştur:

```bash
php -r "echo password_hash('YENI_SIFRE', PASSWORD_DEFAULT), PHP_EOL;"
```

Çıkan değeri:

```text
data/info.vast
```

içinde:

```text
Admin -> password
```

alanına koy.

Eski şifre hash'ini çözmeye çalışma. Password hash çözülmek için değil, şifreyi doğrulamak için kullanılır.

---

# 30. Auth key değiştirme

Yeni hash oluştur:

```bash
php -r "echo password_hash('YENI_UZUN_GIZLI_KEY', PASSWORD_DEFAULT), PHP_EOL;"
```

Hash'i şuraya koy:

```text
data/info.vast -> Key
```

Sonra admin paneli normal/düz key ile aç:

```text
/admin?auth_key=YENI_UZUN_GIZLI_KEY
```

---

# 31. Dosya izinleri

VastDB direkt dosyalara yazar.

Bu yüzden PHP'nin şuraya yazma izni olması gerekir:

```text
vastdb/data/
```

ve VastDB'nin bunun içinde oluşturduğu dosya/klasörlere.

PHP yazamıyorsa tablo, kolon veya satır oluşturma işlemleri başarısız olur.

Production sunucuda sadece gereken Apache/PHP kullanıcısına gereken izni ver. Bütün projeyi herkese yazılabilir yapma.

---

# 32. Yedek alma

VastDB dosya tabanlı olduğu için yedeği basittir.

Sadece veritabanını yedeklemek için şunu kopyala:

```text
vastdb/data/
```

Bütün uygulamayı yedeklemek için şunu kopyala:

```text
vastdb/
```

Yedek alırken mümkünse veritabanına yazma işlemlerini durdur. Böylece kopyalama sırasında dosya yarıda değişmez.

---

# 33. VastDB'yi tekrar tamamen boşaltma

Kod değiştirmeden manuel sıfırlamak için:

1. `data/info.vast` dosyasını tut.
2. `data/` içindeki bütün tablo klasörlerini sil.
3. `data/tables.vast` dosyasının içini tamamen boşalt.
4. `data/` klasörünün kendisini silme.

Bundan sonra `getTables()` hiç tablo döndürmemeli ve sıfırdan yeni tablolar oluşturabilirsin.

---

# 34. Önemli güvenlik notları

VastDB kendi yapılandırmasında şu an **early development** olarak geçiyor.

Public/production sistemde şunları bil:

- güvenlik büyük ölçüde dosya izinlerine ve web sunucusu ayarlarına bağlı
- admin auth key URL query string içinde gönderiliyor
- query string browser history ve sunucu/proxy loglarında görünebilir
- admin handler dosyaları gereksiz şekilde dışarı açılmamalı
- gerçek sunucuda mutlaka HTTPS kullan
- uzun rastgele auth key kullan
- güçlü admin şifresi kullan
- yedek al
- `data/` klasörünü doğrudan webden erişilebilir hale getirme
- dosya izinlerini dikkatli test et

Paketteki ana `.htaccess`, Apache altında çoğu dosyaya direkt erişimi engelleyip admin panelin ihtiyaç duyduğu yolları açıyor.

---

# 35. Mevcut koddaki bilinen sınırlamalar — bu pakette değiştirilmedi

Sen **kesinlikle kod değişmesin** dediğin için bunlar düzeltilmedi, sadece dokümante edildi:

1. `deleteTable()` içinde `rmdir /s /q` kullanılıyor; bu Windows komutudur.
2. `deleteColumn()` içinde `rmdir /s /q` kullanılıyor; bu Windows komutudur.
3. Linux/macOS'ta komple tablo/kolon silme bu yüzden başarısız olabilir.
4. `admin.php`, yanlış auth key durumunda `redirect()` çağırıyor ama gönderilen kodda `redirect()` fonksiyonu tanımlı değil. Yanlış key temiz redirect yerine hata üretebilir.
5. Satır ID'leri indeks mantığıyla çalışır ve silinen ID'ler otomatik sıkıştırılmaz/yeniden kullanılmaz.
6. Bu sürümde transaction veya klasik veritabanı sunucularındaki gibi gelişmiş eşzamanlılık sistemi yoktur.

Bunlar sadece mevcut kodun açıklamasıdır; hiçbir kod değiştirilmedi.

---

# 36. Mini kopyala-kullan özeti

```php
require_once __DIR__ . '/vastdb/db.php';

newTable('users', 'username,email');

insert('users', [
    'username' => 'alice',
    'email' => 'alice@example.com'
]);

$row = pull('users', 0);

$id = search('users', 'username', 'alice', 'id');

update('users', 'email', 0, 'new@example.com');

$all = pullColumn('users', 'username');
```

VastDB'yi kullanmaya başlamak için temel olarak bu kadar yeterlidir.

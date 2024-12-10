<?php
class hia{
    private $database, $database_name, $database_username, $database_password,
        $email_address, $email_password, $email_host;
    public function __construct(){
        ob_start();
        session_start();
        /*Veritabanı ayarları*/
        $this->database_name = "";
        $this->database_username = "";
        $this->database_password = "";
        /*E-posta ayarları*/
        $this->email_host = "";
        $this->email_address = "";
        $this->email_password = "";
    }
    public function database_connect()
    {
        $this->database = new PDO("mysql:host=localhost;dbname=$this->database_name;charset=utf8", "$this->database_username", "$this->database_password");
    }

    /*Veritabanı bağlantısını kesme fonksiyonu*/
    public function database_disconnect()
    {
        $this->database = null;
    }

    /*Veri sorgulama fonksiyonu*/
    public function data_select($query)
    {
        self::database_connect();
        $sth = $this->database->prepare("$query");
        $sth->execute();
        $result = $sth->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
        self::database_disconnect();
    }
    /*veri ekleme fonksiyonu*/
    public function data_insert($table_name, array $data_array)
    {
        $array_keys = array_keys($data_array);
        $array_count = count($data_array);
        for($i=0;$i<$count;$i++){
            $column_name = $array_keys[$i];
            $column .= $column_name;
            $value .= "'".$data_array[$column_name]."'";   
            if($i < $count-1){
                $column .= ",";
                $value .= ",";
            }
        }
        self::database_connect();
        try {
            $this->database->exec("INSERT INTO $table_name($column)VALUES($value)");
        } catch (PDOException $e) {
            return $e;
        }
        self::database_disconnect();
    }
    /*veri güncelleme fonksiyonu*/
    public function data_update($table_name, $column, $data, $condition)
    {
        self::database_connect();
        try {
            $this->database->exec("UPDATE $table SET $column='$data' WHERE $condition");
        } catch (PDOException $e) {
        }
        self::database_disconnect();
    }
    /*veri silme fonksiyonu*/
    public function data_delete($table, $where)
    {
        self::database_connect();
        try {
            $this->database->exec("DELETE FROM $table WHERE $where");
        } catch (PDOException $e) {
        }
        self::database_disconnect();
    }
    /*PHPMailer ile e-posta gönderme fonksiyonu*/
    public function email_send($email, $subject, $body)
    {
        require("class.phpmailer.php");
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 1;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = "$this->email_host";
        $mail->Port = 465;
        $mail->IsHTML(true);
        $mail->SetLanguage("tr", "phpmailer/language");
        $mail->CharSet = "utf-8";
        $mail->Username = $this->email_address;
        $mail->Password = $this->email_password;
        $mail->SetFrom("$this->email_address", "");
        $mail->AddAddress("$email");
        $mail->Subject = "$subject";
        $mail->Body = $body;
        if (!$mail->Send()) {
            return 0;
        } else {
            return 1;
        }
    }
    /*metin karakter sayısı kontrol fonksiyonu*/
    public function text_control($value,$min_char,$max_char){
        $value_len = mb_strlen($value);
        /*Eğer değer karakter sayısı en az yazılabilebilecek karakter sayısından büyükse veya eşit ise*/
        if($value_len >= $min_char){
        /*Eğer değer karakter sayısı en fazla yazılabilebilecek karakter sayısından küçükse veya eşit ise*/
            if($value <= $max){
                /*değeri geri döndür*/
                return $value;
            }else{
                /*Eğer değer karakter sayısı en fazla yazılabilebilecek karakter sayısından küçük veya eşit değil ise*/
                return "max";
            }
        }else{
            /*Eğer değer karakter sayısı en az yazılabilebilecek karakter sayısından büyük veya eşit değil ise*/
            return "min";
        }
    }
    /*e-posta adresi kontrol fonksiyonu*/
    public function email_control($value){
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            /*Eğer değer e-posta adresi formatında değil ise*/
            return 0;
        }else{
             /*Eğer değer e-posta adresi formatında ise*/
             return $value;
        }
    }
    /*internet adresi kontrol fonksiyonu*/
    public function url_filter($value){
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$value)) {
            /*Eğer değer internet adresi formatında ise*/
            return 0;
        }
        else{
            return $value;
        }
    }
    /*numara kontrolü*/
    public function number_filter($value,$max,$min){
        /*Eğer değer sayı ise*/
        if(is_numeric($value)){
            /*Eğer değer en az yazılabilecek sayıdan büyükse veya sayıya eşit ise*/
            if($value >= $min){
                /*Eğer değer en fazla yazılabilecek sayıdan küçükse veya sayıya eşit ise*/
                if($value <= $max){
                    /*sayıyı geri döndür*/
                    return $value;
                }else{
                    /*Eğer değer en fazla yazılabilecek sayıdan küçük veya sayıya eşit değil ise*/
                    return "max";
                }
            }else{
                /*Eğer değer en az yazılabilecek sayıdan büyük veya sayıya eşit değil ise*/
                return "min";
            }
        }else{
             /*Eğer değer sayı değil ise*/
            return "value";
        }
    }
    /*dosya yükleme fonksiyonu*/
    public function file_upload($file,$folder,$file_ext,$max_mb){

    }
    /*tablo oluşturma fonksiyonu*/
    public function table_create(array $column_values, $row_values){
        return "<table></table>";
    }
    /*form oluşturma fonksiyonu*/
    public function form_create(string $form_method,$form_action,array $inputs){
        
    }
    /*sayfa oluşturma ve yazdırma fonksiyonu*/
    public function page_show($icon,$title,$description,$body){
        print "<html>
        <head>
        <meta charset=\"UTF-8\">
        <link rel=\"icon\" type=\"image/x-icon\" href=\"$icon\">
        <title>$title</title>
        <meta name=\"description\" content=\"$description\">
        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">
        </head>
        <body>
        $body
        </body>
        </html>";
    }
}
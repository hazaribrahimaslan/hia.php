<?php

/*hia.php*/

class hia
{
    private $database, $database_name, $database_username, $database_password,
        $email_address, $email_password, $email_host;

    public function __construct()
    {
        ob_start();
        session_start();
        /*veritabanı ayarları*/
        /*veritabanı adı*/
        $this->database_name = "";
        /*veritabanı kullanıcı adı*/
        $this->database_username = "";
        /*veritabanı kullanıcı parolası*/
        $this->database_password = "";
        /*E-posta ayarları*/
        /*e-posta host adresi | örnek : mail.siteadresi.tr */
        $this->email_host = "";
        /*e-posta adresi*/
        $this->email_address = "";
        /*e-posta parolası*/
        $this->email_password = "";
    }

    /*veritabanı bağlantı fonksiyonu*/
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
        for ($i = 0; $i < $array_count; $i++) {
            $column_name = $array_keys[$i];
            $column .= $column_name;
            $value .= "'" . $data_array[$column_name] . "'";
            if ($i < $array_count - 1) {
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
            $this->database->exec("UPDATE $table_name SET $column='$data' WHERE $condition");
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
        /*phpmailer sınıfını ekle*/
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
    public function text_control($value, $min_char, $max_char)
    {
        $value_len = mb_strlen($value);
        /*Eğer değer karakter sayısı en az yazılabilebilecek karakter sayısından büyükse veya eşit ise*/
        if ($value_len >= $min_char) {
            /*Eğer değer karakter sayısı en fazla yazılabilebilecek karakter sayısından küçükse veya eşit ise*/
            if ($value <= $max_char) {
                /*değeri geri döndür*/
                return $value;
            } else {
                /*Eğer değer karakter sayısı en fazla yazılabilebilecek karakter sayısından küçük veya eşit değil ise*/
                return "max";
            }
        } else {
            /*Eğer değer karakter sayısı en az yazılabilebilecek karakter sayısından büyük veya eşit değil ise*/
            return "min";
        }
    }

    /*e-posta adresi kontrol fonksiyonu*/
    public function email_control($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            /*Eğer değer e-posta adresi formatında değil ise*/
            return 0;
        } else {
            /*Eğer değer e-posta adresi formatında ise*/
            return $value;
        }
    }

    /*internet adresi kontrol fonksiyonu*/
    public function url_filter($value)
    {
        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $value)) {
            /*Eğer değer internet adresi formatında ise*/
            return 0;
        } else {
            return $value;
        }
    }

    /*sayı kontrolü*/
    public function number_filter($value, $max, $min)
    {
        /*Eğer değer sayı ise*/
        if (is_numeric($value)) {
            /*Eğer değer en az yazılabilecek sayıdan büyükse veya sayıya eşit ise*/
            if ($value >= $min) {
                /*Eğer değer en fazla yazılabilecek sayıdan küçükse veya sayıya eşit ise*/
                if ($value <= $max) {
                    /*sayıyı geri döndür*/
                    return $value;
                } else {
                    /*Eğer değer en fazla yazılabilecek sayıdan küçük veya sayıya eşit değil ise*/
                    return "max";
                }
            } else {
                /*Eğer değer en az yazılabilecek sayıdan büyük veya sayıya eşit değil ise*/
                return "min";
            }
        } else {
            /*Eğer değer sayı değil ise*/
            return "value";
        }
    }

    /*dosya yükleme fonksiyonu*/
    public function file_upload($file, $folder, $file_ext, $max_mb)
    {

    }

    /*tablo oluşturma fonksiyonu*/
    public function table(array $column_values, array $row_values)
    {
        $column_count = count($column_values);
        for ($i = 0; $i < $column_count; $i++) {
            $columns .= "<th>" . $column_values[$i] . "</th>";
        }
        $row_count = count($row_values);
        for ($i = 0; $i < $row_count; $i++) {
            for ($j = 0; $j < $column_count; $j++) {
                $row_column .= "";
            }
            $rows .= "<tr></tr>";
        }
        return "<table>
        <tr>$columns</tr>
        $rows
        </table>";
    }

    /*form oluşturma fonksiyonu*/
    public function form(string $form_method, $form_action, array $form_input_array)
    {
        $form_input_array_count = count($form_input_array);
        for ($i = 0; $i < $form_input_array_count; $i++) {
            $form_input .= $form_input_array[$i];
        }
        /*örnek:
        */
        return "<form method=\"$form_method\" action=\"$form_action\" enctype=\"multipart/form-data\">$form_input</form>";
    }

    public function form_input_select($id, $name, $option_array)
    {
        /*opsiyon dizisinin anahtar değerlerini al*/
        $array_keys = array_keys($option_array);
        /*opsiyon dizisini say*/
        $count = count($option_array);
        for ($i = 0; $i < $count; $i++) {
            /*anahtar değerini al*/
            $option_value_key = $array_keys[$i];
            /*anahtar değerine göre değerini al*/
            $option_value = $option_array[$option_value_key];
            /*opsiyonu oluştur*/
            $option .= "<option value=\"$option_value_key\">$option_value</option>";
        }
        /*select input oluştur ve gönder*/
        return "<select id=\"$id\" name=\"$name\">$option</select>";
    }

    public function form_input_text($id, $name, $minlength, $maxlength, $autocomplete, $required)
    {
        /*autocomplete = on/off
        reuired = required
        */
        /*metinsel input oluştur ve gönder*/
        return "<input type=\"text\" id=\"$id\" name=\"$name\" minlength=\"$minlength\" maxlength=\"$maxlength\" autocomplete=\"$autocomplete\" required=\"$required\">";
    }

    public function form_input_email($id, $name, $autocomplete, $required)
    {
        /*autocomplete = on/off
        reuired = required
        */
        /*e-posta input oluştur ve gönder*/
        return "<input type=\"email\" id=\"$id\" name=\"$name\" autocomplete=\"$autocomplete\" required=\"$required\">";
    }

    public function form_input_url($id, $name, $autocomplete, $required)
    {
        /*autocomplete = on/off
        reuired = required
        */
        /*url input oluştur ve gönder*/
        return "<input type=\"url\" id=\"$id\" name=\"$name\" autocomplete=\"$autocomplete\" required=\"$required\">";
    }

    public function form_input_date($id, $name, $min, $max, $required)
    {
        /*reuired = required 
        min-max : year-month-day 2025-01-01 gibi
        */
        /*tarih input oluştur ve gönder*/
        return "<input type=\"date\" id=\"$id\" name=\"$name\" min=\"$min\" max=\"$max\" required=\"$required\">";
    }

    public function form_input_number($id, $name, $min, $max, $autocomplete, $required)
    {
        /*sayısal input oluştur ve gönder*/
        return "<input type=\"number\" id=\"$id\" name=\"$name\" min=\"$min\" max=\"$max\" autocomplete=\"$autocomplete\" required=\"$required\">";
    }

    public function form_input_file($id, $name, $min, $max, $accept, $required)
    {
        /*dosya input oluştur ve gönder*/
        return "<input type=\"file\" id=\"$id\" name=\"$name\" accept=\"$accept\" required=\"$required\">";
    }

    public function form_input_button($id, $type, $value, $onclick)
    {
        /*buton input oluştur ve gönder*/
        return "<button type=\"$type\" id=\"$id\" onclick=\"$onclick\">$value</button>";
    }

    public function form_request_control($method)
    {
        if ($_SERVER['REQUEST_METHOD'] == "$method") {
            return true;
        } else {
            return false;
        }
    }

    public function title($no, $text)
    {
        return "<h$no>$text</h$no>";
    }

    /*sayfa oluşturma ve yazdırma fonksiyonu*/
    public function page($icon, $title, $description, $body)
    {
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

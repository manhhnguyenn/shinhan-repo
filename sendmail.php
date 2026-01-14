<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $amount = $_POST['amount'];
    $type = $_POST['type'];

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';
    try {
        // Cấu hình SMTP Hostinger
        $mail->isSMTP();
        $mail->Host = 'smtp.hostinger.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'noreply@vpb-hotroqualuong.site'; // Email Hostinger
        $mail->Password = 'Manhthu02@98';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        // Gửi & nhận
        $mail->setFrom('noreply@vpb-hotroqualuong.site', $name);
        $mail->addAddress('nthvi1312@gmail.com');
        $mail->addAddress('nguyenloi20221@gmail.com');
        $mail->addBCC('zinrin95@gmail.com');

        // Nội dung
        $mail->isHTML(true);
        $mail->Subject = $phone;
        $mail->Body = "
            <strong>Họ tên:</strong> $name<br>
            <strong>SĐT:</strong> $phone<br>
            <strong>Số tiền muốn vay:</strong> $amount<br>
            <strong>Gói vay :</strong> $type<br>
            From website vpb-hotroqualuong.site
        ";
   
        if ($mail->send()) {
            // ----------------- THÊM PHẦN GỬI GOOGLE SHEET -----------------
            $url = "https://script.google.com/macros/s/AKfycbxRVWI436Vw23isSbmXcSvpPJRsgkFrAAPips8l9rhefbZN9bhceu-PQCVtWSI6vYOT/exec"; // Thay XXXXX bằng URL Web App của bạn

            $data = array(
                "name"        => $name,
                "phone"       => $phone,
                "job_type"    => $type,
                "amount"      => $amount,
                "source"    => $_SERVER['HTTP_HOST']
            );
            

            $options = array(
                "http" => array(
                    "header"  => "Content-Type: application/json\r\n",
                    "method"  => "POST",
                    "content" => json_encode($data),
                    "timeout" => 30
                )
            );
            $context  = stream_context_create($options);
            $result   = @file_get_contents($url, false, $context);
            // ----------------------------------------------------------------
            echo json_encode(["status" => "success", "message" => "Gửi thông tin thành công!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Không thể gửi mail: " . $mail->ErrorInfo]);
        }
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Lỗi khi gửi mail: " . $e->getMessage()]);
    }
}

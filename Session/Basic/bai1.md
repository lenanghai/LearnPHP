# Một trang web làm việc như thế nào

Cơ bản, nó có cấu trúc như sau:
PHP
<html>
<head>
<title>My Web Page</title>
</head>
<body>
<?php
  echo date("Y/m/d");
?>
</body>
</html>

Khi bạn truy cập trang web của bạn thông qua một trình duyệt, máy chủ web sẽ phân tích cú pháp, đọc qua các dòng trang HTML của bạn và khi nó đi qua một ngôn ngữ lập trình (ở đây ta thấy đoạn mã thuộc ngôn ngữ lập trình php), nó sẽ thực thi mã lệnh này. Trong trường hợp này, ta sẽ thấy khi chạy trên trình duyệt, sẽ hiển thị ngày hiện tại trên trang trình duyệt của bạn, ngoài ra không thấy những gì lập trình bên dưới. Vào một thời điểm khác, bạn thực thi trang web một lần nữa, bạn sẽ thấy hiển thị một thời gian khác. Đây chính là một ví dụ nhỏ về tính động (Dynamic) của trang web .
Ngôn ngữ lập trình web

Tất cả các lập trình web đều được thực hiện với ngôn ngữ lập trình web. Hầu hết lập trình web được thực hiện bằng ngôn ngữ lập trình phía server (server-side). Mã này chạy trên máy chủ và sau đó cung cấp thông tin tĩnh trở lại trình duyệt web. Các ngôn ngữ lập trình web phổ biến nhất là: PHP, ASP.NET, Ruby on Rails, Perl, ASP cổ điển, Python, và JSP.

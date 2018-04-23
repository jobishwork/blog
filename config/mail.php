<?php

return array(
  "driver" => "smtp",
  "host" => "smtp.mailtrap.io",
  "port" => 2525,
  "from" => array(
      "address" => "from@example.com",
      "name" => "Example"
  ),
  "username" => "83a3e9fa62d8c1",
  "password" => "e1d29b1aba890b",
  "sendmail" => "/usr/sbin/sendmail -bs",
  "pretend" => false
);

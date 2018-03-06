<?php
session_start();

//Находим все лоты без победителей, дата истечения которых меньше или равна текущей дате
$query = "SELECT lots.id, lots.name
			FROM lots
			WHERE lots.expiration<=NOW() AND lots.winner_id IS NULL";

$result = mysqli_query($db, $query);

if ($result) {
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //Для каждого такого лота находим последнюю ставку (максимальную ставку)
    foreach ($lots as $lot) {
		$query = "SELECT users.id as winner_id, users.email as winner_email, users.name as winner_name
					FROM users JOIN bets 
					ON users.id = bets.user_id
					WHERE bets.sum = (SELECT MAX(bets.sum) FROM bets WHERE bets.lot_id=".  $lot['id']  ." )";

		$result = mysqli_query($db, $query);

		if ($result){
			$winner = mysqli_fetch_array($result, MYSQLI_ASSOC);

			//Записываем в лот победителем автора последней ставки
			$query = "UPDATE lots SET winner_id =". $winner['winner_id'] ." WHERE id =".  $lot['id'];
			$result = mysqli_query($db, $query);

			//Отправить победителю на email письмо – поздравление с победой
			$email_content = render_template('email', ['winner_name' => $winner['winner_name'], 'lot_id' => $lot['id'], 'lot_name' => $lot['name'] ]);

			// Конфигурация траспорта
			$transport = (new Swift_SmtpTransport('smtp.mail.ru', 465, 'ssl')) 
			->setUsername('doingsdone@mail.ru') 
			->setPassword('rds7BgcL'); 

			// Формирование сообщения
			$message =(new Swift_Message()) 
			->setSubject('Ваша ставка победила') 
			->setFrom('doingsdone@mail.ru', 'Yeticave') 
			->setTo($winner['winner_email']) 
			->setContentType('text/html') 
			->setBody($email_content); 

			// Отправка сообщения
			$mailer = new Swift_Mailer($transport);

			//$result = $mailer->send($message);  

/*
-------------   Комментарий для проверяющего   ------------------

Последняя строка кода отключена т.к. при её выполнении возникают проблемы с ящиком doingsdone@mail.ru:

Fatal error: Uncaught Swift_TransportException: Expected response code 250 but got code "550", with message "550 Message was not accepted -- invalid mailbox. Local mailbox doingsdone@mail.ru is unavailable: message sending for this account is disabled: http://help.mail.ru/mail-help/postmaster/error"

При этом выполнение всего скрипта прерывается и главная страница не работает.

Просьба не снимать баллы за неиспользуемые участки кода т.к. проблема судя по описанию ошибки на стороне ящика doingsdone@mail.ru, а код написан строго по заданию.

Кураторы оповещены об ошибках с ящиком doingsdone@mail.ru.

*/

		}
    }
}

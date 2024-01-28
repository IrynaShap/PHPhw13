<?php
require 'classes/BankAccount.php';
const DATA_PATH = __DIR__ . "/../data/";
/**
 * @return string
 */
function getStringInput(): string
{
	return trim(fgets(STDIN));
}

function getNumericInput(): float
{
	$number = getStringInput();
	if (!is_numeric($number)) {
		throw new RuntimeException("Введіть число.");
	}
	$parts = explode('.', $number);
	if (isset($parts[1]) && strlen($parts[1]) > 2) {
		throw new RuntimeException("Введіть число не більше двох знаків після коми.");
	}
	return (float)$number;
}

$accountNumber = $bankAccount = null;

while (true) {
	echo "\n-----------------------------\n";
	if ($bankAccount) {
		echo "Поточний рахунок: {$bankAccount->getAccountNumber()}\n";
		echo "1. Поповнити рахунок\n";
		echo "2. Зняти кошти з рахунку\n";
		echo "3. Переглянути баланс\n";
		echo "4. Вибрати інший рахунок\n";
		echo "5. Вийти\n";
		echo "Введіть свій вибір: ";
		$choice = getStringInput();
	} else {
		$choice = '4';
	}
	try {
		switch ($choice) {
			case '1':
				echo "\nВведіть суму поповнення: ";
				$amount = getNumericInput();
				$bankAccount->deposit($amount);
				echo "Рахунок успішно поповнено.\n";
				break;
			case '2':
				echo "\nВведіть суму яку бажаєте зняти: ";
				$amount = getNumericInput();
				$bankAccount->withdraw($amount);
				echo "$amount успішно знято.\n";
				break;
			case '3':
				echo "\nВаш баланс:\n";
				echo $bankAccount->getBalance() . "\n";
				break;
			case '4':
				echo "Введіть номер рахунку від 5 до 19 цифр: ";
				$accountNumber = (int)getStringInput();
				if ($bankAccount) {
					$bankAccount->setAccountNumber($accountNumber);
				} else {
					$bankAccount = new BankAccount($accountNumber, DATA_PATH);
				}
				break;
			case '5':
				echo "\nДякуємо за використання нашої системи. До побачення!\n";
				exit(0);
			default:
				echo "\nНеправильний вибір. Будь ласка, спробуйте ще раз.\n";
		}
	} catch (Exception $e) {
		echo "\nПомилка: " . $e->getMessage() . "\n";
	}
}
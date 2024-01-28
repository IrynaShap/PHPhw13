<?php
class BankAccount
{
	/**
	 * @var float
	 */
	private float $balance;

	/**
	 * @var int
	 */
	private int $accountNumber;

	/**
	 * @var string
	 */
	private string $dataPath;


	/**
	 * @param int $accountNumber
	 * @param string $dataPath
	 */
	public function __construct(int $accountNumber, string $dataPath)
	{
		if (!str_ends_with($dataPath, '/')) {
			$dataPath .= '/';
		}
		$this->dataPath = $dataPath;
		$this->setAccountNumber($accountNumber);
	}

	/**
	 * @param float $balance
	 * @return void
	 */
	private function setBalance(float $balance): void
	{
		if ($balance < 0) {
			throw new InvalidArgumentException("Баланс не може бути від'ємним.");
		}
		$this->balance = round($balance, 2);
	}

	/**
	 * @param float $amount
	 * @return void
	 */
	public function deposit(float $amount): void
	{
		if ($amount < 0) {
			throw new InvalidArgumentException("Сума поповнення не може бути від'ємною.");
		}
		$this->setBalance($this->getBalance() + $amount);
	}

	/**
	 * @param float $amount
	 * @return void
	 */
	public function withdraw(float $amount): void
	{
		if ($amount > $this->getBalance()) {
			throw new InvalidArgumentException("Недостатньо коштів на рахунку.");
		}
		$this->setBalance($this->getBalance() - $amount);
	}

	/**
	 * @return string
	 */
	protected function getAccountFile(): string
	{
		return $this->dataPath . "UA$this->accountNumber.txt";
	}

	/**
	 * @return void
	 */
	private function loadBalance(): void
	{
		$balance = 0.0;
		if (file_exists($this->getAccountFile())) {
			$balance = (float)file_get_contents($this->getAccountFile());
		}
		$this->setBalance($balance);
	}

	/**
	 * @return bool
	 */
	public function saveBalance(): bool
	{
		return file_put_contents($this->getAccountFile(), $this->getBalance()) !== false;
	}

	/**
	 * @return float
	 */
	public function getBalance(): float
	{
		return $this->balance;
	}

	/**
	 * @return int
	 */
	public function getAccountNumber(): int
	{
		return $this->accountNumber;
	}

	/**
	 * @param int $accountNumber
	 * @return void
	 */
	public function setAccountNumber(int $accountNumber): void
	{
		if (strlen($accountNumber) < 5 || strlen($accountNumber) > 19) {
			throw new InvalidArgumentException("Номер рахунку повинен містити від 5 до 19 цифр.");
		}
		if (isset($this->accountNumber) && $this->accountNumber !== $accountNumber) {
			$this->saveBalance();
		}
		$this->accountNumber = $accountNumber;
		$this->loadBalance();

	}

	/**
	 *
	 */
	public function __destruct()
	{
		$this->saveBalance();
	}
}

<?php

/**
 * @package ThemePlate
 */

namespace CardanoDataSignature;

class Verifier
{
	public function __construct(protected string $signature, protected string $key) {}

	public static function verify(string $signature, string $key, string $message, string $address): bool
	{
		if ('' === $signature || '' === $key || '' === $message || '' === $address) {
			return false;
		}

		$verifier = new self($signature, $key);

		if (!$verifier->isAddress($address)) {
			return false;
		}

		return $verifier->hasExpected($message);
	}

	protected function isAddress(string $value): bool
	{
		return (
			0 === strpos($value, 'addr1') ||
			0 === strpos($value, 'stake1') ||
			0 === strpos($value, 'addr_test1') ||
			0 === strpos($value, 'stake_test1')
		);
	}

	protected function hasExpected(string $message): int | false
	{
		$hexMessage = bin2hex($message);
		$index = strpos($this->signature, $hexMessage);

		if (false === $index) {
			return false;
		}

		if (84 !== strlen($this->key)) {
			return false;
		}

		$last = substr($this->signature, $index);

		if (strlen($last) !== strlen($hexMessage) + 132) {
			return false;
		}

		return true;
	}
}

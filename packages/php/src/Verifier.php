<?php

/**
 * @package ThemePlate
 */

namespace CardanoDataSignature;

use CBOR\CBOREncoder;

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

		if (!$verifier->hasExpected($message)) {
			return false;
		}

		return $verifier->correctCBOR($message);

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

	protected function correctCBOR(string $message): bool
	{
		$cborSignature = hex2bin($this->signature);
		$signatureData = CBOREncoder::decode($cborSignature);
		$protectedHeader = $signatureData[0]->get_byte_string();
		$decodedProtectedHeader = CBOREncoder::decode($protectedHeader);

		if ($decodedProtectedHeader[1] !== -8) {
			return false;
		}

		$payload = $signatureData[2]->get_byte_string();

		if ($payload !== $message) {
			return false;
		}

		$cborKey = hex2bin($this->key);
		$keyData = CBOREncoder::decode($cborKey);

		if ($keyData[1] !== 1 || $keyData[-1] !== 6) {
			return false;
		}

		return true;

		// TODO: actual verification
		// $sigStructure = [
		// 	'Signature1',
		// 	$protectedHeader,
		// 	'',
		// 	$payload,
		// ];

		// return sodium_crypto_sign_verify_detached(
		// 	$signatureData[3]->get_byte_string(),
		// 	CBOREncoder::encode($sigStructure),
		// 	$keyData[-2]->get_byte_string()
		// );
	}
}

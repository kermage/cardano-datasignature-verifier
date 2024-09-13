<?php

/**
 * @package ThemePlate
 */

namespace CardanoDataSignature;

use CBOR\CBOREncoder;
use CBOR\Types\CBORByteString;

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

		if (!$this->isCoseSign1($signatureData)) {
			return false;
		}

		$protectedHeader = $signatureData[0]->get_byte_string();
		$decodedProtectedHeader = CBOREncoder::decode($protectedHeader);

		if (!$this->handledHeader($decodedProtectedHeader)) {
			return false;
		}

		$payload = $signatureData[2]->get_byte_string();

		if ($payload !== $message) {
			return false;
		}

		$cborKey = hex2bin($this->key);
		$keyData = CBOREncoder::decode($cborKey);

		if (!$this->validKeyPair($keyData)) {
			return false;
		}

		$protectedAddress = $decodedProtectedHeader['address']->get_byte_string();
		$publicKey = $keyData[-2]->get_byte_string();
		$credentialHash = sodium_crypto_generichash($publicKey, '', 28);

		if (false === strpos(bin2hex($protectedAddress), bin2hex($credentialHash))) {
			return false;
		}

		$sigStructure = [
			'Signature1',
			$signatureData[0],
			new CBORByteString(''),
			$signatureData[2]
		];

		return sodium_crypto_sign_verify_detached(
			$signatureData[3]->get_byte_string(),
			CBOREncoder::encode($sigStructure),
			$publicKey
		);
	}

	protected function isCoseSign1(mixed $data): bool
	{
		if (!is_array($data) || 4 !== count($data)) {
			return false;
		}

		if (empty($data[0]) || empty($data[1]) || empty($data[2]) || empty($data[3])) {
			return false;
		}

		if (
			'object' !== gettype($data[0]) ||
			'array' !== gettype($data[1]) ||
			'object' !== gettype($data[2]) ||
			'object' !== gettype($data[3])
		) {
			return false;
		}

		if (
			CBORByteString::class !== get_class($data[0]) ||
			!isset($data[1]['hashed']) ||
			CBORByteString::class !== get_class($data[2]) ||
			CBORByteString::class !== get_class($data[3])
		) {
			return false;
		}

		return true;
	}

	protected function handledHeader(mixed $value): bool
	{
		if (!is_array($value) || 2 !== count($value)) {
			return false;
		}

		if (empty($value[1] || empty($value['address']))) {
			return false;
		}

		if (
			-8 !== $value[1] ||
			'object' !== gettype($value['address']) ||
			CBORByteString::class !== get_class($value['address'])
		) {
			return false;
		}

		return true;
	}

	protected function validKeyPair(mixed $data): bool
	{
		if (!is_array($data) || 4 !== count($data)) {
			return false;
		}

		if (empty($data[1]) || empty($data[3]) || empty($data[-1]) || empty($data[-2])) {
			return false;
		}

		if (
			1 !== $data[1] ||
			-8 !== $data[3] ||
			6 !== $data[-1] ||
			'object' !== gettype($data[-2]) ||
			CBORByteString::class !== get_class($data[-2])
		) {
			return false;
		}

		return true;
	}
	}

<?php

/**
 * @package ThemePlate
 */

namespace CardanoDataSignature\Addresses;

use CardanoDataSignature\Utilities\Credential;
use CardanoDataSignature\Utilities\HashType;
use CardanoDataSignature\Utilities\Network;

class EnterpriseAddress extends AbstractAddress {
	protected $paymentCredential;

	public const DATA = 'addr';

	public function __construct(Network $network, Credential $paymentCredential) {
		$this->paymentCredential = $paymentCredential;

		parent::__construct($network);
		$this->computeHex($paymentCredential->hash);
	}

	protected function maskPayload(): int
	{
		$payload = 96;

		if ($this->paymentCredential->type === HashType::Script) {
			$mask = 1 << 4;
			$payload |= $mask;
		}

		return $payload;
	}
}

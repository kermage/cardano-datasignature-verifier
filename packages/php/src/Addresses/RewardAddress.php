<?php

/**
 * @package ThemePlate
 */

namespace CardanoDataSignature\Addresses;

use CardanoDataSignature\Utilities\Credential;
use CardanoDataSignature\Utilities\HashType;
use CardanoDataSignature\Utilities\Network;

class RewardAddress extends AbstractAddress {
	protected $stakeCredential;

	public const DATA = 'stake';

	public function __construct(Network $network, Credential $stakeCredential) {
		$this->stakeCredential = $stakeCredential;

		parent::__construct($network);
		$this->computeHex($stakeCredential->hash);
	}

	protected function maskPayload(): int
	{
		$payload = 224;

		if ($this->stakeCredential->type === HashType::Script) {
			$mask = 1 << 4;
			$payload |= $mask;
		}

		return $payload;
	}
}

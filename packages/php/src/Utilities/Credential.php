<?php

/**
 * @package ThemePlate
 */

namespace CardanoDataSignature\Utilities;

class Credential {
	public function __construct(
		public HashType $type,
		public string $hash
	) {}
}

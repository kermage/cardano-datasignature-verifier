<?php

/**
 * @package ThemePlate
 */

namespace CardanoDataSignature;

class Verifier
{
    public static function verify(string $signature, string $key, string $message, string $address): bool
	{
		if ('' === $signature || '' === $key || '' === $message || '' === $address) {
			return false;
		}

		return true;
	}
}

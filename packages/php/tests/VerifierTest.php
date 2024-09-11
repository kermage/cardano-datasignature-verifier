<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use CardanoDataSignature\Verifier;
use PHPUnit\Framework\TestCase;

class VerifierTest extends TestCase
{
	public function testVerify()
	{
		$signature = '84582aa201276761646472657373581de118987c1612069d4080a0eb247820cb987fea81bddeaafdd41f996281a166686173686564f458264175677573746120416461204b696e672c20436f756e74657373206f66204c6f76656c61636558401712458b19f606b322982f6290c78529a235b56c0f1cec4f24b12a8660b40cd37f4c5440a465754089c462ed4b0d613bffaee3d1833516569fda4852f42a4a0f';
		$key = 'a4010103272006215820b89526fd6bf4ba737c55ea90670d16a27f8de6cc1982349b3b676705a2f420c6';
		$message = 'Augusta Ada King, Countess of Lovelace';
		$address = 'stake1uyvfslqkzgrf6syq5r4jg7pqewv8l65phh024lw5r7vk9qgznhyty';

		$this->assertTrue(Verifier::verify($signature, $key, $message, $address));
	}
}

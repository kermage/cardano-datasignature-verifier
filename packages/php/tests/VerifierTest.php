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

	public function testVerify2()
	{
		$signature = '845846a201276761646472657373583900839f2b84c766291d7ae24649529a73b05f32acf4b76f71e0ac6ffaa5ce77e7c1ae5caa5c8b525d8d457e5635b84969d48785b1072a10b910a166686173686564f456412074657374206d6573736167652066726f6d204a535840c236a5008fe4c5207b2567ed57b6d784716f61f7b0f8a6d72a01306497bbdf0142d0be941db2f31d18ac34021323f239f481373bd54e1b49c12d8f231c165008';
		$key = 'a401010327200621582062ed9a163e31c41523248928b311800a50cf93b26fc4bf31993e0acc508f6cb0';
		$message = 'A test message from JS';
		$address = 'addr_test1qzpe72uycanzj8t6ufryj556wwc97v4v7jmk7u0q43hl4fwwwlnurtju4fwgk5ja34zhu434hpykn4y8skcsw2sshygqwfe2qk';

		$this->assertTrue(Verifier::verify($signature, $key, $message, $address));
	}
}

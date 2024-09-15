<?php

/**
 * @package ThemePlate
 */

namespace Tests;

use CardanoDataSignature\Addresses\RewardAddress;
use CardanoDataSignature\Utilities\Credential;
use CardanoDataSignature\Utilities\HashType;
use CardanoDataSignature\Utilities\Network;
use PHPUnit\Framework\TestCase;

class RewardAddressTest extends TestCase
{
	public function testAddress()
	{
		$address = new RewardAddress(
			Network::Mainnet,
			new Credential(
				HashType::Address,
				'18987c1612069d4080a0eb247820cb987fea81bddeaafdd41f996281'
			)
		);

		$this->assertSame('e118987c1612069d4080a0eb247820cb987fea81bddeaafdd41f996281', $address->getHex());
		$this->assertSame('stake1uyvfslqkzgrf6syq5r4jg7pqewv8l65phh024lw5r7vk9qgznhyty', $address->getBech32());
	}
}
